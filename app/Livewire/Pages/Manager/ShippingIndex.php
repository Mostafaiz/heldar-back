<?php

namespace App\Livewire\Pages\Manager;

use App\Http\Dto\Request\Shipping\DeleteShipping as DeleteShippingDto;
use App\Http\Dto\Request\Shipping\CreateShipping as CreateShippingDto;
use App\Livewire\Forms\CreateShippingForm;
use App\Services\ShippingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class ShippingIndex extends Component
{
    public string $pageTitle = 'پست ارسال';
    public string $routeName = 'manager.shipping.index';
    public CreateShippingForm $form;
    public array $shippings;

    public function mount()
    {
        $this->loadShippings();
    }

    public function create(): void
    {
        $this->form->validate();
        try {
            $dto = CreateShippingDto::makeDto($this->form);
            app(ShippingService::class)->create($dto);
            $this->loadShippings();
            $this->dispatch('success', message: 'پست ارسال با موفقیت افزوده شد!');
            $this->reset('form.name', 'form.price', 'form.description');
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در افزودن پست!');
        }
    }

    #[On('get-all-shipping')]
    public function loadShippings(): void
    {
        $this->shippings = app(ShippingService::class)->getAllShippings();
    }

    public function delete(int $id): void
    {
        try {
            app(ShippingService::class)->delete(DeleteShippingDto::makeDto(['id' => $id]));
            $this->dispatch('success', message: 'پست ارسال با موفقیت حذف شد!');
            $this->loadShippings();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در حذف پست!');
        }
    }

    public function render()
    {
        return view('pages.manager.shipping-index');
    }
}
