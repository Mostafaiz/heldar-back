<?php

namespace App\Livewire\Forms;

use App\Http\Dto\Response\Customer\User\Address;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateAddressForm extends Form
{
    public ?int $id = 0;
    public ?string $name;
    public ?int $provinceId = null;
    public ?int $cityId = null;
    public ?string $zipcode;
    public ?string $fullAddress;

    public function rules()
    {
        return [
            'id' => [
                'nullable',
                'integer',
                Rule::exists('addresses', 'id'),
            ],
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
            'id.exists' => 'آدرس مورد نظر یافت نشد.',
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

    public function setData(Address $dto): void
    {
        $this->id = $dto->id;
        $this->name = $dto->name;
        $this->provinceId = $dto->provinceId;
        $this->cityId = $dto->cityId;
        $this->fullAddress = $dto->fullAddress;
        $this->zipcode = $dto->zipcode;
    }
}
