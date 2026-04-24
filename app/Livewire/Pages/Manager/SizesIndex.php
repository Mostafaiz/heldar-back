<?php

namespace App\Livewire\Pages\Manager;

use App\Exceptions\Size\SizeException;
use App\Http\Dto\Request\Size\CreateSize as CreateSizeDto;
use App\Http\Dto\Request\Size\UpdateSize as UpdateSizeDto;
use App\Http\Dto\Request\Size\DeleteSize as DeleteSizeDto;
use App\Livewire\Forms\CreateSizeForm;
use App\Livewire\Forms\UpdateSizeForm;
use App\Services\SizeService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.manager')]
class SizesIndex extends Component
{
    public string $pageTitle = 'سایزها';
    public string $routeName = 'manager.sizes.index';
    public CreateSizeForm $createForm;
    public UpdateSizeForm $updateForm;

    public function create(
    ): void {
        $validated = $this->createForm->validate();
        $sizeService = app(SizeService::class);
        $dto = CreateSizeDto::makeDto($validated);

        try {
            $sizeService->create($dto);
            $this->createForm->reset();
            $this->dispatch('success', message: 'سایز با موفقیت ایجاد شد.');
        } catch (SizeException $exception) {
            $this->dispatch('exception', message: 'خطا در ایجاد سایز!');
        }

        $this->showAll();
    }

    public function showAll(): Collection
    {
        $sizeService = app(SizeService::class);

        return $sizeService->getAll();
    }

    public function update(int $id, string $name): void
    {
        $this->updateForm->id = $id;
        $this->updateForm->name = $name;
        $validated = $this->updateForm->validate();
        $sizeService = app(SizeService::class);
        $dto = UpdateSizeDto::makeDto($validated);

        try {
            $sizeService->update($dto);
            $this->updateForm->reset();
            $this->dispatch('success', message: 'سایز با موفقیت ویرایش شد.');
        } catch (SizeException $exception) {
            $this->dispatch('exception', message: 'خطا در ویرایش سایز!');
        }

        $this->showAll();
    }

    public function resetErrors(): void
    {
        $this->resetErrorBag();
        $this->reset('updateForm');
    }

    public function delete(int $id): void
    {
        $galleryService = app(SizeService::class);
        $dto = DeleteSizeDto::makeDto(['id' => $id]);

        try {
            $galleryService->delete($dto);
            $this->dispatch('success', message: 'سایز با موفقیت حذف شد.');
            $this->showAll();
        } catch (SizeException $exception) {
            $this->dispatch('exception', message: 'خطا در حذف سایز!');
        }
    }

    public function render()
    {
        $sizes = $this->showAll();
        return view('pages.manager.sizes-index', compact('sizes'));
    }
}
