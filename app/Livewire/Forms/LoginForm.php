<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class LoginForm extends Form
{
    public string $phone = '';
    public string $code = '';
    public function rules(): array
    {
        return [
            'phone' => 'required|regex:/^09\d{9}$/',
            'code' => 'required|regex:/^\d{6}$/',
        ];
    }
    public function messages()
    {
        return [
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.regex' => 'شماره موبایل نامعتبر است.',
            'code.required' => 'کد تایید الزامی است.',
            'code.regex' => 'کد تایید نامعتبر است.',
        ];
    }
}
