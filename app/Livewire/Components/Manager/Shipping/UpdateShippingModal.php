<?php

namespace App\Livewire\Components\Manager\Shipping;

use App\Exceptions\Insurance\InsuranceException;
use App\Exceptions\Shipping\ShippingException;
use App\Http\Dto\Request\Shipping\UpdateShipping as UpdateShippingDto;
use App\Livewire\Forms\UpdateShippingForm;
use App\Services\ShippingService;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateShippingModal extends Component
{
    public UpdateShippingForm $form;

    #[On('get-shipping-data-for-edit')]
    public function getData(int $id): void
    {
        try {
            $shipping = app(ShippingService::class)->getShippingById($id);
            $this->form->setData($shipping);
        } catch (ShippingException $exception) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات پست!');
        }
    }

    public function update(): void
    {
        $this->form->normalize();
        $validated = $this->form->validate();
        $guaranteeService = app(ShippingService::class);
        $dto = UpdateShippingDto::makeDto($validated);

        try {
            $guaranteeService->update($dto);
            $this->dispatch('success', message: "پست با موفقیت ویرایش شد.");
            $this->form->reset();
            $this->dispatch('get-all-shipping');
        } catch (\Throwable $exception) {
            $this->dispatch('exception', message: 'خطا در ویرایش پست!');
        }
    }

    public function resetData(): void
    {
        $this->form->reset();
    }

    public function render()
    {
        return view('components.manager.shipping.update-shipping-modal');
    }
}
