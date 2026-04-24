<?php

namespace App\Livewire\Components\Manager\Gallery;

use App\Exceptions\Gallery\FolderException;
use App\Http\Dto\Gallery\UploadImageDto;
use App\Livewire\Forms\UploadImageForm;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadModal extends Component
{
    use WithFileUploads;

    public UploadImageForm $form;
    public $image;
    public bool $uploading = false;
    public int $progress = 0;
    public bool $visible = false;

    #[On('show-upload-modal')]
    public function showModal(): void
    {
        $this->visible = true;
    }

    #[On('hide-upload-modal')]
    public function hideModal(): void
    {
        $this->visible = false;
        $this->form->reset('image', 'alt');
        $this->resetErrorBag();
    }

    public function removeImage(): void
    {
        $this->form->image = null;
        $this->progress = 0;
    }

    public function store(): void
    {
        $validated = $this->form->validate();
        $galleryService = app('GalleryService');
        $galleryService->uploadImage(UploadImageDto::makeDto($validated));
        $this->hideModal();
        $this->dispatch('gallery-go-to-last-page');
        $this->dispatch('success', message: "تصویر با موفقیت آپلود شد.");
    }

    #[On('set-current-folder')]
    public function setCurrentFolder(?int $id): void
    {
        $this->form->folderId = $id;
    }

    public function addImageMaxError(): void
    {
        $maxSize = config('gallery.max_image_size') / 1024;
        $this->resetImageErrors();
        $this->addError('form.image', "حداکثر حجم فایل $maxSize مگابایت است.");
    }

    public function addImageTypeError(): void
    {
        $this->resetImageErrors();
        $this->addError('form.image', 'فایل انتخاب شده باید یک تصویر باشد.');
    }

    public function addImageNameError(): void
    {
        $this->resetImageErrors();
        $this->addError('form.image', 'نام فایل نمی‌تواند بیشتر از 150 کاراکتر باشد.');
    }

    public function resetImageErrors(): void
    {
        $this->resetErrorBag('form.image');
    }

    public function render()
    {
        return view('components.manager.gallery.upload-modal');
    }
}
