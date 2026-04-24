<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class ManagerLoginForm extends Form
{
    public string $password = '';

    public function rules()
    {
        return [
            'password' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'password.required' => 'رمز عبور الزامی است.',
        ];
    }
}
