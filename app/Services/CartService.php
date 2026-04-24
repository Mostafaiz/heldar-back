<?php

namespace App\Services;

use App\Exceptions\Product\ProductException;
use App\Http\Dto\Request\Customer\Cart\GetCartItem as GetCartItemDto;
use App\Http\Dto\Request\Customer\Cart\UpdateCartItem as UpdateCartItemDto;
use App\Http\Dto\Response\Customer\Cart\Cart as CartResponseDto;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function addItem(UpdateCartItemDto $dto)
    {
        $cart = $this->getActiveCart();
        if (!$cart) {
            throw new \RuntimeException('No active cart found for the user.');
        }

        $variant = ProductVariant::query()
            ->where('product_id', $dto->productId)
            ->where('pattern_id', $dto->patternId)
            ->when($dto->sizeId, fn($q) => $q->where('size_id', $dto->sizeId))
            ->first();

        if (!$variant) {
            throw new \RuntimeException('Requested product variant not found.');
        }

        try {
            return DB::transaction(function () use ($cart, $variant, $dto) {
                $existing = $cart->items()
                    ->where('product_variant_id', $variant->id)
                    ->where('guarantee_id', $dto->guaranteeId)
                    ->where('insurance_id', $dto->insuranceId)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    $existing->quantity += $dto->quantity;
                    if (!$existing->save()) {
                        throw new \RuntimeException("Failed to update cart item.");
                    }
                    return $existing;
                }

                $newItem = $cart->items()->create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $dto->quantity,
                    'guarantee_id' => $dto->guaranteeId,
                    'insurance_id' => $dto->insuranceId,
                ]);

                if (!$newItem) {
                    throw new \RuntimeException("Failed to create new cart item.");
                }

                return $newItem;
            });
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                "Error adding item to cart: " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    public function getActiveCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $cartId = Session::get('cart_id');
        if ($cartId && $cart = Cart::find($cartId)) {
            return $cart;
        }

        $existing = Cart::where('session_id', Session::getId())->first();
        if ($existing) {
            Session::put('cart_id', $existing->id);

            return $existing;
        }

        $cart = Cart::create(['session_id' => Session::getId()]);
        Session::put('cart_id', $cart->id);

        return $cart;
    }

    public function getCartItems()
    {
        $cart = $this->getActiveCart();
        $items = $cart->items()
            ->with(['variant.product', 'variant.pattern.colors', 'variant.size', 'guarantee', 'insurance'])
            ->get();

        return [$items->map(fn($item) => CartResponseDto::from($item))->all(), $cart];
    }

    public function checkAndGetCartItems()
    {
        $cart = $this->getActiveCart();

        $items = $cart->items()
            ->with([
                'variant.product',
                'variant.pattern.colors',
                'variant.size',
                'guarantee',
                'insurance'
            ])
            ->oldest()
            ->get();

        $hasChanges = false;

        foreach ($items as $item) {
            $availableQty = (int) $item->variant->quantity;
            $cartQty = (int) $item->quantity;

            if ($availableQty <= 0) {
                $item->delete();
                $hasChanges = true;
                continue;
            }

            if ($cartQty > $availableQty) {
                $item->quantity = $availableQty;
                $item->save();
                $hasChanges = true;
                continue;
            }

            if ($cartQty < 1) {
                $item->quantity = 1;
                $item->save();
                $hasChanges = true;
            }
        }

        if ($hasChanges) {
            $items = $cart->items()
                ->with([
                    'variant.product',
                    'variant.pattern.colors',
                    'variant.size',
                    'guarantee',
                    'insurance'
                ])
                ->oldest()
                ->get();

            session()->put('cart_alert', true);
        }

        return [
            $items->map(fn($item) => CartResponseDto::from($item))->all(),
            $cart
        ];
    }

    public function getCartItemById(GetCartItemDto $dto)
    {
        $cart = $this->getActiveCart();

        $variant = ProductVariant::where('product_id', $dto->productId)
            ->where('pattern_id', $dto->patternId)
            ->where('size_id', $dto->sizeId)
            ->first('id');

        if (!isset($variant))
            ProductException::VariantNotFound();

        try {
            return CartResponseDto::from(
                $cart->items()
                    ->where('product_variant_id', $variant->id)
                    ->where('guarantee_id', $dto->guaranteeId)
                    ->where('insurance_id', $dto->insuranceId)
                    ->first()
            );
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getCartItemCount(): int
    {
        $cart = $this->getActiveCart();
        return $cart->items()->pluck('quantity')->sum();
    }

    public function mergeGuestCartIntoUserCart(): void
    {
        if (!Auth::check()) {
            return;
        }

        $guestCartId = Session::pull('cart_id');
        if (!$guestCartId) {
            return;
        }

        $guestCart = Cart::find($guestCartId);
        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        DB::transaction(function () use ($guestCart, $userCart) {
            foreach ($guestCart->items as $item) {
                $existing = $userCart->items()
                    ->where('product_variant_id', $item->product_variant_id)
                    ->where('guarantee_id', $item->guarantee_id)
                    ->where('insurance_id', $item->insurance_id)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    $existing->quantity = max($item->quantity, $existing->quantity);
                    $existing->save();
                } else {
                    $userCart->items()->create([
                        'product_variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity,
                        'guarantee_id' => $item->guarantee_id,
                        'insurance_id' => $item->insurance_id,
                    ]);
                }
            }
            $guestCart->delete();
        });
    }

    public function persistUserCartToSession(): void
    {
        if (!Auth::check()) {
            return;
        }

        $userCart = Cart::where('user_id', Auth::id())->first();
        if (!$userCart) {
            return;
        }

        $guestCart = Cart::firstOrCreate(['session_id' => Session::getId()]);
        foreach ($userCart->items as $item) {
            $guestCart->items()->create([
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'guarantee_id' => $item->guarantee_id,
                'insurance_id' => $item->insurance_id,
            ]);
        }
        Session::put('cart_id', $guestCart->id);
    }

    public function increaseItem(int $id)
    {
        $cartItem = CartItem::with('variant', 'cart')->find($id);

        if ($cartItem->cart->locked)
            throw new Exception('Cart is locked!', 555);

        if ($cartItem->quantity >= $cartItem->variant->quantity)
            return false;

        $cartItem->quantity++;
        $cartItem->save();
        return true;
    }

    public function decreaseItem(int $id)
    {
        $cartItem = CartItem::with('cart')->find($id);

        if ($cartItem->cart->locked)
            throw new Exception('Cart is locked!', 555);

        if ($cartItem->quantity > 1) {
            $cartItem->quantity--;
            $cartItem->save();
        }
    }

    public function removeItem(UpdateCartItemDto $dto)
    {
        $cart = $this->getActiveCart();

        $productVariant = ProductVariant::where('product_id', $dto->productId)
            ->where('pattern_id', $dto->patternId)
            ->where('size_id', $dto->sizeId)
            ->first();

        if (!isset($productVariant))
            ProductException::VariantNotFound();

        $cart->items()->where('product_variant_id', $productVariant->id)->delete();
    }

    public function removeItemById(int $id)
    {
        $cartItem = CartItem::with('cart')->find($id);

        if ($cartItem->cart->locked)
            throw new Exception('Cart is locked!', 555);

        $cartItem->delete();
    }

    public function updateCartItem(UpdateCartItemDto $dto, bool $increase = true)
    {
        $cart = $this->getActiveCart();

        $productVariant = ProductVariant::where('product_id', $dto->productId)
            ->where('pattern_id', $dto->patternId)
            ->where('size_id', $dto->sizeId)
            ->first();

        if (!isset($productVariant))
            ProductException::VariantNotFound();

        $cartItem = $cart->items()->where('product_variant_id', $productVariant->id)->select(['quantity'])?->get()->first();

        if (isset($cartItem))
            if ($increase && $cartItem->quantity >= $productVariant->quantity) return;

        CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_variant_id' => $productVariant->id,
                'guarantee_id' => $dto->guaranteeId,
                'insurance_id' => $dto->insuranceId,
            ],
            [
                'quantity' => ($increase)
                    ? DB::raw('COALESCE(quantity, 0) + 1')
                    : DB::raw('quantity - 1')
            ]
        );
    }

    public function updateCartItems(array $orders)
    {
        $cart = $this->getActiveCart();

        if ($cart->locked) {
            throw new Exception('Cart is locked!', 555);
        }

        $variantIds = array_column($orders, 'product_variant_id');
        if (empty($variantIds)) {
            return;
        }

        foreach ($orders as $order) {
            $cartItem = $cart->items()
                ->where('product_variant_id', $order['product_variant_id'])
                ->first();

            if ($cartItem) {
                if ($order['quantity'] > 0) {
                    // Update existing item if quantity is greater than zero
                    if ($cartItem->quantity != $order['quantity']) {
                        $cartItem->quantity = $order['quantity'];
                        $cartItem->save();
                    }
                } else {
                    // Delete the cart item if quantity is zero
                    $cartItem->delete();
                }
            } else {
                if ($order['quantity'] > 0) {
                    // Insert new item if quantity is greater than zero
                    CartItem::create([
                        'cart_id'            => $cart->id,
                        'product_variant_id' => $order['product_variant_id'],
                        'quantity'           => $order['quantity'],
                        'guarantee_id'       => null,
                        'insurance_id'       => null,
                    ]);
                }
            }
        }
    }

    public function clearCart(): void
    {
        $cart = $this->getActiveCart();

        if ($cart->locked)
            throw new Exception('Cart is locked!', 555);

        $cart->items()->delete();
    }
}
