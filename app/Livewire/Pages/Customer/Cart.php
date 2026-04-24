<?php

namespace App\Livewire\Pages\Customer;

use App\Http\Dto\Request\Customer\Cart\UpdateCartItem as UpdateCartItemDto;
use App\Services\CartService;
use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.customer')]
class Cart extends Component
{
    public int $totalPrice = 0;
    public array $products = [];
    public int $itemCount;
    public bool $canBuy = true;
    public ?string $currentUserLevel = null;

    public function mount(): void
    {
        try {
            $this->setCurrentUserLevel();
            $this->loadUserCart();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در بارگذاری اطلاعات!');
        }
    }

    public function hydrate()
    {
        $this->checkCartChange();
    }

    public function checkCartChange()
    {
        if (session('cart_alert')) {
            $this->dispatch('notify', type: 'warning', message: 'سبد خرید شما تغییر کرده است!');
            session()->forget('cart_alert');
        }
    }

    #[On('load-user-cart')]
    public function loadUserCartFromMiniCart()
    {
        $this->products = app(CartService::class)->checkAndGetCartItems()[0];
        $this->checkCartChange();
        $this->setTotalPrice();
    }

    public function setItemCount()
    {
        $this->itemCount = app(CartService::class)->getCartItemCount();
    }

    public function setCanBuy()
    {
        if ($this->itemCount < 10 && ($this->currentUserLevel == 'silver' || $this->currentUserLevel == 'gold'))
            $this->canBuy = false;
        else
            $this->canBuy = true;
    }

    public function setCurrentUserLevel()
    {
        $this->currentUserLevel = app(UserService::class)->getCurrentUserLevel();
    }

    public function loadUserCart()
    {
        $this->products = app(CartService::class)->checkAndGetCartItems()[0];
        $this->checkCartChange();
        $this->setTotalPrice();
        $this->setItemCount();
        $this->setCanBuy();
        $this->dispatch('update-mini-cart');
        $this->dispatch('update-desktop-header');
    }

    public function increaseItem(int $id)
    {
        try {
            $max = app(CartService::class)->increaseItem($id);
            $this->loadUserCart();
            return $max;
        } catch (\Throwable $th) {
            if ($th->getCode() == 555)
                $this->dispatch('notify', type: 'warning', message: 'سبد شما موقتا غیرفعال می‌باشد!');
            else
                $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function decreaseItem(int $id)
    {
        try {
            app(CartService::class)->decreaseItem($id);
            $this->loadUserCart();
        } catch (\Throwable $th) {
            if ($th->getCode() == 555)
                $this->dispatch('notify', type: 'warning', message: 'سبد شما موقتا غیرفعال می‌باشد!');
            else
                $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function removeItem(int $id): void
    {
        try {
            app(CartService::class)->removeItemById($id);
            $this->loadUserCart();
        } catch (\Throwable $th) {
            if ($th->getCode() == 555)
                $this->dispatch('notify', type: 'warning', message: 'سبد شما موقتا غیرفعال می‌باشد!');
            else
                $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function removeAllItems()
    {
        try {
            app(CartService::class)->clearCart();
            $this->loadUserCart();
        } catch (\Throwable $th) {
            if ($th->getCode() == 555)
                $this->dispatch('notify', type: 'error', message: 'سبد شما موقتا غیرفعال می‌باشد!');
            else
                $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function setTotalPrice(): void
    {
        $this->totalPrice = array_reduce($this->products, function ($carry, $product) {
            return $carry + (($product->price - $product->discount) * $product->quantity);
        }, 0);
    }

    public function render()
    {
        return view('pages.customer.cart');
    }
}
