<?php

namespace App\Livewire\Components\Manager\Insurances;

use App\Exceptions\Guarantee\GuaranteeException;
use App\Http\Dto\Response\Insurance as InsuranceDto;
use App\Services\InsuranceService;
use Livewire\Attributes\On;
use Livewire\Component;

class InsuranceDetailsModal extends Component
{
    public InsuranceDto $insurance;

    #[On('get-insurance-data')]
    public function getData(int $id): void
    {
        try {
            $this->insurance = app(InsuranceService::class)->getInsuranceById($id);
        } catch (GuaranteeException $exception) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات بیمه!');
        }
    }

    public function resetData(): void
    {
        $this->reset();
    }

    public function render()
    {
        return view('components.manager.insurances.insurance-details-modal');
    }
}