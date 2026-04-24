<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class UpdatePublicSiteConfigDataForm extends Form
{
    public ?string $phone = '';
    public ?string $address = '';
    public ?string $aboutUs = '';
    public ?string $enamadCode = '';

    public function rules(): array
    {
        return [
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'aboutUs' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'enamadCode' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.string' => 'شماره تلفن معتبر نیست.',
            'phone.max' => 'شماره تلفن بیش از حد مجاز طولانی است.',

            'address.string' => 'آدرس معتبر نیست.',
            'address.max' => 'آدرس بیش از حد مجاز طولانی است.',

            'aboutUs.string' => 'متن درباره ما معتبر نیست.',
            'aboutUs.max' => 'متن درباره ما بیش از حد مجاز طولانی است.',
        ];
    }

    public function setData(array $data)
    {
        $this->phone = $data['admin_phone'];
        $this->address = $data['admin_address'];
        $this->aboutUs = $data['about_us'];
    }
}
