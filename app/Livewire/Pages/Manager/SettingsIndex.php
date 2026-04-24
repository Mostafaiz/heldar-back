<?php

namespace App\Livewire\Pages\Manager;

use App\Livewire\Forms\UpdatePublicSiteConfigDataForm;
use App\Livewire\Forms\UpdateSMSPhonesForm;
use App\Services\SiteConfigService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.manager')]
class SettingsIndex extends Component
{
    public string $pageTitle = "تنظیمات سامانه";
    public string $routeName = "manager.settings";
    public UpdatePublicSiteConfigDataForm $publicDataForm;
    public UpdateSMSPhonesForm $SMSPhonesForm;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        try {
            $data = app(SiteConfigService::class)->getData();
            if ($data) $this->publicDataForm->setData($data);
            if ($data) $this->SMSPhonesForm->setData($data);
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات!');
        }
    }

    public function submitPublicData()
    {
        $validated = $this->publicDataForm->validate();

        try {
            app(SiteConfigService::class)->updateGeneralData($validated);
            $this->dispatch('success', message: 'با موفقیت به روز رسانی شد!');
            $this->loadData();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در ویرایش اطلاعات!');
        }
    }

    public function submitSMSPhones()
    {
        $validated = $this->SMSPhonesForm->validate();

        try {
            app(SiteConfigService::class)->updateSMSPhones($validated);
            $this->dispatch('success', message: 'با موفقیت به روز رسانی شد!');
            $this->loadData();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در ویرایش اطلاعات!');
        }
    }

    public function render()
    {
        return view('pages.manager.settings-index');
    }
}
