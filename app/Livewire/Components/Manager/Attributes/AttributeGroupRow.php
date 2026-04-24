<?php

namespace App\Livewire\Components\Manager\Attributes;

use App\Http\Dto\Attribute\CreateAttributeDto;
use App\Http\Dto\Attribute\DeleteAttributeGroupDto;
use App\Http\Dto\Attribute\UpdateAttributeGroupDto;
use App\Livewire\Forms\CreateAttributeForm;
use App\Livewire\Forms\UpdateAttributeGroupForm;
use App\Models\AttributeGroup;
use Livewire\Attributes\On;
use Livewire\Component;

class AttributeGroupRow extends Component
{

    public AttributeGroup $attributeGroup;
    public UpdateAttributeGroupForm $updateAttributeGroupForm;
    public CreateAttributeForm $createAttributeForm;
    public bool $showAddAttribute = false;
    public bool $editingAttributeGroup = false;

    public function mount()
    {
        $this->updateAttributeGroupForm->setForm($this->attributeGroup);
        $this->createAttributeForm->setForm($this->attributeGroup);
    }

    #[On('refresh-product-attributes:{attributeGroup.id}')]
    public function refreshProductAttribute()
    {
        $this->attributeGroup->refresh();
    }
    public function deleteAttributeGroup()
    {
        $attributeService = app('AttributeService');
        $dto = DeleteAttributeGroupDto::makeDto($this->attributeGroup);
        $attributeService->deleteAttributeGroup($dto);
        $this->dispatch('get-all-attributes');
        $this->dispatch('success', message: "ویژگی‌ها با موفقیت حذف شدند.");
    }

    public function addAttribute()
    {
        try {
            if (!$this->showAddAttribute)
                return;
            $validated = $this->createAttributeForm->validate();
            $attributeService = app('AttributeService');
            $dto = CreateAttributeDto::makeDto($this->attributeGroup, $validated);
            $attributeService->createAttribute($dto);

            $this->createAttributeForm->resetExcept('attributeGroup');
            $this->showAddAttribute = false;
            $this->dispatch('success', message: 'ویژگی با موفقیت افزوده شد!');
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }
    }

    public function updateAttributeGroup()
    {
        if (!$this->updateAttributeGroupForm->isDirty())
            return;

        $validated = $this->updateAttributeGroupForm->validate();

        $attributeService = app('AttributeService');
        $dto = UpdateAttributeGroupDto::makeDto($this->attributeGroup, $validated);
        $attributeService->updateAttributeGroup($dto);
        $this->dispatch('success', message: "نام مجموعه ویژگی‌ها با موفقیت ویرایش شد.");

        $this->editingAttributeGroup = false;
    }

    public function render()
    {
        return view('components.manager.attributes.attribute-group-row');
    }
}
