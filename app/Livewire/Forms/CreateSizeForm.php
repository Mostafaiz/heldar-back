<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateSizeForm extends Form
{
    public string $name = '';

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sizes'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام سایز الزامی است.',
            'name.max' => 'نام سایز باید بیشتر از 50 کاراکتر باشد.',
            'name.unique' => 'نام سایز نمی‌تواند تکراری باشد.',
        ];
    }
}