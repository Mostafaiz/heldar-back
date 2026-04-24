<?php

namespace App\Livewire\Forms;

use App\Models\AttributeGroup;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Form;

class CreateAttributeForm extends Form
{
    #[Locked]
    public AttributeGroup $attributeGroup;

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
                        $this->attributeGroup->id
                    )
            ],
        ];
    }

    public function setForm(AttributeGroup $attributeGroup)
    {
        $this->attributeGroup = $attributeGroup;
    }
}