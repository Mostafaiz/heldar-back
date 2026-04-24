<?php

namespace App\Livewire\Pages\Customer;

use App\Enums\Payment\PaymentMethodEnum;
use App\Models\User;
use App\Services\CartService;
use App\Services\PaymentCardService;
use App\Services\ProductService;
use App\Services\ShippingService;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer')]
class CheckoutPage extends Component
{
    use WithFileUploads;

    public array $cartItems;
    public $cart;
    public ?int $selectedAddressId = null;
    public ?int $selectedAddressIdForManager = null;
    public ?int $selectedShippingId = null;
    public string $paymentMethod;
    public array $addresses;
    public array $shippings;
    public int $totalPrice;
    public int $totalDiscount;
    public $chequeImage = null;
    public bool $uploading = false;
    public int $progress = 0;
    public bool $isManager;
    public $users;
    public ?int $selectedManagerUserId = null;
    public ?string $userSearch = null;
    public string $currentUserLevel;
    public User $currentUser;
    public $image = null;

    public function mount()
    {
        $userService = app(UserService::class);
        $this->paymentMethod = PaymentMethodEnum::GATEWAY->value;

        if (!$userService->isLoggedIn()) return redirect()->route('login');
        $this->currentUser = $userService->getCurrentUser();

        $this->isManager = $this->currentUser->role->value === 'manager';

        $this->currentUserLevel = $this->currentUser->level;

        $this->loadUserCartItems();
        if (!count($this->cartItems)) abort(403);

        $this->loadAddresses();
        $this->loadPaymentCards();
        $this->loadShippings();
    }

    public function loadUserCartItems(): bool
    {
        [$this->cartItems, $this->cart] = app(CartService::class)->checkAndGetCartItems();
        if (!count($this->cartItems)) abort(403);
        $this->setTotalPrice();

        if (session('cart_alert')) {
            $this->dispatch('notify', type: 'warning', message: 'سبد خرید شما تغییر کرده است!', url: '/cart');
            session()->forget('cart_alert');
            return false;
        }

        return true;
    }

    public function getPaymentCardsProperty()
    {
        return app(PaymentCardService::class)->getAllCustomer();
    }

    public function setTotalPrice(): void
    {
        $this->totalPrice = array_reduce($this->cartItems, function ($carry, $product) {
            return $carry + (($product->price - $product->discount) * $product->quantity);
        }, 0);
        $this->totalDiscount = array_reduce($this->cartItems, function ($carry, $product) {
            return $carry + ($product->discount * $product->quantity);
        }, 0);
    }

    public function loadAddresses()
    {
        $this->addresses = app(UserService::class)->getCurrentUserAddresses();
        if (count($this->addresses)) $this->selectedAddressId = 0;
    }

    public function loadShippings(): void
    {
        $this->shippings = app(ShippingService::class)->getAllShippingsCustomer();
        if (count($this->shippings)) $this->selectedShippingId = 1;
    }

    public function loadUsers()
    {
        $this->users = app(UserService::class)->getAllUsersWithAddressesBySearch($this->userSearch);
    }

    public function loadPaymentCards()
    {
        // $this->paymentCards = app(PaymentCardService::class)->getAllCustomer();
    }

    public function updatedPaymentMethod($value)
    {
        if ($value == PaymentMethodEnum::MANAGER->value) {
            $this->loadUsers();
        } else if ($value == PaymentMethodEnum::CHEQUE->value) {
            $this->loadPaymentCards();
        } else {
            $this->reset('selectedAddressIdForManager', 'selectedManagerUserId');
        }
    }

    public function updatedUserSearch()
    {
        $this->loadUsers();
    }

    public function payGateway()
    {
        try {
            if ($this->selectedAddressId === null) {
                $this->dispatch('notify', type: 'error', message: 'هیچ آدرسی انتخاب نشده!');
                return;
            }

            $price = $this->totalPrice + ($this->selectedShippingId != null ? $this->shippings[$this->selectedShippingId - 1]->price : 0);
            $result = app(TransactionService::class)->createOnlineTransaction($this->cart, $this->addresses[$this->selectedAddressId]->id ?? null, $price);

            if (! $result['success']) {
                $this->addError('payment', $result['message']);
                return;
            }

            return redirect()->away($result['payment_url']);
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function payCheque()
    {
        if ($this->selectedAddressId === null) {
            $this->dispatch('notify', type: 'error', message: 'هیچ آدرسی انتخاب نشده!');
            return;
        }

        $address = $this->addresses[$this->selectedAddressId]->fullAddress ?? null;
        $amount = $this->totalPrice + ($this->selectedShippingId != null ? $this->shippings[$this->selectedShippingId - 1]->price : 0);
        $data = [
            'address' => $address,
            'amount' => $amount,
            'cart' => $this->cart,
            'image' => $this->image ?? null,
        ];
        try {
            app(TransactionService::class)->createOfflineTransaction($data);
            if ($this->image) {
                return redirect()->route('customer.payment.success');
            } else {
                return redirect()->route('customer.payment.success', ['message' => 'لطفا فیش واریزی را بارگذاری کنید.', 'timer' => 6]);
            }
        } catch (\Throwable $th) {
            $this->redirect('payment/failed');
        }
    }

    public function payManager()
    {
        try {
            $price = $this->totalPrice + ($this->selectedShippingId != null ? $this->shippings[$this->selectedShippingId - 1]->price : 0);
            app(TransactionService::class)->createTransactionByAdmin($this->cart, $this->selectedManagerUserId, $this->selectedAddressIdForManager, $price);
            $this->redirect('payment/success');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
            $this->redirect('payment/failed');
        }
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function submit()
    {
        if (!$this->loadUserCartItems())
            return;

        if ($this->currentUser->name == null || $this->currentUser->family == null) {
            $this->dispatch('notify', type: 'warning', message: 'لطفا ثبت‌نام خود را تکمیل کنید.', position: 'center', buttonText: 'ورود', url: route('customer.profile'), localStorageKey: 'openProfile', timer: 'infinite');
            return;
        }

        switch ($this->paymentMethod) {
            case PaymentMethodEnum::CHEQUE->value:
                $this->payCheque();
                break;

            case PaymentMethodEnum::GATEWAY->value:
                $this->payGateway();
                break;

            case PaymentMethodEnum::MANAGER->value:
                $this->payManager();
                break;
        }
    }

    public function render()
    {
        return view('pages.customer.checkout-page', [
            'paymentCards' => $this->paymentCards
        ]);
    }
}
