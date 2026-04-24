<?php

namespace App\Livewire\Components\Customer;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class MiniCart extends Component
{
    public array $products = [];
    public int $totalPrice = 0;

    public function mount(): void
    {
        $this->loadUserCart();
        $this->setTotalPrice();
    }

    #[On('update-mini-cart')]
    public function loadUserCartFromSinglePage()
    {
        $this->products = app(CartService::class)->checkAndGetCartItems()[0];
        $this->setTotalPrice();
    }

    public function loadUserCart()
    {
        $this->products = app(CartService::class)->checkAndGetCartItems()[0];
        $this->setTotalPrice();
        $this->dispatch('load-user-cart');
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

    public function setTotalPrice(): void
    {
        $this->totalPrice = array_reduce($this->products, function ($carry, $product) {
            return $carry + (($product->price - $product->discount) * $product->quantity);
        }, 0);
    }

    public function render()
    {
        return view('components.customer.mini-cart');
    }
}
