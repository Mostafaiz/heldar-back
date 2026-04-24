<?php

namespace App\Livewire\Components\Manager\Attributes;

use App\Http\Dto\Attribute\DeleteAttributeDto;
use App\Http\Dto\Attribute\UpdateAttributeDto;
use App\Livewire\Forms\UpdateAttributeForm;
use App\Models\Attribute;
use Livewire\Component;

class AttributeRow extends Component
{

    public Attribute $attribute;
    public UpdateAttributeForm $form;
    public bool $editing = false;

    public function mount(Attribute $attribute)
    {
        $this->form->setForm($attribute);
    }

    public function delete()
    {
        $attributeService = app('AttributeService');
        $dto = DeleteAttributeDto::makeDto($this->attribute);
        $attributeService->deleteAttribute($dto);
        $this->dispatch('refresh-product-attributes:' . $this->attribute->attribute_group_id);
        $this->dispatch('success', message: "ویژگی با موفقیت حذف شد.");
    }

    public function update()
    {
        if (!$this->form->isDirty()) {
            $this->editing = false;
            return;
        }
        $validated = $this->form->validate();

        $attributeService = app('AttributeService');
        $dto = UpdateAttributeDto::makeDto($this->attribute, $validated);
        $attributeService->updateAttribute($dto);
        $this->dispatch('success', message: "ویژگی با موفقیت ویرایش شد.");

        $this->editing = false;
    }

    public function render()
    {
        return view('components.manager.attributes.attribute-row');
    }
}
