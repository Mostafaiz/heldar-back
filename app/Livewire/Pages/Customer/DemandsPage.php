<?php

namespace App\Livewire\Pages\Customer;

use App\Services\DemandService;
use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class DemandsPage extends Component
{
    public $demands;
    public bool $isLoggedIn = false;

    public function mount()
    {
        $this->isLoggedIn = app(UserService::class)->isLoggedIn();

        $this->loadDemands();
    }

    public function loadDemands()
    {
        try {
            $this->demands = app(DemandService::class)->getUserDemands();
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در بارگذاری درخواست‌ها!');
        }
    }

    public function removeDemand(int $id)
    {
        try {
            app(DemandService::class)->deleteDemand($id);
            $this->loadDemands();
            $this->dispatch('notify', type: 'success', message: 'با موفقیت حذف شد!');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در حذف درخواست!');
        }
    }

    public function render()
    {
        return view('pages.customer.demands-page');
    }
}
