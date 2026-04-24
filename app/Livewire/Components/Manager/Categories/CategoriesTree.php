<?php

namespace App\Livewire\Components\Manager\Categories;

use App\Http\Dto\Category\DeleteCategoryDto;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoriesTree extends Component
{
    public Category $category;
    public float $depth = 0.7;
    public bool $isChild = false;

	#[On('delete-category')]
    public function delete($categ)
    {
        $dto = DeleteCategoryDto::makeDto($this->category);
        $categoryService = app('CategoryService');
        $categoryService->deleteCategory($dto);
		$this->dispatch('get-categories', id: $this->category->id);
	}

	public function mount($category)
	{
		$this->category = $category;
		$this->isChild = $this->category->categories_id !== null;
	}

    public function render()
    {
        $this->category->load('children');

        return view('components.manager.categories.categories-tree');
    }
}
