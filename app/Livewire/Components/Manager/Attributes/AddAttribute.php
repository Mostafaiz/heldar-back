<?php

namespace App\Livewire\Components\Manager\Attributes;

use App\Http\Dto\Attribute\CreateAttributeGroupDto;
use App\Livewire\Forms\CreateAttributeGroupForm;
use Livewire\Component;

class AddAttribute extends Component
{

    public CreateAttributeGroupForm $form;

    public function addAttributeInput()
    {
        $this->form->attributes[] = '';
        $this->dispatch('focus');
    }

    public function store()
    {
        $validated = $this->form->validate();
        $attributeService = app('AttributeService');
        $dto = CreateAttributeGroupDto::makeDto($validated);
        $attributeService->createAttributeGroup($dto);
        $this->form->reset();
        $this->dispatch('get-all-attributes');
        $this->dispatch('success', message: "ویژگی‌ها با موفقیت ذخیره شدند.");
    }

    public function render()
    {
        return view('components.manager.attributes.add-attribute');
    }
}
