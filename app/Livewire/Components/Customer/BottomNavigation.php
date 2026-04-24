<?php

namespace App\Livewire\Components\Customer;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class BottomNavigation extends Component
{
    public int $itemCount;

    public function mount()
    {
        $this->loadCartItemCount();
    }

    #[On('search')]
    public function search(?string $text = '')
    {
        session()->put('search', $this->searchText);
        $this->redirect('/products', true);
    }

    #[On('update-mini-cart')]
    public function loadCartItemCount()
    {
        $cartService = app(CartService::class);
        $this->itemCount = $cartService->getCartItemCount();
    }

    public function render()
    {
        return view('components.customer.bottom-navigation');
    }
}
