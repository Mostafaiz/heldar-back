<?php

namespace App\Livewire\Components\Manager\Gallery;

use App\Exceptions\Gallery\FolderException;
use App\Http\Dto\Request\Gallery\GetFolder;
use App\Livewire\Forms\UpdateFolderForm;
use App\Http\Dto\Request\Gallery\UpdateFolder as UpdateFolderDto;
use App\Models\Folder;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateFolderModal extends Component
{

    public bool $visible = false;
    public UpdateFolderForm $form;

    #[On('show-update-folder-modal')]
    public function showModal(int $id): void
    {
        $this->visible = true;
        $this->form->id = $id;
        $this->form->name = $this->getFolderName();
        $this->dispatch('focus');
    }

    #[On('hide-update-folder-modal')]
    public function hideModal(): void
    {
        $this->visible = false;
        $this->form->reset();
        $this->resetErrors();
    }

    public function update(): void
    {
        if ($this->form->name !== $this->getFolderName()) {
            try {
                $validated = $this->form->validate();
                $galleryService = app('GalleryService');
                $dto = UpdateFolderDto::makeDto($validated);
                $galleryService->updateFolder($dto);
                $this->dispatch('gallery-go-to-page');
                $this->dispatch('success', message: "نام پوشه با موفقیت تغییر یافت.");
            } catch (FolderException $exception) {
                $this->addError("form.name", "نام پوشه نمی‌تواند تکراری باشد.");
            }
        }

        $this->hideModal();
        $this->form->reset();
    }

    public function getFolderName(): string
    {
        $validated = $this->form->validateOnly('id');
        $galleryService = app('GalleryService');
        $dto = GetFolder::makeDto($validated);
        return $galleryService->getFolder($dto)->name;
    }

    public function resetErrors(): void
    {
        $this->resetErrorBag('form.name');
    }

    public function render()
    {
        return view('components.manager.gallery.update-folder-modal');
    }
}
