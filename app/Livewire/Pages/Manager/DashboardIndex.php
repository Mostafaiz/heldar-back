<?php

namespace App\Livewire\Pages\Manager;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.manager')]
class DashboardIndex extends Component
{
    public string $pageTitle = 'داشبورد';
    public string $routeName = 'manager.dashboard.index';

    public function render()
    {
        return view('pages.manager.dashboard-index');
    }
}