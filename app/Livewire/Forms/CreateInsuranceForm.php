<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateInsuranceForm extends Form
{
    public string $name = '';
    public string $provider = '';
    public ?string $insuranceCode = null;
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
            'insuranceCode' => [
                'nullable',
                'string',
                Rule::unique('insurances', 'insurance_code'),
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
            'name.required' => 'نام بیمه الزامی است.',
            'name.max' => 'نام بیمه باید کمتر از 255 کاراکتر باشد.',
            'name.unique' => 'نام بیمه نمی‌تواند تکراری باشد.',
            'provider.required' => 'نام ارائه‌دهنده بیمه الزامی است.',
            'provider.max' => 'نام ارائه‌دهنده بیمه باید کمتر از 255 کاراکتر باشد.',
            'insuranceCode.unique' => 'کد بیمه‌نامه نمی‌تواند تکراری باشد.',
            'duration.required' => 'مدت زمان بیمه الزامی است.',
            'duration.integer' => 'مدت زمان بیمه معتبر نیست.',
            'duration.min' => 'مدت زمان بیمه باید حداقل 1 ماه باشد.',
            'description.string' => 'توضیحات بیمه معتبر نیست.',
            'price.required' => 'هزینه بیمه الزامی است.',
            'price.integer' => 'هزینه بیمه معتبر نیست.',
            'price.min' => 'هزینه بیمه نمی‌تواند منفی باشد.',
        ];
    }

    public function normalize()
    {
        $this->price = convert_numbers_to_english($this->price);
    }
}