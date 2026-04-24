<?php

namespace App\Livewire\Components\Manager\Guarantees;

use App\Exceptions\Guarantee\GuaranteeException;
use App\Http\Dto\Response\Guarantee as GuaranteeDto;
use App\Services\GuaranteeService;
use Livewire\Attributes\On;
use Livewire\Component;

class GuaranteeDetailsModal extends Component
{
    public GuaranteeDto $guarantee;

    #[On('get-guarantee-data')]
    public function getData(int $id): void
    {
        try {
            $this->guarantee = app(GuaranteeService::class)->getGuaranteeById($id);
        } catch (GuaranteeException $exception) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات گارانتی!');
        }
    }

    public function resetData(): void
    {
        $this->reset();
    }

    public function render()
    {
        return view('components.manager.guarantees.guarantee-details-modal');
    }
}