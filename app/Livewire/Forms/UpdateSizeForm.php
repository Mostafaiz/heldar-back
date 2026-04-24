<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateSizeForm extends Form
{
    public ?int $id;
    public string $name = '';

    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('sizes', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sizes')->ignore($this->id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'خطا در انجام عملیات.',
            'id.integer' => 'خطا در انجام عملیات.',
            'id.exists' => 'سایز مورد نظر یافت نشد.',
            'name.required' => 'نام سایز الزامی است.',
            'name.max' => 'نام سایز باید کمتر از 50 کاراکتر باشد.',
            'name.unique' => 'نام سایز نمی‌تواند تکراری باشد.',
        ];
    }
}