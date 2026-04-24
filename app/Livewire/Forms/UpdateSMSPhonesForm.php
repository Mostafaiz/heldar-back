<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class UpdateSMSPhonesForm extends Form
{
    public ?string $firstSMSPhone = '';
    public ?string $secondSMSPhone = '';

    public function rules(): array
    {
        return [
            'firstSMSPhone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'secondSMSPhone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'firstSMSPhone.string' => 'شماره تلفن معتبر نیست.',
            'firstSMSPhone.max' => 'شماره تلفن بیش از حد مجاز طولانی است.',

            'secondSMSPhone.string' => 'شماره تلفن معتبر نیست.',
            'secondSMSPhone.max' => 'شماره تلفن بیش از حد مجاز طولانی است.',
        ];
    }

    public function setData(array $data)
    {
        $this->firstSMSPhone = $data['first_sms_phone'];
        $this->secondSMSPhone = $data['second_sms_phone'];
    }
}
