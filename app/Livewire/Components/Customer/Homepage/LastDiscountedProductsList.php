<?php

namespace App\Livewire\Components\Customer\Homepage;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\ProductService;

#[Layout('layouts.customer')]
class LastDiscountedProductsList extends Component
{
    public ?array $products;

    public function mount()
    {
        $this->lastDiscount();
    }

    public function lastDiscount()
    {
        $productService = app(ProductService::class);
        $this->products = $productService->getLastDiscountedProductsCustomer();
    }

    public function render()
    {
        return view('components.customer.homepage.last-discounted-products-list');
    }
}
