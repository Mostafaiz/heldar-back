<?php

namespace App\Livewire\Pages\Manager;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.manager')]
class GalleryIndex extends Component
{
    public function render()
    {
        return view('pages.manager.gallery-index');
    }
}
