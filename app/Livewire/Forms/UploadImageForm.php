<?php

namespace App\Livewire\Forms;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UploadImageForm extends Form
{
    public ?UploadedFile $image = null;
    public string $alt = '';
    public ?int $folderId = null;

    public function rules()
    {
        return [
            'image' => [
                'required',
            ],
            'alt' => [
                'required',
                'string',
                'max:255',
            ],
            'folderId' => [
                'nullable',
                'integer',
                Rule::exists('folders', 'id'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => "تصویر الزامی است.",
            'image.image' => "فایل انتخاب شده باید یک تصویر باشد.",
            'image.max' => "حداکثر حجم فایل 2 مگابایت است.",
            'alt.required' => "برچسب تصویر الزامی است.",
            'alt.max' => "طول برچسب باید کمتر از ۲۵۵ کاراکتر باشد.",
        ];
    }
}
