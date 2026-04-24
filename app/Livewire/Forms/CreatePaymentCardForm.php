<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreatePaymentCardForm extends Form
{
    public string $ownerName = '';
    public string $bankName = '';
    public string $cardNumber = '';
    public string $IBANnumber = '';

    public function rules()
    {
        return [
            'ownerName' => ['required', 'string', 'max:255'],
            'bankName' => ['required', 'string', 'max:255'],
            'cardNumber' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('payment_cards', 'card_number'),
            ],
            'IBANnumber' => [
                'required',
                'string',
                'digits:24',
                Rule::unique('payment_cards', 'iban_number'),
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

    public function cleanNumber()
    {
        if (!empty($this->cardNumber)) {
            $this->cardNumber = substr(str_replace(' ', '', $this->cardNumber), 0, 16);
        }
        if (!empty($this->IBANnumber)) {
            $this->IBANnumber = substr(str_replace(' ', '', $this->IBANnumber), 0, 24);
        }
    }
}
