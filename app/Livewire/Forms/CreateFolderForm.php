<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateFolderForm extends Form
{
    public string $name = '';
    public ?int $parentId = null;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('folders')->where('parent_id', $this->parentId),
            ],
            'parentId' => [
                'nullable',
                'integer',
                Rule::exists('folders', 'id'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام پوشه الزامی است.',
            'name.max' => 'نام پوشه باید کمتر از 255 کاراکتر باشد.',
            'name.unique' => 'نام پوشه تکراری است.',
        ];
    }
}
