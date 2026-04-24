<?php

namespace App\Livewire\Components\Customer\Homepage;

use Livewire\Component;
use App\Services\ProductService;

class LastProductsList extends Component
{
    public ?array $products;

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $productService = app(ProductService::class);
        $this->products = $productService->getLastProductsCustomer();
    }

    public function render()
    {
        return view('components.customer.homepage.last-products-list');
    }
}
