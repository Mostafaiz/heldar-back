<?php

namespace App\Livewire\Components\Customer;

use App\Services\SiteConfigService;
use Livewire\Component;

class Footer extends Component
{
    public string $phone;
    public string $address;

    public function mount()
    {
        try {
            $data = app(SiteConfigService::class)->getData();
            $this->phone = $data['admin_phone'];
            $this->address = $data['admin_address'];
        } catch (\Throwable $th) {
            $this->dispatch('nofity', type: 'error', message: 'خطا در دریافت اطلاعات!');
        }
    }

    public function render()
    {
        return view('components.customer.footer');
    }
}
