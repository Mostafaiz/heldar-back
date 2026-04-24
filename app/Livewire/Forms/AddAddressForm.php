<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class AddAddressForm extends Form
{
    public ?string $name;
    public ?int $provinceId = null;
    public ?int $cityId = null;
    public ?string $zipcode = null;
    public ?string $fullAddress;

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'provinceId' => [
                'required',
                Rule::exists('provinces', 'id'),
            ],
            'cityId' => [
                'required',
                Rule::exists('cities', 'id'),
            ],
            'zipcode' => [
                'required',
                'integer',
                'digits:10'
            ],
            'fullAddress' => [
                'required',
                'string',
                'max:255',
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام آدرس الزامی می‌باشد.',
            'provinceId.required' => 'استان الزامی می‌باشد.',
            'provinceId.exists' => 'استان معتبر نمی‌باشد.',
            'cityId.required' => 'شهر الزامی می‌باشد.',
            'cityId.exists' => 'شهر معتبر نمی‌باشد.',
            'zipcode.required' => 'کد پستی الزامی می‌باشد.',
            'zipcode.integer' => 'کد پستی معتبر نمی‌باشد.',
            'zipcode.digits' => 'کد پستی باید 10 رقم باشد.',
            'fullAddress.required' => 'آدرس کامل الزامی می‌باشد.',
            'fullAddress.string' => 'آدرس معتبر نمی‌باشد.',
            'fullAddress.max' => 'آدرس طولانی است.',
        ];
    }

    public function normalize()
    {
        $this->zipcode = convert_numbers_to_english($this->zipcode);
    }
}
