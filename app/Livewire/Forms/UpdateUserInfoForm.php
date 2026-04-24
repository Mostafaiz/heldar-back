<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateUserInfoForm extends Form
{
    public ?string $name = '';
    public ?string $family = '';

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:225',
            ],
            'family' => [
                'required',
                'string',
                'max:225',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام الزامی است.',
            'name.string' => 'نام معتبر نمی‌باشد.',
            'name.max' => 'نام طولانی است.',
            'family.required' => 'نام خانوادگی الزامی است.',
            'family.string' => 'نام خانوادگی معتبر نمی‌باشد.',
            'family.max' => 'نام خانوادگی طولانی است.',
        ];
    }
}
