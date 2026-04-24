<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SearchProductsForm extends Form
{
    #[Validate(['required', 'string', 'min:2'])]
    public string $keyword;

    public function messages()
    {
        return [
            'keyword.required' => 'متن جستجو الزامی می باشد.',
            'keyword.string' => 'متن جستجو معتبر نمی باشد.',
            'keyword.min' => ' حداقل 2 کاراکتر وارد کنید.'
        ];
    }
}
