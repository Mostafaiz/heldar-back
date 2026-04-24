<?php

namespace App\Livewire\Pages\Manager;

use App\Http\Dto\Category\DeleteCategoryDto;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Reactive;

#[Layout('layouts.manager')]
class CategoriesIndex extends Component
{
    public string $pageTitle = 'دسته‌بندی‌ها';
    public string $routeName = 'manager.categories.index';
    public Collection $categories;

    public function mount()
    {
        $this->getCategories();
    }

    #[On('get-categories')]
    public function getCategories()
    {
        $categoryService = app('CategoryService');
        $this->categories = $categoryService->getNestedCategories();
        logger("get!");
    }

    public function deleteCategory(int $id)
    {
        $categoryService = app('CategoryService');
        $category = Category::findOrFail($id);
        $dto = DeleteCategoryDto::makeDto($category);
        $categoryService->deleteCategory($dto);
        logger("delete!");
        $this->getCategories();
        $this->dispatch('success', message: 'دسته‌بندی با موفقیت حذف شد.');
    }

    public function render()
    {
        $this->getCategories();
        return view('pages.manager.categories-index');
    }
}
