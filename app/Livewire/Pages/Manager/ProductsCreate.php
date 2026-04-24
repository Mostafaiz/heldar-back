<?php

namespace App\Livewire\Pages\Manager;

use App\Enums\ProductLevelsEnum;
use App\Exceptions\Product\ProductException;
use App\Http\Dto\Request\Product\Attribute;
use App\Http\Dto\Request\Product\CreatePattern;
use App\Http\Dto\Request\Product\CreateProduct as CreateProductDto;
use App\Livewire\Concerns\HasArrayActions;
use App\Livewire\Forms\CreateProductForm;
use App\Services\AttributeService;
use App\Services\CategoryService;
use App\Services\ColorService;
use App\Services\GalleryService;
use App\Services\GuaranteeService;
use App\Services\InsuranceService;
use App\Services\ProductService;
use App\Services\SizeService;
use Carbon\Exceptions\Exception as ExceptionsException;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class ProductsCreate extends Component
{
    public string $pageTitle = 'افزودن محصول جدید';
    public string $routeName = 'manager.products.create';
    public CreateProductForm $form;
    public array $categories = [];
    public array $colors = [];
    public array $sizes = [];
    public array $guarantees = [];
    public array $insurances = [];
    public array $attributeGroups = [];

    public function mount()
    {
        $this->form->level = ProductLevelsEnum::BORONZE->value;
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

    public function create(array $generalInfo, array $categories, array $patterns, ?array $attributes, bool $isValidated): void
    {
        $this->form->fill(
            [
                'name' => $generalInfo['name'],
                'englishName' => $generalInfo['englishName'],
                'slug' => $generalInfo['slug'],
                'brand' => $generalInfo['brand'],
                'description' => $generalInfo['description'],
                'categories' => $categories,
            ]
        );


        $this->form->cleanPrice();
        $validated = $this->form->validate();

        if (!$isValidated) return;

        try {
            $productDto = CreateProductDto::makeDto(
                [
                    'categories' => $categories,
                    'patterns' => array_map(function ($value) {
                        return CreatePattern::makeDto($value);
                    }, $patterns),
                    'level' => $this->form->level,
                    'attributes' => $attributes !== null ? Attribute::makeDto($attributes) : null,
                    ...$validated,
                ]
            );

            $productService = app(ProductService::class);
            $productService->create($productDto);

            $this->dispatch('success', message: 'محصول با موفقیت ذخیره شد.');
            $this->form->level = 'boronze';
            $this->reset('form.price');
        } catch (\Throwable $e) {
            $this->dispatch('exception', message: 'خطا در ثبت محصول!');
        }
    }

    public function render()
    {
        return view('pages.manager.products-create');
    }
}
