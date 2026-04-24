<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransactionService
{
    public function createOnlineTransaction(Cart $cart, int $addressId, int $amount)
    {
        $tx = null;

        try {
            DB::beginTransaction();

            $address = $addressId ? Address::findOrFail($addressId)->full_address : null;

            $tx = Transaction::create([
                'user_id' => $cart->user_id,
                'admin_id' => null,
                'cart_id' => $cart->id,
                'address' => $address,
                'amount' => $amount,
                'method' => 'online',
                'gateway' => 'zarinpal',
                'status' => 'pending',
                'shipping_status' => false,
            ]);

            $this->reserveCartStock($cart);
            $cart->update([
                'locked' => true,
            ]);

            $baseUrl = config('services.zarinpal.sandbox')
                ? 'https://sandbox.zarinpal.com/pg/v4/payment'
                : 'https://api.zarinpal.com/pg/v4/payment';

            $response = Http::post("$baseUrl/request.json", [
                'merchant_id' => config('services.zarinpal.merchant_id'),
                'amount' => $amount,
                'callback_url' => route('payment.callback'),
                'description' => "پرداخت سبد #{$cart->id}",
                'metadata' => ['cart_id' => $cart->id],
                'currency' => 'IRT',
            ]);

            if (!$response->ok()) {
                $tx->update([
                    'status' => 'failed',
                    'response' => $response->body(),
                ]);
                $this->releaseCartStock($cart);
                throw new \RuntimeException('Invalid response from Zarinpal');
            }

            $data = $response->json();

            if (!isset($data['data']['authority'])) {
                $tx->update([
                    'status' => 'failed',
                    'response' => json_encode($data['errors'] ?? 'Payment creation failed'),
                ]);
                $this->releaseCartStock($cart);
                throw new \RuntimeException($data['errors']['message'] ?? 'Payment creation failed');
            }

            $authority = $data['data']['authority'];

            $tx->update([
                'authority' => $authority,
                'response' => $data,
            ]);

            DB::commit();

            $paymentUrl = config('services.zarinpal.sandbox')
                ? "https://sandbox.zarinpal.com/pg/StartPay/{$authority}"
                : "https://www.zarinpal.com/pg/StartPay/{$authority}";

            return [
                'success' => true,
                'payment_url' => $paymentUrl,
                'transaction' => $tx,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($tx) {
                $tx->update([
                    'status' => 'failed',
                    'response' => json_encode(['error' => $e->getMessage()]),
                ]);
                $this->releaseCartStock($cart);
            }

            throw $e;
        }
    }

    public function createTransactionByAdmin(Cart $cart, int $userId, ?int $addressId, int $price)
    {
        $amount = $price;
        $address = $addressId != null ? Address::findOrFail($addressId)->full_address : null;

        return DB::transaction(function () use ($cart, $amount, $address, $userId) {
            $tx = Transaction::create([
                'user_id' => $userId,
                'admin_id' => auth()->id(),
                'cart_id' => $cart->id,
                'address' => $address,
                'amount' => $amount,
                'method' => 'online',
                'gateway' => 'zarinpal',
                'status' => 'success',
                'shipping_status' => false,
            ]);

            $this->reserveCartStock($cart);
            $this->createTransactionItemsFromCart($tx);
        });
    }

    public function verifyByAuthority(?string $authority): array
    {
        $tx = Transaction::where('authority', $authority)->first();

        if (!$tx) {
            return [
                'success' => false,
                'message' => 'Transaction not found!',
            ];
        }

        if ($tx->status === 'success') {
            return [
                'success' => true,
                'message' => 'Transaction already verified and successful!',
                'transaction' => $tx,
            ];
        }

        try {
            $result = $this->verifyWithZarinpal($tx);

            if (isset($result['data']['ref_id'])) {
                DB::transaction(function () use ($tx, $result) {
                    $tx->update([
                        'status' => 'success',
                        'ref_id' => $result['data']['ref_id'],
                        'response' => $result,
                    ]);

                    $this->createTransactionItemsFromCart($tx);
                });

                $SmsService = app(SmsProviderService::class);
                $SmsService->gatewayPayOrderCustomer($tx->user->username, $tx->code);
                $SmsService->orderManager($user->username);

                return [
                    'success' => true,
                    'transaction' => $tx->fresh(),
                    'ref_id' => $result['data']['ref_id'],
                ];
            }

            DB::transaction(function () use ($tx, $result) {
                $tx->update([
                    'status' => 'failed',
                    'response' => $result,
                ]);

                if ($tx->cart) {
                    $this->releaseCartStock($tx->cart);
                }
            });

            Log::warning('Zarinpal verification failed', [
                'transaction_id' => $tx->id,
                'response' => $result,
            ]);

            return [
                'success' => false,
                'message' => $result['errors']['message'] ?? 'Payment failed',
                'response' => $result,
            ];
        } catch (\Throwable $e) {
            Log::error('Error verifying transaction', [
                'transaction_id' => $tx->id,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred during transaction verification',
                'transaction' => $tx,
            ];
        }
    }

    private function verifyWithZarinpal(Transaction $tx): array
    {
        $baseUrl = config('services.zarinpal.sandbox')
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
            : 'https://api.zarinpal.com/pg/v4/payment/verify.json';

        $response = Http::timeout(10)->post($baseUrl, [
            'merchant_id' => config('services.zarinpal.merchant_id'),
            'amount' => $tx->amount,
            'authority' => $tx->authority,
        ]);

        if (!$response->ok()) {
            Log::warning('Zarinpal HTTP request failed', [
                'transaction_id' => $tx->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['errors' => ['message' => 'Verification request failed']];
        }

        return $response->json();
    }

    public function createOfflineTransaction(array $data)
    {
        $user = auth()->user();

        $tx = Transaction::create([
            'user_id' => $user->id,
            'admin_id' => null,
            'cart_id' => $data['cart']->id ?? null,
            'address' => $data['address'] ?? null,
            'amount' => (int) ($data['amount'] ?? 0),
            'method' => $data['method'] ?? 'cheque',
            'gateway' => null,
            'status' => 'uploading',
            'shipping_status' => false,
            'cheque_image' => null,
        ]);

        $this->reserveCartStock($tx->cart);
        $this->createTransactionItemsFromCart($tx);

        if ($data['image']) {
            $this->updateOfflineTransactionImage($tx->id, $data['image']);
        }

        $smsService = app(SmsProviderService::class);
        $smsService->creditPayOrderCustomer($tx->user->username, $tx->code);
        $smsService->orderManager($user->username);
    }

    public function updateOfflineTransactionImage(int $transactionId, $image)
    {
        $chequeImagePath = null;

        $uploaded = $image;
        $originalName = pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploaded->getClientOriginalExtension();
        $uniqueName = $this->getNewImageUniqueName($originalName, $data['folder_id'] ?? null, $extension);

        try {
            $chequeImagePath = $uploaded->storeAs('images/cheques', $uniqueName, 'public');
        } catch (\Throwable $e) {
            $chequeImagePath = null;
            throw new Exception($e);
        }

        $updated = Transaction::where('id', '=', $transactionId)
            ->where('status', 'uploading')
            ->update([
                'cheque_image' => $chequeImagePath,
                'status' => 'pending'
            ]);

        if (!$updated) {
            if ($chequeImagePath && Storage::disk('public')->exists($chequeImagePath)) {
                Storage::disk('public')->delete($chequeImagePath);
            }

            throw new Exception("Transaction is not in 'uploading' status.");
        }
    }

    public function deleteOfflineTransactionImage(int $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->status != 'pending')
            throw new Exception("can't remove image!");

        if ($transaction->cheque_image && Storage::exists($transaction->cheque_image)) {
            Storage::delete($transaction->cheque_image);
        }

        $transaction->update([
            'cheque_image' => null,
            'status' => 'uploading',
        ]);
    }

    public function cancelOfflineTransaction(int $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->status != 'uploading')
            throw new Exception("can't cancel image!");

        $transaction->update([
            'status' => 'failed',
        ]);
    }

    private function getNewImageUniqueName(string $originalName, ?int $folderId = null, ?string $extension = null): string
    {
        $slug = Str::slug(substr($originalName, 0, 80)); // keep slug reasonably short
        $folderPart = $folderId ? "f{$folderId}-" : '';
        $time = time();
        $rand = Str::random(6);
        $ext = $extension ? '.' . ltrim($extension, '.') : '';

        return "{$folderPart}{$slug}-{$time}-{$rand}{$ext}";
    }

    public function approveTransaction(int $id, ?string $note = null)
    {
        $smsService = new SmsProviderService();
        $tx = Transaction::with('user')->find($id);

        if ($tx->status !== 'pending' && $tx->status !== 'uploading') {
            throw new \DomainException('Transaction is not pending or uploading!');
        }

        $user = $tx->user()->first();

        DB::transaction(function () use ($tx, $note) {
            $tx->update([
                'status' => 'accepted',
                'note' => $note ?? $tx->note
            ]);
        });

        $smsService->submitImage($user->username, "2");

        return $tx->user;
    }

    public function rejectTransaction(int $id, ?string $reason = null)
    {
        $tx = Transaction::with('cart.items.variant', 'user')->find($id);

        if ($tx->status !== 'pending' && $tx->status !== 'uploading') {
            throw new \DomainException('Transaction is not pending or uploading!');
        }

        DB::transaction(function () use ($tx, $reason) {
            $tx->update([
                'status' => 'rejected',
                'note' => $reason,
            ]);

            if ($tx->items) {
                foreach ($tx->items as $item) {
                    if ($item->productVariant) {
                        $item->productVariant->increment('quantity', $item->quantity);
                    }
                }
            }
        });

        return $tx->user;
    }

    public function reserveCartStock(Cart $cart): void
    {
        foreach ($cart->items as $item) {
            $variant = ProductVariant::find($item->product_variant_id);
            if (!$variant)
                throw new Exception('product_variant_not_found');
            if ($variant->quantity < $item->quantity)
                throw new Exception('insufficient_stock');

            $variant->decrement('quantity', $item->quantity);
        }
    }

    public function releaseCartStock(Cart $cart): void
    {
        foreach ($cart->items as $item) {
            $variant = ProductVariant::find($item->product_variant_id);
            if (!$variant)
                continue;
            $variant->increment('quantity', $item->quantity);
        }

        $cart->update([
            'locked' => false,
        ]);
    }

    public function createTransactionItemsFromCart(Transaction $tx): void
    {
        $cart = $tx->cart;

        foreach ($cart->items as $item) {
            $unitPrice = $item->unitPrice();
            $totalPrice = $item->totalPrice();
            $variant = $item->variant;

            $tx->items()->create([
                'user_id' => $tx->user_id,
                'product_name' => $variant->product->name,
                'pattern_name' => $variant->pattern?->name,
                'product_variant_id' => $variant->id,
                'sku' => $variant?->sku,
                'unit_price' => $unitPrice,
                'quantity' => $item->quantity,
                'total_price' => $totalPrice,
                'guarantee' => optional($item->guarantee)->name ?? null,
                'insurance' => optional($item->insurance)->name ?? null,
            ]);
        }

        $cart->items()->delete();
    }

    public function handleCallback(Request $request): array
    {
        $authority = $request->get('Authority') ?? null;

        if (!$authority) {
            return [
                'success' => false,
                'message' => 'authority_missing',
            ];
        }

        if ($request->get('Status') === "NOK") {
            $transaction = Transaction::where('authority', $authority)->first();

            if ($transaction) {
                if ($transaction->cart) {
                    $this->releaseCartStock($transaction->cart);
                }

                $transaction->update([
                    'status' => 'failed',
                    'response' => json_encode(['message' => 'User cancellation']),
                ]);
            }

            return [
                'success' => false,
                'message' => 'payment_cancelled'
            ];
        }

        return $this->verifyByAuthority($authority);
    }

    public function checkoutForUser(int $targetUserId, int $adminId, int $addressId, ?Cart $cart = null): array
    {
        if (!$cart) {
            $cart = Cart::where('user_id', $targetUserId)->first();
            if (!$cart)
                throw new Exception('Cart not found!');
        }

        return $this->createOnlineTransaction($cart, $adminId, $addressId);
    }

    public function getUserHistory(int $userId)
    {
        return TransactionItem::where('user_id', $userId)
            // ->where('status', 'success') // Only completed purchases
            ->latest()
            ->get();
    }

    public function getPaginatedTransactions(int $perPage = 20, int $page): array
    {
        $paginated = Transaction::query()
            ->with([
                'user:id,name,family,username',
                'user.addresses.province:id,name',
                'user.addresses.city:id,name',
                'admin:id,name,family',
                'items.productVariant',
                'items.productVariant.pattern',
            ])
            ->latest()
            ->paginate($perPage, page: $page)
            ->withQueryString();

        $transactions = collect($paginated->items())->map(function ($tx) {

            $address = $tx->user?->addresses?->first();
            $fullAddress = $address ? implode(' / ', [
                $address->province->name,
                $address->city->name,
                $address->full_address,
                $address->zipcode
            ]) : null;

            $items = $tx->items->map(function ($item) {
                return [
                    'product_name' => $item->product_name ?? '---',
                    'product_code' => $item->sku ?? '---',
                    'pattern_name' => $item->pattern_name ?? '---',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ];
            });

            return (object) [
                'id' => $tx->id,
                'amount' => $tx->amount,
                'method' => $tx->method,
                'gateway' => $tx->gateway,
                'status' => $tx->status,
                'refId' => $tx->ref_id,
                'chequeImage' => $tx->cheque_image,
                'shippingStatus' => $tx->shipping_status,
                'createdAt' => $tx->created_at,
                'address' => $fullAddress,
                'adminId' => $tx->admin_id,
                'code' => $tx->code,

                'user' => $tx->user ? (object) [
                    'id' => $tx->user->id,
                    'name' => trim($tx->user->name . ' ' . $tx->user->family),
                    'username' => $tx->user->username,
                ] : null,

                'admin' => $tx->admin ? (object) [
                    'id' => $tx->admin->id,
                    'name' => trim($tx->admin->name . ' ' . $tx->admin->family),
                ] : null,

                'items' => $items,
            ];
        })->toArray();

        return [
            'data' => $transactions,
            'pagination' => (object) [
                'currentPage' => $paginated->currentPage(),
                'lastPage' => $paginated->lastPage(),
                'perPage' => $paginated->perPage(),
                'total' => $paginated->total(),
                'next_page_url' => $paginated->nextPageUrl(),
                'prev_page_url' => $paginated->previousPageUrl(),
            ]
        ];
    }

    public function getTransactionDetails(int $transactionId)
    {
        $tx = Transaction::with([
            'user:id,name,family,username',
            'admin:id,name,family,username',
            'items.productVariant.pattern',
        ])->findOrFail($transactionId);

        $totalQuantity = $tx->items->sum('quantity');

        return (object) [
            'id' => $tx->id,
            'amount' => $tx->amount,
            'method' => $tx->method,
            'status' => $tx->status,
            'shipping_status' => $tx->shipping_status,
            'note' => $tx->note,
            'createdAt' => $tx->created_at->toDateTimeString(),
            'address' => $tx->address,
            'chequeImage' => $tx->cheque_image,
            'adminId' => $tx->admin_id,
            'code' => $tx->code,
            'refId' => $tx->ref_id,
            'gateway' => $tx->gateway,

            'total_quantity' => $totalQuantity,

            'user' => (object) [
                'id' => $tx->user->id,
                'name' => trim($tx->user->name . ' ' . $tx->user->family),
                'username' => $tx->user->username,
            ],

            'items' => $tx->items->map(fn($item) => [
                'product_name' => $item->product_name,
                'product_code' => $item->sku,
                'pattern_name' => $item->pattern_name,
                'unit_price' => $item->unit_price,
                'quantity' => $item->quantity,
                'total_price' => $item->total_price,
                'description' => $tx->note,
            ]),
        ];
    }

    public function getCurrentCustomerTransactions()
    {
        $transactions = auth()->user()?->transactions()->with([
            'items.productVariant',
            'items.productVariant.pattern',
        ])->latest()->get() ?? collect();

        return $transactions;
    }

    public function updateShippingStatus(int $id, bool $status)
    {
        Transaction::where('id', '=', $id)->update(
            [
                'shipping_status' => $status,
            ]
        );
    }

    public function submitShippingStatus(int $id)
    {
        $tx = Transaction::with('user')->find($id);

        if ($tx->shipping_status === false) {
            $tx->update([
                'shipping_status' => true
            ]);

            app(SmsProviderService::class)->orderSentCustomer($tx->user->username);
        }
    }

    public function getUserTransactions(int $userId): Collection
    {
        $transactions = Transaction::where('user_id', $userId)
            ->with(['items.productVariant.pattern'])
            ->latest()
            ->get();

        return $transactions;
    }
}
