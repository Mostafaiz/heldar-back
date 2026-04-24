<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class AddManagerForm extends Form
{
    public string $phone = '';

    public function rules()
    {
        return [
            'phone' => [
                'required',
                'regex:/^09\d{9}$/',
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'شماره تلفن کاربر الزامی است.',
            'phone.regex' => 'شماره تلفن معتبر نیست.',
        ];
    }
}