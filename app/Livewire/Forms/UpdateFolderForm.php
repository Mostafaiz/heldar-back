<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateFolderForm extends Form
{
    public int $id = 0;
    public string $name = '';

    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('folders', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('folders')->where('name', $this->name),
            ],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'پوشه انتخاب شده معتبر نیست.',
            'id.exists' => 'پوشه انتخاب شده معتبر نیست.',
            'id.integer' => 'پوشه انتخاب شده معتبر نیست.',
            'name.required' => 'نام پوشه الزامی است.',
            'name.string' => 'نام پوشه معتبر نیست.',
            'name.max' => 'نام پوشه باید کمتر از 255 کاراکتر باشد.',
            'name.unique' => 'نام پوشه تکراری است.',
        ];
    }
}
