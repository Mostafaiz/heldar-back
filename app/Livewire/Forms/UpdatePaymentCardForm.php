<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdatePaymentCardForm extends Form
{
    public ?int $id;
    public string $ownerName = '';
    public string $bankName = '';
    public string $cardNumber = '';
    public string $IBANnumber = '';

    public function rules()
    {
        return [
            'id' => ['required'],
            'ownerName' => ['required', 'string', 'max:255'],
            'bankName' => ['required', 'string', 'max:255'],
            'cardNumber' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('payment_cards', 'card_number')->ignore($this->id),
            ],
            'IBANnumber' => [
                'required',
                'string',
                'digits:24',
                Rule::unique('payment_cards', 'iban_number')->ignore($this->id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'ownerName.required' => 'نام دارنده کارت الزامی است.',
            'ownerName.string'   => 'نام دارنده کارت معتبر نیست.',
            'ownerName.max'      => 'نام دارنده کارت نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',

            'bankName.required' => 'نام بانک الزامی است.',
            'bankName.string'   => 'نام بانک معتبر نیست.',
            'bankName.max'      => 'نام بانک نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',

            'cardNumber.required' => 'شماره کارت الزامی است.',
            'cardNumber.string'   => 'شماره کارت معتبر نیست.',
            'cardNumber.digits'   => 'شماره کارت باید 16 رقم باشد.',
            'cardNumber.unique'   => 'این شماره کارت قبلاً ثبت شده است.',

            'IBANnumber.required' => 'شماره شبا الزامی است.',
            'IBANnumber.string'   => 'شماره شبا معتبر نیست.',
            'IBANnumber.digits'   => 'شماره شبا باید 24 رقم باشد.',
            'IBANnumber.unique'   => 'این شماره شبا قبلاً ثبت شده است.',
        ];
    }

    public function setData($data)
    {
        $this->id = $data->id;
        $this->ownerName = $data->owner_name;
        $this->bankName = $data->bank_name;
        $this->cardNumber = $data->card_number;
        $this->IBANnumber = $data->iban_number;
    }

    public function cleanNumber()
    {
        if (!empty($this->cardNumber)) {
            $this->cardNumber = str_replace(' ', '', $this->cardNumber);
        }
        if (!empty($this->IBANnumber)) {
            $this->IBANnumber = substr(str_replace(' ', '', $this->IBANnumber), 0, 24);
        }
    }
}
