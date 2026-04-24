<?php

namespace App\Livewire\Pages\Customer;

use App\Services\CategoryService;
use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class HomePage extends Component
{
    // public $products;
    public array $categories;

    public function mount()
    {
        $this->categories = app(CategoryService::class)->getAllParentCategoriesCustomer();
        // $this->products = app(ProductService::class)->getLastProductsCustomer(6);
    }

    public function render()
    {
        return view('pages.customer.home-page');
    }
}
