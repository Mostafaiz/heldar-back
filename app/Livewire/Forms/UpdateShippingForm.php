<?php

namespace App\Livewire\Forms;

use App\Http\Dto\Response\Shipping as ShippingDto;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateShippingForm extends Form
{
    public ?int $id = 0;
    public string $name = '';
    public $price;
    public ?string $description = null;
    public bool $status = true;

    public function rules()
    {
        return [
            'id' => [
                'nullable',
                'integer',
                Rule::exists('shippings', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'پست مورد نظر یافت نشد.',
            'name.required' => 'نام پست الزامی است.',
            'name.max' => 'نام پست باید کمتر از 255 کاراکتر باشد.',
            'name.unique' => 'نام پست نمی‌تواند تکراری باشد.',
            'description.string' => 'توضیحات پست معتبر نیست.',
            'price.required' => 'هزینه پست الزامی است.',
            'price.integer' => 'هزینه پست معتبر نیست.',
            'price.min' => 'هزینه پست نمی‌تواند منفی باشد.',
        ];
    }

    public function normalize()
    {
        $this->price = convert_numbers_to_english($this->price);
    }

    public function setData(ShippingDto $guarantee): void
    {
        $this->id = $guarantee->id;
        $this->name = $guarantee->name;
        $this->description = $guarantee->description;
        $this->price = $guarantee->price;
    }
}
