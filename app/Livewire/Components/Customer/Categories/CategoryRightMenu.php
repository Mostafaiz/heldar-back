<?php

namespace App\Livewire\Components\Customer\Categories;

use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CategoryRightMenu extends Component
{
    public Collection $categories;

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = app(CategoryService::class)->getAllCategoriesCustomer();
    }

    public function render()
    {
        return view('components.customer.categories.category-right-menu');
    }
}
