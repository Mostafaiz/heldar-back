<?php

namespace App\Livewire\Components\Manager\Categories;

use Livewire\Attributes\On;
use Livewire\Component;

class DeleteConfirm extends Component
{
	public bool $showBox = false;
	public int $categoryId = 0;

	#[On('delete-category-confirm')]
	public function show(int $id): void {
		$this->showBox = true;
		$this->categoryId = $id;
	}

	public function delete() {
		$this->dispatch('delete-category', id: $this->categoryId);
		$this->showBox = false;
		$this->categoryId = 0;
	}

	public function cancel() {
		$this->showBox = false;
		$this->categoryId = 0;
	}

    public function render()
    {
		return view('components.manager.categories.delete-confirm');
	}
}
