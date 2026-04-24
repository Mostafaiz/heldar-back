<?php

namespace App\Livewire\Pages\Manager;

use App\Http\Dto\DeleteColorDto;
use App\Models\Color;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class ColorsIndex extends Component
{
    public string $pageTitle = 'رنگ‌ها';
    public string $routeName = 'manager.colors.index';
    public Collection $colors;
    public function mount()
    {
        $this->getColors();
    }

    #[On('get-colors')]
    public function getColors()
    {
        $colorService = app('ColorService');
        $this->colors = $colorService->index();
    }

    public function delete(int $id)
    {
        $colorService = app('ColorService');
        $dto = DeleteColorDto::makeDto(Color::find($id));
        $colorService->delete($dto);
        $this->dispatch('get-colors');
        $this->dispatch('success', message: 'رنگ با موفقیت حذف شد.');
    }

    public function render()
    {
        return view('pages.manager.colors-index');
    }
}
