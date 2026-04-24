<?php

namespace App\Livewire\Forms;

use App\Http\Dto\Response\Guarantee as GuaranteeDto;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateGuaranteeForm extends Form
{
    public ?int $id = 0;
    public string $name = '';
    public string $provider = '';
    public ?string $serial = null;
    public ?int $duration;
    public ?string $description = null;
    public $price;

    public function rules()
    {
        return [
            'id' => [
                'nullable',
                'integer',
                Rule::exists('guarantees', 'id'),
            ],
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
                Rule::unique('guarantees', 'serial')->ignore($this->id),
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
            'id.exists' => 'گارانتی مورد نظر یافت نشد.',
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

    public function setData(GuaranteeDto $guarantee): void
    {
        $this->id = $guarantee->id;
        $this->name = $guarantee->name;
        $this->provider = $guarantee->provider;
        $this->serial = $guarantee->serial;
        $this->duration = $guarantee->duration;
        $this->description = $guarantee->description;
        $this->price = $guarantee->price;
    }
}