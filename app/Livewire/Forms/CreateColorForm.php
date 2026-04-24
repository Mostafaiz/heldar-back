<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateColorForm extends Form
{
    public string $name = '';

    public string $code = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('colors')->whereNull('deleted_at')],
            'code' => ['required', Rule::unique('colors')->whereNull('deleted_at')],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام رنگ الزامی است.',
            'name.string' => 'نام رنگ باید شامل حروف باشد.',
            'code.required' => 'کد رنگ الزامی است.',
        ];
    }
}