<?php

namespace App\Livewire\Forms;

use App\Models\AttributeGroup;
use Livewire\Form;

class UpdateAttributeGroupForm extends Form
{

    public AttributeGroup $attributeGroup;
    public string $name = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
        ];
    }

    public function setForm(AttributeGroup $attributeGroup)
    {
        $this->attributeGroup = $attributeGroup;
        $this->name = $attributeGroup->name;
    }

    public function isDirty()
    {
        return $this->name !== $this->attributeGroup->name;
    }
}
