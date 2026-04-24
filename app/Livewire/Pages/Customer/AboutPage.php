<?php

namespace App\Livewire\Pages\Customer;

use App\Services\SiteConfigService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class AboutPage extends Component
{
    public ?array $data;

    public function mount()
    {
        $this->data = app(SiteConfigService::class)->getData();
    }

    public function render()
    {
        return view('pages.customer.about-page');
    }
}
