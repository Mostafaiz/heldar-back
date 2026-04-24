<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateCategoryForm extends Form
{

    public int $id;
    public string $name = '';
    public ?string $descriptionCategory = null;
    public ?string $descriptionPage = null;
    public int $status = 1;
    public ?int $imageId = null;
    public ?int $parentId;

    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('categories')->ignore($this->id),
            ],
            'descriptionCategory' => [
                'nullable',
                'string'
            ],
            'descriptionPage' => [
                'nullable',
                'string'
            ],
            'status' => [
                'required',
                'integer'
            ],
            'imageId' => [
                'nullable',
                Rule::exists('files', 'id')
            ],
            'parentId' => [
                'nullable',
                Rule::exists('categories', 'id')
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام دسته‌بندی الزامی است.',
            'name.string' => 'نام دسته‌بندی باید شامل حروف باشد.',
            'name.unique' => 'نام دسته‌بندی نمی‌تواند تکراری باشد.',
            'descriptionCategory' => 'توضیحات دسته‌بندی باید شامل حروف باشد.',
            'descriptionPage' => 'توضیحات صفحه دسته‌بندی باید شامل حروف باشد.',
        ];
    }
}