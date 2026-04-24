<?php

namespace App\Livewire\Pages\Manager;

use App\Exceptions\Category\CategoryException;
use App\Http\Dto\Category\CreateCategoryDto;
use App\Http\Dto\Gallery\UploadImageDto;
use App\Http\Dto\Request\Gallery\GetImage as GetImageDto;
use App\Http\Dto\Response\Image as ImageDto;
use App\Livewire\Forms\CreateCategoryForm;
use App\Models\Category;
use App\Models\File;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class CategoriesCreate extends Component
{
    public string $pageTitle = 'ایجاد دسته‌بندی جدید';
    public string $routeName = 'manager.categories.create';
    public CreateCategoryForm $form;
    public Collection $categories;
    public ?ImageDto $image = null;

    public function mount()
    {
        $this->getCategories();
    }

    #[On('set-selected-image')]
    public function setSelectedImage(int $id): void
    {
        $this->form->imageId = $id;
        $galleryService = app('GalleryService');
        $dto = GetImageDto::makeDto(['id' => $id]);
        $this->image = $galleryService->getImage($dto);
    }

    public function removeImage(): void
    {
        $this->reset('image');
    }

    #[On('get-categories')]
    public function getCategories(): void
    {
        $CategoryService = app('CategoryService');
        $this->categories = $CategoryService->getAllCategories();
    }

    public function store(): void
    {
        try {
            $validated = $this->form->validate();
            $CategoryService = app('CategoryService');
            $dto = CreateCategoryDto::makeDto($validated);
            $CategoryService->createCategory($dto);
            $this->reset('form', 'image');
            $this->dispatch('get-categories');
            $this->dispatch('success', message: "دسته‌بندی با موفقیت منتشر شد.");
        } catch (CategoryException $exception) {
            $this->addError("form.name", "نام دسته‌بندی نمی‌تواند تکراری باشد.");
        }
    }

    public function render()
    {
        return view('pages.manager.categories-create');
    }
}
