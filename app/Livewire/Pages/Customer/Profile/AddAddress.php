<?php

namespace App\Livewire\Pages\Customer\Profile;

use App\Http\Dto\Request\Address\CreateAddress as CreateAddressDto;
use App\Livewire\Forms\AddAddressForm;
use App\Models\Province;
use Livewire\Attributes\Layout;
use App\Services\AddressService;
use Livewire\Component;

use function PHPSTORM_META\type;

#[Layout('layouts.customer')]
class AddAddress extends Component
{
    public AddAddressForm $form;
    public $provinces;
    public $cities;

    public function mount()
    {
        $this->loadProvinces();
    }

    public function submit()
    {
        $this->form->normalize();
        $validated = $this->form->validate();

        try {
            $dto = CreateAddressDto::makeDto($validated);
            app(AddressService::class)->create($dto);
            $this->dispatch('load-addresses');
            $this->dispatch('notify', type: 'success', message: 'آدرس با موفقیت افزوده شد!');
            $this->form->reset('form.name', 'form.provinceId', 'form.cityId', 'form.fullAddress', 'form.zipcode');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در افزودن آدرس!');
        }
    }

    public function resetData(): void
    {
        $this->reset('form.name', 'form.provinceId', 'form.cityId', 'form.fullAddress', 'form.zipcode');
        $this->resetValidation();
    }

    public function loadProvinces()
    {
        $this->provinces = app(AddressService::class)->getProvinces();
    }

    public function loadCities()
    {
        $this->cities = app(AddressService::class)->getCitiesByProvince($this->form->provinceId);
    }

    public function render()
    {
        return view('pages.customer.profile.add-address');
    }
}
