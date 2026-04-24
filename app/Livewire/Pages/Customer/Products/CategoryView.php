<?php

namespace App\Livewire\Pages\Customer\Products;

use App\Exceptions\Category\CategoryException;
use App\Http\Dto\Request\Category\GetCategory;
use Livewire\Attributes\Layout;
use App\Services\CategoryService;
use Livewire\Component;

#[Layout('layouts.customer')]
class CategoryView extends Component
{
    public ?array $categories;
    public ?int $categoryId = null;

    public function mount(?int $id)
    {
        $this->categoryId = $id;
        $this->loadCategories($id);
    }

    public function loadCategories(?int $id)
    {
        $categoryService = app(CategoryService::class);
        $dto = GetCategory::makeDto(['id' => $id]);

        try {
            $this->categories = $categoryService->getSubCategoriesCustomer($dto);
        } catch (CategoryException $exception) {
            abort(404);
        }
    }

    public function render()
    {
        return view('pages.customer.products.category-view');
    }
}
