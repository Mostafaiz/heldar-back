<?php

namespace App\Livewire\Components\Customer\Profile;

use App\Exceptions\Profile\AddressException;
use App\Http\Dto\Request\Profile\UpdateAddressUser;
use App\Livewire\Forms\UpdateAddressForm;
use App\Services\AddressService;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateAddress extends Component
{
    public UpdateAddressForm $form;
    public $provinces;
    public $cities;

    public function mount()
    {
        $this->loadProvinces();
    }

    #[On('load-address-for-edit')]
    public function getData(int $id): void
    {
        try {
            $address = app(AddressService::class)->getAddressById($id);
            $this->form->setData($address);
            $this->loadCities();
        } catch (AddressException $exception) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات ادرس!');
        }
    }

    public function update(): void
    {
        $this->form->normalize();
        $validated = $this->form->validate();

        try {
            $addressService = app(AddressService::class);
            $dto = UpdateAddressUser::makeDto($validated);
            $addressService->update($dto);
            $this->dispatch('load-addresses');
            $this->dispatch('notify', type: 'success', message: "آدرس با موفقیت ویرایش شد.");
        } catch (AddressException $exception) {
            $this->dispatch('notify', type: 'error', message: 'خطا در ویرایش آدرس!');
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
        return view('components.customer.profile.update-address');
    }
}
