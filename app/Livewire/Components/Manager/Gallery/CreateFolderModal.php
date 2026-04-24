<?php

namespace App\Livewire\Components\Manager\Gallery;

use App\Exceptions\Gallery\FolderException;
use App\Http\Dto\Request\Gallery\CreateFolder;
use App\Livewire\Forms\CreateFolderForm;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateFolderModal extends Component
{
    public CreateFolderForm $form;
    public bool $visible = false;

    #[On('show-create-folder-modal')]
    public function showModal(): void
    {
        $this->visible = true;
    }

    #[On('hide-create-folder-modal')]
    public function hideModal(): void
    {
        $this->visible = false;
        $this->form->reset('name');
        $this->resetErrors();
    }

    #[On('set-current-folder')]
    public function setCurrentFolder(?int $id): void
    {
        $this->form->parentId = $id;
    }

    public function store(): void
    {
        try {
            $validated = $this->form->validate();
            $GalleryService = app('GalleryService');
            $dto = CreateFolder::makeDto($validated);
            $GalleryService->createFolder($dto);
            $this->hideModal();
            $this->dispatch('success', message: "پوشه با موفقیت ذخیره شد.");
            $this->dispatch('gallery-go-to-page');
        } catch (FolderException $exception) {
            $this->addError("form.name", "نام پوشه نمی‌تواند تکراری باشد.");
        }
    }

    public function resetErrors(): void
    {
        $this->resetErrorBag('form.name');
    }

    public function render()
    {
        return view('components.manager.gallery.create-folder-modal');
    }
}
