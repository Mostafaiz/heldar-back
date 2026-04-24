<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateAttributeGroupForm extends Form
{
    public string $name = '';
    public array $attributes = [];

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('attribute_groups')->withoutTrashed()
            ],
            'attributes' => [
                'required',
            ],
            'attributes.*' => [
                'distinct',
                'required'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام مجموعه ویژگی‌ها الزامی است.',
            'name.string' => 'نام مجموعه ویژگی‌ها باید دارای حروف باشد.',
            'name.unique' => 'نام مجموعه ویژگی‌ها نمی‌تواند تکراری باشد.',
            'attributes.required' => 'حداقل یک ویژگی الزامی است.',
            'attributes.*.required' => 'نام ویژگی نمی‌تواند خالی باشد.',
            'attributes.*.distinct' => 'نام ویژگی نمی‌تواند تکراری باشد.',
        ];
    }
}