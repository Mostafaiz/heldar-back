<?php

namespace App\Livewire\Pages\Manager;

use App\Exceptions\Insurance\InsuranceException;
use App\Http\Dto\Request\Insurance\CreateInsurance as CreateInsuranceDto;
use App\Http\Dto\Request\Insurance\DeleteInsurance as DeleteInsuranceDto;
use App\Livewire\Forms\CreateInsuranceForm;
use App\Services\InsuranceService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class InsurancesIndex extends Component
{
    public string $pageTitle = 'بیمه‌ها';
    public string $routeName = 'manager.insurances.index';
    public CreateInsuranceForm $form;

    public function create(): void
    {
        $this->form->normalize();
        $validated = $this->form->validate();
        $insuranceService = app(InsuranceService::class);
        $dto = CreateInsuranceDto::makeDto($validated);

        try {
            $insuranceService->create($dto);
            $this->dispatch('success', message: 'بیمه با موفقیت ایجاد شد.');
            $this->form->reset();
        } catch (InsuranceException $exception) {
            $this->dispatch('exception', message: 'خطا در ایجاد بیمه!');
        }
    }

    #[On('get-all-insurances')]
    public function showAll(): Collection
    {
        return app(InsuranceService::class)->getAll();
    }

    public function delete(int $id): void
    {
        $insuranceService = app(InsuranceService::class);
        $dto = DeleteInsuranceDto::makeDto(['id' => $id]);

        try {
            $insuranceService->delete($dto);
            $this->dispatch('success', message: 'بیمه با موفقیت حذف شد.');
        } catch (InsuranceException $exception) {
            $this->dispatch('exception', 'خطا در حذف بیمه!');
        }
    }

    public function render()
    {
        $insurances = $this->showAll();
        return view('pages.manager.insurances-index', compact('insurances'));
    }
}
