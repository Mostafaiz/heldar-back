<?php

namespace App\Livewire\Components\Manager\Insurances;

use App\Exceptions\Insurance\InsuranceException;
use App\Http\Dto\Request\Insurance\UpdateInsurance as UpdateInsuranceDto;
use App\Livewire\Forms\UpdateInsuranceForm;
use App\Services\InsuranceService;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateInsuranceModal extends Component
{
    public UpdateInsuranceForm $form;

    #[On('get-insurance-data-for-edit')]
    public function getData(int $id): void
    {
        try {
            $insurance = app(InsuranceService::class)->getInsuranceById($id);
            $this->form->setData($insurance);
        } catch (InsuranceException $exception) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات بیمه!');
        }
    }

    public function update(): void
    {
        $this->form->normalize();
        $validated = $this->form->validate();
        $guaranteeService = app(InsuranceService::class);
        $dto = UpdateInsuranceDto::makeDto($validated);

        try {
            $guaranteeService->update($dto);
            $this->dispatch('success', message: "بیمه با موفقیت ویرایش شد.");
        } catch (InsuranceException $exception) {
            $this->dispatch('exception', message: 'خطا در ویرایش بیمه!');
        }

        $this->form->reset();
        $this->dispatch('get-all-insurances');
    }

    public function resetData(): void
    {
        $this->form->reset();
    }

    public function render()
    {
        return view('components.manager.insurances.update-insurance-modal');
    }
}