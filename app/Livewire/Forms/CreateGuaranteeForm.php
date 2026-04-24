<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateGuaranteeForm extends Form
{
    public string $name = '';
    public string $provider = '';
    public ?string $serial = null;
    public ?int $duration;
    public ?string $description = null;
    public $price;

    public function rules(
    ) {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'provider' => [
                'required',
                'string',
                'max:255',
            ],
            'serial' => [
                'nullable',
                'string',
                Rule::unique('guarantees', 'serial'),
            ],
            'duration' => [
                'required',
                'integer',
                'min:1',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام گارانتی الزامی است.',
            'name.max' => 'نام گارانتی باید کمتر از 255 کاراکتر باشد.',
            'name.unique' => 'نام گارانتی نمی‌تواند تکراری باشد.',
            'provider.required' => 'نام ارائه‌دهنده گارانتی الزامی است.',
            'provider.max' => 'نام ارائه‌دهنده گارانتی باید کمتر از 255 کاراکتر باشد.',
            'serial.unique' => 'شماره سریال گارانتی نمی‌تواند تکراری باشد.',
            'duration.required' => 'مدت زمان گارانتی الزامی است.',
            'duration.integer' => 'مدت زمان گارانتی معتبر نیست.',
            'duration.min' => 'مدت زمان گارانتی باید حداقل 1 ماه باشد.',
            'description.string' => 'توضیحات گارانتی معتبر نیست.',
            'price.required' => 'هزینه گارانتی الزامی است.',
            'price.integer' => 'هزینه گارانتی معتبر نیست.',
            'price.min' => 'هزینه گارانتی نمی‌تواند منفی باشد.',
        ];
    }

    public function normalize()
    {
        $this->price = convert_numbers_to_english($this->price);
    }
}