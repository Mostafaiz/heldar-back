<?php

namespace App\Livewire\Pages\Manager;

use App\Exceptions\Category\CategoryException;
use App\Http\Dto\Request\Category\GetCategory;
use App\Http\Dto\Request\Category\UpdateCategory;
use App\Http\Dto\Request\Gallery\GetImage as GetImageDto;
use App\Http\Dto\Response\Image as ImageDto;
use App\Livewire\Forms\UpdateCategoryForm;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class CategoriesEdit extends Component
{
    public string $pageTitle = 'ویرایش دسته‌بندی';
    public string $routeName = 'manager.categories.edit';
    public UpdateCategoryForm $form;
    public Collection $categories;
    public ?ImageDto $image = null;

    public function mount(int $id): void
    {
        $this->form->id = $id;
        $this->loadCategoryValues($id);
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
        $this->reset('image', 'form.imageId');
    }

    #[On('get-categories')]
    public function getCategories(): void
    {
        $CategoryService = app('CategoryService');
        $this->categories = $CategoryService->getAllCategories($this->form->id);
    }

    public function update(): void
    {
        try {
            $validated = $this->form->validate();
            $CategoryService = app('CategoryService');
            $dto = UpdateCategory::makeDto($validated);
            $CategoryService->updateCategory($dto);
            $this->dispatch('success', message: "دسته‌بندی با موفقیت ویرایش شد.", route: '/manager/categories');
        } catch (CategoryException $exception) {
            $this->addError("form.name", "نام دسته‌بندی نمی‌تواند تکراری باشد.");
        }
    }

    public function loadCategoryValues(int $id): void
    {
        $categoryService = app('CategoryService');
        $dto = GetCategory::makeDto(['id' => $id]);

        try {
            $category = $categoryService->getCategory($dto);
        } catch (CategoryException $exception) {
        }

        $this->form->name = $category->name;
        $this->form->descriptionCategory = $category->descriptionCategory;
        $this->form->descriptionPage = $category->descriptionPage;
        $this->form->imageId = $category->image->id ?? null;
        $this->form->parentId = $category->parentId;

        if ($category->image !== null) {
            $this->form->imageId = $category->image->id;
            $this->setSelectedImage($this->form->imageId);
        }
    }

    public function render()
    {
        return view('pages.manager.categories-edit');
    }
}
