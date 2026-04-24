<?php

namespace App\Livewire\Pages\Manager;

use App\Exceptions\Guarantee\GuaranteeException;
use App\Http\Dto\Request\Guarantee\CreateGuarantee as CreateGuaranteeDto;
use App\Http\Dto\Request\Guarantee\DeleteGuarantee as DeleteGuaranteeDto;
use App\Http\Dto\Response\Guarantee as GuaranteeDto;
use App\Livewire\Forms\CreateGuaranteeForm;
use App\Models\Guarantee;
use App\Services\GuaranteeService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

#[Layout('layouts.manager')]
class GuaranteesIndex extends Component
{
    public string $pageTitle = 'گارانتی‌ها';
    public string $routeName = 'manager.guarantees.index';
    public CreateGuaranteeForm $createForm;
    public GuaranteeDto $guaranteeForEdit;

    public function create(): void
    {
        $this->createForm->normalize();
        $validated = $this->createForm->validate();
        $guaranteeService = app(GuaranteeService::class);
        $dto = CreateGuaranteeDto::makeDto($validated);

        try {
            $guaranteeService->create($dto);
            $this->createForm->reset();
            $this->dispatch('success', message: 'گارانتی با موفقیت ایجاد شد.');
        } catch (GuaranteeException $exception) {
            $this->dispatch('exception', message: 'خطا در ایجاد گارانتی!');
        }
    }

    #[On('get-all-guarantees')]
    public function showAll(): Collection
    {
        $guaranteeService = app(GuaranteeService::class);
        return $guaranteeService->getAll();
    }

    public function delete(int $id): void
    {
        $guaranteeService = app(GuaranteeService::class);
        $dto = DeleteGuaranteeDto::makeDto(['id' => $id]);

        try {
            $guaranteeService->delete($dto);
            $this->dispatch('success', message: 'گارانتی با موفقیت حذف شد.');
        } catch (GuaranteeException $exception) {
            $this->dispatch('exception', message: 'خطا در حذف گارانتی!');
        }
    }

    public function render()
    {
        $guarantees = $this->showAll();
        return view('pages.manager.guarantees-index', compact('guarantees'));
    }
}
