<?php

namespace App\Livewire\Pages\Customer\Products;

use App\Http\Dto\Request\Category\GetCategory;
use App\Services\CategoryService;
use App\Services\ColorService;
use Livewire\Attributes\Layout;
use App\Services\ProductService;
use App\Services\SizeService;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.customer')]
class ProductList extends Component
{
    public array $products;
    public array $categories;
    public array $brands;
    public array $colors;
    public array $sizes;
    public int $minPrice = 0;
    public int $maxPrice = 0;
    public array $filters = [
        'category' => [],
        'brand' => [],
        'color' => "",
        'size' => [],
        'discount' => false,
        'quantity' => false,
    ];
    public bool $clearFilterButton = false;
    public int $totalFilters = 0;
    public int $paginateNumber = 15;
    public ?string $search = null;
    public bool $hasMorePages = true;
    public string $orderBy = 'default';

    public function mount(mixed $id = null)
    {
        if (!is_null($id) && !ctype_digit((string) $id)) {
            abort(404);
        }

        $this->search = session('search', '');
        if (isset($id)) {
            array_push($this->filters['category'], $id);
        }

        $this->loadAllProducts();
        $this->getPrices();
        $this->setTotalFilters();
        $this->loadFilters();
    }

    public function loadAllProducts()
    {
        $productService = app(ProductService::class);
        [$this->products, $this->hasMorePages] = $productService->filterProducts($this->filters, $this->search, $this->paginateNumber, $this->orderBy);
    }

    public function changeOrder(string $order)
    {
        $this->paginateNumber = 15;
        $this->orderBy = $order;
        $this->loadAllProducts();
    }

    #[On('load-category-products')]
    public function loadCategoryProducts(int $id)
    {
        $productService = app(ProductService::class);
        $dto = GetCategory::makeDto(['id' => $id]);
        $this->products = $productService->getCategoryProductsCustomer($dto);
    }

    private function loadFilters()
    {
        $this->brands = app(ProductService::class)->getAllBrands();
        $this->colors = app(ColorService::class)->getAllCustomerIfHasProducts();
        $this->sizes = app(SizeService::class)->getAllCustomer();
        $this->categories = app(CategoryService::class)->getNestedCategoriesCustomer();
    }

    public function loadMore()
    {
        $this->paginateNumber += 15;
        $this->loadAllProducts();
    }

    public function filterBrand(string $brand, bool $status)
    {
        if ($status)
            $this->filters['brand'][] = $brand;
        else
            unset($this->filters['brand'][array_search($brand, $this->filters)]);

        $this->loadAllProducts();
    }

    public function removeSearch()
    {
        $this->search = null;
        session()->forget('search');
        $this->loadAllProducts();
    }

    public function updatedFilters()
    {
        $this->loadAllProducts();
        $this->setTotalFilters();

        if (
            !empty($this->filters['category']) ||
            !empty($this->filters['brand']) ||
            !empty($this->filters['color']) ||
            !empty($this->filters['size']) ||
            $this->filters['discount'] ||
            $this->filters['quantity'] ||
            $this->filters['minPrice'] != $this->minPrice ||
            $this->filters['maxPrice'] != $this->maxPrice
        )
            $this->clearFilterButton = true;
        else
            $this->clearFilterButton = false;
    }

    public function resetFilters(): void
    {
        $this->reset('filters', 'totalFilters');
        $this->loadAllProducts();
        $this->getPrices();
        $this->clearFilterButton = false;
    }

    public function getPrices(): void
    {
        $productService = app(ProductService::class);
        $this->minPrice = $productService->getMinPrice() ?? 0;
        $this->filters['minPrice'] = $this->minPrice;
        $this->maxPrice = $productService->getMaxPrice() ?? 0;
        $this->filters['maxPrice'] = $this->maxPrice;
    }

    public function setTotalFilters(): void
    {
        $this->totalFilters = count(
            array_filter(
                [
                    !empty($this->filters['category']),
                    !empty($this->filters['brand']),
                    !empty($this->filters['color']),
                    !empty($this->filters['size']),
                    $this->filters['discount'],
                    $this->filters['quantity'],
                    $this->filters['minPrice'] != $this->minPrice,
                    $this->filters['maxPrice'] != $this->maxPrice
                ]
            )
        );
    }

    public function dehydrate()
    {
        session()->forget('search');
    }

    public function render()
    {
        return view('pages.customer.products.product-list');
    }
}
