<?php

namespace App\Livewire\Components\Manager\Colors;

use App\Http\Dto\CreateColorDto;
use App\Livewire\Forms\CreateColorForm;
use Livewire\Component;

class AddColor extends Component
{
    public CreateColorForm $form;

    public function store()
    {
        $validated = $this->form->validate();
        $colorService = app('ColorService');
        $dto = CreateColorDto::makeDto($validated);
        $colorService->store($dto);
        $this->form->reset();
        $this->dispatch('get-colors');
        $this->dispatch('success', message: "رنگ با موفقیت ذخیره شد.");
    }

    public function render()
    {
        return view("components.manager.colors.add-color");
    }
}
