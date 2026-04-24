<?php

namespace App\Livewire\Pages\Manager;

use App\Services\DemandService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.manager')]
class DemandsIndex extends Component
{
    public string $pageTitle = 'درخواست‌های کاربران';
    public string $routeName = 'manager.demands.index';
    public $demands;

    public function mount()
    {
        $this->loadDemands();
    }

    public function loadDemands()
    {
        try {
            $this->demands = app(DemandService::class)->getAllDemands();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در بارگذاری!');
        }
    }

    public function removeDemand(int $id)
    {
        try {
            app(DemandService::class)->deleteDemand($id);
            $this->loadDemands();
            $this->dispatch('success', message: 'با موفقیت حذف شد!');
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در حذف!');
        }
    }

    public function render()
    {
        return view('pages.manager.demands-index');
    }
}
