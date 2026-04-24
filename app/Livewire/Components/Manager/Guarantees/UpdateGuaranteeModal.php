<?php

namespace App\Livewire\Components\Manager\Guarantees;

use App\Exceptions\Guarantee\GuaranteeException;
use App\Http\Dto\Request\Guarantee\UpdateGuarantee as UpdateGuaranteeDto;
use App\Livewire\Forms\UpdateGuaranteeForm;
use App\Services\GuaranteeService;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateGuaranteeModal extends Component
{
    public UpdateGuaranteeForm $form;

    #[On('get-guarantee-data-for-edit')]
    public function getData(int $id): void
    {
        try {
            $guarantee = app(GuaranteeService::class)->getGuaranteeById($id);
            $this->form->setData($guarantee);
        } catch (GuaranteeException $exception) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات گارانتی!');
        }
    }

    public function update(): void
    {
        $this->form->normalize();
        $validated = $this->form->validate();
        $guaranteeService = app(GuaranteeService::class);
        $dto = UpdateGuaranteeDto::makeDto($validated);

        try {
            $guaranteeService->update($dto);
            $this->dispatch('success', message: "گارانتی با موفقیت ویرایش شد.");
        } catch (GuaranteeException $exception) {
            $this->dispatch('exception', message: 'خطا در ویرایش گارانتی!');
        }

        $this->form->reset();
        $this->dispatch('get-all-guarantees');
    }

    public function resetData(): void
    {
        $this->form->reset();
    }

    public function render()
    {
        return view('components.manager.guarantees.update-guarantee-modal');
    }
}