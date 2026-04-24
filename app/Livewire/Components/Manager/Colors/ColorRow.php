<?php

namespace App\Livewire\Components\Manager\Colors;

use App\Http\Dto\DeleteColorDto;
use App\Http\Dto\UpdateColorDto;
use App\Livewire\Forms\UpdateColorForm;
use App\Models\Color;
use Livewire\Component;

class ColorRow extends Component
{
    public UpdateColorForm $form;

    public function mount(Color $color)
    {
        $this->form->setColor($color);
    }

    public function resetData()
    {
        $this->form->resetColor();
    }

    public function update()
    {
        $validated = $this->form->validate();

        $colorService = app('ColorService');
        $dto = UpdateColorDto::makeDto($this->form->color, $validated);
        $colorService->update($dto);
        $this->dispatch('success', message: 'رنگ با موفقیت ویرایش شد.');
    }

    public function render()
    {
        return view('components.manager.colors.color-row');
    }
}
