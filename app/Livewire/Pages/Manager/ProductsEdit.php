<?php

namespace App\Livewire\Pages\Manager;

use App\Http\Dto\Request\Product\Attribute;
use App\Http\Dto\Request\Product\DeleteProduct as DeleteProductDto;
use App\Http\Dto\Request\Product\UpdatePattern;
use App\Http\Dto\Request\Product\UpdateProduct as UpdateProductDto;
use App\Livewire\Forms\CreateProductForm;
use App\Livewire\Forms\UpdateProductForm;
use App\Services\AttributeService;
use App\Services\CategoryService;
use App\Services\ColorService;
use App\Services\GalleryService;
use App\Services\GuaranteeService;
use App\Services\InsuranceService;
use App\Services\ProductService;
use App\Services\SizeService;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.manager')]
class ProductsEdit extends Component
{
    public string $pageTitle = 'ویرایش محصول';
    public string $routeName = 'manager.products.edit';
    public UpdateProductForm $form;
    public array $categories = [];
    public array $colors = [];
    public array $sizes = [];
    public array $guarantees = [];
    public array $insurances = [];
    public array $attributeGroups = [];
    public int $productId;

    public function mount(int $id)
    {
        $this->productId = $id;
        $this->form->id = $id;
    }

    public function loadProduct()
    {
        return app(ProductService::class)->getProductById($this->productId);
    }

    public function loadCategories(string $name): void
    {
        $this->categories = app(CategoryService::class)->getCategoriesByNameWithImage($name)->toArray();
    }

    public function loadColors(string $name): void
    {
        $this->colors = app(ColorService::class)->getColorsByName($name)->toArray();
    }

    public function loadSizes(string $name): void
    {
        $this->sizes = app(SizeService::class)->getSizesByName($name)->toArray();
    }

    public function loadGuarantees(string $name): void
    {
        $this->guarantees = app(GuaranteeService::class)->getGuaranteesByName($name)->toArray();
    }

    public function loadInsurances(string $name): void
    {
        $this->insurances = app(InsuranceService::class)->getInsurancesByName($name)->toArray();
    }

    public function numberFormat(string $value): string
    {
        return number_format((int) $value);
    }

    public function loadImages(array $ids)
    {
        return app(GalleryService::class)->getImagesByIds($ids)->toArray();
    }

    public function loadAttributeGroups(string $name)
    {
        $this->attributeGroups = app(AttributeService::class)->getAttributeGroupsByName($name)->toArray();
    }

    public function update(array $generalInfo, array $categories, array $patterns, ?array $attributes, bool $isValidated): void
    {
        $this->form->fill(
            [
                'name' => $generalInfo['name'],
                'englishName' => $generalInfo['englishName'],
                'slug' => $generalInfo['slug'],
                'brand' => $generalInfo['brand'],
                'description' => $generalInfo['description'],
                'price' => $generalInfo['price'],
                'level' => $generalInfo['level'],
                'categories' => $categories,
            ]
        );

        $this->form->cleanPrice();
        $validated = $this->form->validate();

        if (!$isValidated) return;

        try {
            $productDto = UpdateProductDto::makeDto(
                [
                    'patterns' => array_map(function ($value) {
                        return UpdatePattern::makeDto($value);
                    }, $patterns),
                    'attributes' => $attributes !== null ? Attribute::makeDto($attributes) : null,
                    'id' => $this->productId,
                    ...$validated,
                ]
            );

            $productService = app(ProductService::class);
            $productService->update($productDto);

            $this->dispatch('success', message: 'محصول با موفقیت ویرایش شد.');
        } catch (\Throwable $e) {
            $this->dispatch('exception', message: 'خطا در ویرایش محصول!');
        }
    }

    public function delete()
    {
        try {
            app(ProductService::class)->delete(DeleteProductDto::makeDto(['id' => $this->productId]));
            $this->dispatch('success', message: 'محصول با موفقیت حذف شد.');
            $this->redirect('/manager/products', true);
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در حذف محصول!');
        }
    }

    public function render()
    {
        return view('pages.manager.products-edit');
    }
}
