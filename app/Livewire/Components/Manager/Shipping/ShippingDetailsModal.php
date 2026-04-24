<?php

namespace App\Livewire\Components\Manager\Shipping;

use App\Http\Dto\Response\Shipping as ShippingDto;
use App\Services\ShippingService;
use Livewire\Attributes\On;
use Livewire\Component;

class ShippingDetailsModal extends Component
{
    public ShippingDto $shipping;

    #[On('get-shipping-data')]
    public function getData(int $id): void
    {
        try {
            $this->shipping = app(ShippingService::class)->getShippingById($id);
        } catch (\Throwable $exception) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات پست!');
        }
    }

    public function resetData(): void
    {
        $this->reset();
    }

    public function render()
    {
        return view('components.manager.shipping.shipping-details-modal');
    }
}
