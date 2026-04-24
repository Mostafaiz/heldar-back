<?php

namespace App\Livewire\Pages\Manager;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class AttributesIndex extends Component
{
    public string $pageTitle = 'ویژگی‌های محصول';
    public string $routeName = 'manager.attributes.index';
    public Collection $attributeGroups;

    #[On('get-all-attributes')]
    public function getAllAttributes(): void
    {
        $attributeService = app('AttributeService');
        $this->attributeGroups = $attributeService->all();
    }

    public function mount(): void
    {
        $this->getAllAttributes();
    }

    public function render(): View
    {
        return view('pages.manager.attributes-index');
    }
}
