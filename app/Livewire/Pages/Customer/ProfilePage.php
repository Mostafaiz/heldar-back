<?php

namespace App\Livewire\Pages\Customer;

use App\Exceptions\User\UserException;
use App\Http\Dto\Request\Address\CreateAddress;
use App\Http\Dto\Request\Profile\UpdateUserInfo;
use App\Livewire\Forms\AddAddressForm;
use App\Livewire\Forms\UpdateUserInfoForm;
use App\Services\AddressService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Services\ProfileService;
use App\Services\UserService;
use Illuminate\Auth\Events\Logout;

use function PHPSTORM_META\type;

#[Layout('layouts.customer')]
class ProfilePage extends Component
{
    public UpdateUserInfoForm $form;
    public AddAddressForm $addressForm;
    public string $phone;
    public array $addresses;
    public bool $isManager = false;
    public $provinces;
    public $cities;

    public function mount(): void
    {
        $this->loadUserInfo();
        $this->loadProvinces();
        $this->loadAddresses();
    }

    private function loadUserInfo(): void
    {
        try {
            $user = app(ProfileService::class)->getUserInfo();
            $this->form->name = $user->name;
            $this->form->family = $user->family;
            $this->phone = $user->phone;
            $this->isManager = $user->role == 'manager' ? true : false;
        } catch (UserException $exception) {
            $this->dispatch('exception', message: 'کاربر یافت نشد!');
        }
    }

    #[On('load-addresses')]
    public function loadAddresses(): void
    {
        try {
            $this->addresses = app(UserService::class)->getCurrentUserAddresses();
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در بارگذاری آدرس‌ها!');
        }
    }

    public function submit(): void
    {
        $validated = $this->form->validate();
        $service = app(ProfileService::class);

        try {
            $dto = UpdateUserInfo::makeDto($validated);
            $service->updateUserInfo($dto);
            $this->loadUserInfo();
            $this->loadAddresses();
            $this->dispatch('notify', type: 'success', message: 'پروفایل با موفقیت به روز رسانی شد!');
        } catch (UserException $exception) {
            $this->dispatch('notify', type: 'error', message: 'خطا در تغییرات اطلاعات!');
        }
    }

    public function submitWithAddress()
    {
        $validatedProfile = $this->form->validate();
        $validatedAddress = $this->addressForm->validate();

        try {
            app(ProfileService::class)->updateUserInfo(
                UpdateUserInfo::makeDto($validatedProfile)
            );
            app(AddressService::class)->create(
                CreateAddress::makeDto($validatedAddress)
            );
            $this->loadUserInfo();
            $this->loadAddresses();
            $this->dispatch('notify', type: 'success', message: 'پروفایل با موفقیت به روز رسانی شد!');
        } catch (\Throwable $exception) {
            $this->dispatch('notify', type: 'error', message: 'خطا در تغییرات اطلاعات!');
        }
    }

    public function resetProfileData()
    {
        $this->reset('form.name', 'form.family');
    }

    public function deleteAddress(int $id): void
    {
        app(AddressService::class)->delete($id);
        $this->loadAddresses();
        $this->dispatch('notify', type: 'success', message: 'آدرس با موفقیت حذف شد!');
    }

    public function loadProvinces()
    {
        $this->provinces = app(AddressService::class)->getProvinces();
    }

    public function loadCities()
    {
        $this->cities = app(AddressService::class)->getCitiesByProvince($this->addressForm->provinceId);
    }

    public function logout()
    {
        $authService = app('AuthService');
        $authService->logout();
        $this->redirect(route('login'));
    }

    public function render()
    {
        return view('pages.customer.profile-page');
    }
}
