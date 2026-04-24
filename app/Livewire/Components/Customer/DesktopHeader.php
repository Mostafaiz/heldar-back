<?php

namespace App\Livewire\Components\Customer;

use App\Services\CartService;
use App\Services\CategoryService;
use Livewire\Attributes\On;
use Livewire\Component;

class DesktopHeader extends Component
{
    public int $itemCount;
    public $categories;
    public string $searchText = '';

    public function mount()
    {
        $this->getCartItemCount();
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = app(CategoryService::class)->getAllCategoriesCustomer();
    }

    public function logout()
    {
        $authService = app('AuthService');
        $authService->logout();
        $this->redirect(route('login'));
    }

    #[On(['update-desktop-header', 'update-mini-cart'])]
    public function getCartItemCount()
    {
        $cartService = app(CartService::class);
        $this->itemCount = $cartService->getCartItemCount();
    }

    #[On('search')]
    public function dispatchSearch(?string $text = '')
    {
        session()->put('search', $this->searchText);
        $this->redirect('/products', true);
    }

    public function search()
    {
        session()->put('search', $this->searchText);
        $this->redirect('/products', true);
    }

    public function render()
    {
        return view('components.customer.desktop-header');
    }
}
