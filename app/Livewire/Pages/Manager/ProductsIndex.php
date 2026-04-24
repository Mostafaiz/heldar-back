<?php

namespace App\Livewire\Pages\Manager;

use App\Livewire\Forms\SearchProductsForm;
use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.manager')]
class ProductsIndex extends Component
{
    public string $pageTitle = 'همه محصولات';
    public string $routeName = 'manager.products.index';
    public $products = [];
    public SearchProductsForm $searchForm;
    #[Url]
    public int $page = 1;
    public $paginator = null;
    public bool $isSearchMode = false;


    public function mount()
    {
        $this->loadAllProducts();
    }

    public function nextPage()
    {
        $this->page++;
        $this->isSearchMode ? $this->searchProducts() : $this->loadAllProducts();
    }

    public function previousPage()
    {
        $this->page--;
        $this->isSearchMode ? $this->searchProducts() : $this->loadAllProducts();
    }

    public function goToPage(int $page)
    {
        $this->page = $page;
        $this->isSearchMode ? $this->searchProducts() : $this->loadAllProducts();
    }

    public function loadAllProducts()
    {
        $paginate = app(ProductService::class)->getAllProducts($this->page);
        $this->products = $paginate->data ?? null;
        $this->paginator = $paginate->meta ?? null;
    }

    public function updatedSearchFormKeyword()
    {
        $this->searchProducts();
    }

    public function searchProducts()
    {
        $keyword = trim($this->searchForm->keyword);

        if (mb_strlen($keyword) < 2) {
            $this->page = 1;
            $this->loadAllProducts();
        }

        $this->searchForm->validate();


        try {
            $this->isSearchMode = true;
            $paginate = app(ProductService::class)->getProductsBySearch($keyword, $this->page);
            $this->products = $paginate->data ?? [];
            $this->paginator = $paginate->meta ?? null;
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در دریافت محصولات!');
        }
    }

    public function render()
    {
        return view('pages.manager.products-index');
    }
}
