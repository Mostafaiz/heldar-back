<?php

namespace App\Livewire\Components\Manager;

use Livewire\Component;

class DashboardNavlink extends Component
{
    public string $text, $route, $icon;
    
    public function render()
    {
        return view('components.manager.dashboard-navlink');
    }
}
