<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateProductForm extends Form
{
    public int $id;
    public string $name = '';
    public ?string $englishName = '';
    public string $slug = '';
    public ?string $brand = '';
    public ?string $description = '';
    public ?string $price;
    public bool $status = true;
    public string $level;
    public array $categories;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('products', 'name')->ignore($this->id),
            ],
            'englishName' => ['nullable'],
            'slug' => [
                'required',
                Rule::unique('products', 'slug')->ignore($this->id),
            ],
            'brand' => ['nullable'],
            'description' => ['nullable'],
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
            'level' => ['required'],
            'status' => ['required'],
            'categories.*.id' => [
                Rule::exists('categories', 'id')
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام محصول الزامی می‌باشد.',
            'name.unique' => 'نام محصول تکراری می‌باشد.',
            'name.string' => 'نام محصول معتبر نمی‌باشد.',
            'englishName.string' => 'نام انگلیسی محصول معتبر نمی‌باشد.',
            'slug.required' => 'نامک محصول الزامی می‌باشد.',
            'slug.unique' => 'نامک محصول تکراری می‌باشد.',
            'price.required' => 'قیمت الزامی می‌باشد.',
            'price.integer' => 'قیمت معتبر نمی‌باشد.',
            'price.min' => 'قیمت نباید منفی باشد.',
            'categories.*.id.exists' => 'دسته‌بندی موجود نمی‌باشد.',
        ];
    }

    public function cleanPrice()
    {
        $this->price = str_replace(',', '', $this->price);
    }
}
