<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateShippingForm extends Form
{
    public string $name = '';
    public ?string $description = null;
    public ?int $price = null;
    public bool $status = true;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('shippings'),
            ],
            'price' => [
                'required',
                'integer',
                'min:0'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام پست الزامی است.',
            'name.max' => 'نام پست باید بیشتر از 50 کاراکتر باشد.',
            'name.unique' => 'نام پست نمی‌تواند تکراری باشد.',
            'price.required' => 'قیمت پست الزامی است.',
            'price.integer' => 'قیمت پست نامعتبر است.',
            'price.min' => 'قیمت پست نامعتبر است.',
        ];
    }
}
