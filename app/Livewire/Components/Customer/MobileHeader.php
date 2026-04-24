<?php

namespace App\Livewire\Components\Customer;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class MobileHeader extends Component
{
    public string $searchText = '';
    public int $cartItemCount = 0;

    public function mount()
    {
        $this->loadCartItemCount();
    }

    public function search(?string $text = '')
    {
        session()->put('search', $this->searchText);
        $this->redirect('/products', true);
    }

    #[On('update-mini-cart')]
    public function loadCartItemCount()
    {
        $cartService = app(CartService::class);
        $this->cartItemCount = $cartService->getCartItemCount();
    }

    public function render()
    {
        return view('components.customer.mobile-header');
    }
}
