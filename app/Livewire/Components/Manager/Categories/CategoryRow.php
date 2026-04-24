<?php

namespace App\Livewire\Components\Manager\Categories;

use App\Models\Category;
use Livewire\Component;

class CategoryRow extends Component
{
    public Category $category;

	public function select(): void {
		$this->dispatch('category-select', id: $this->category->id, name: $this->category->name);
	}

    public function render()
    {
		return view('components.manager.categories.category-row');
	}
}
