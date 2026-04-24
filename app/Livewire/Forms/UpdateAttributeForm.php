<?php

namespace App\Livewire\Forms;

use App\Models\Attribute;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateAttributeForm extends Form
{
    public Attribute $attribute;

    public string $key = '';

    public function rules()
    {
        return [
            'key' => [
                'required',
                Rule::unique('attributes')
                    ->withoutTrashed()
                    ->where(
                        'attribute_group_id',
                        $this->attribute->attribute_group_id
                    )
            ],
        ];
    }
    public function setForm(Attribute $attribute)
    {
        $this->attribute = $attribute;
        $this->key = $attribute->key;
    }
    public function isDirty()
    {
        return $this->attribute->key != $this->key;
    }
}
