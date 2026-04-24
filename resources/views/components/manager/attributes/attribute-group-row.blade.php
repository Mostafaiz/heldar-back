<div x-data="{ open : false }" class="products-attribute-row"
    x-bind:class="{'opened':open,'editing': $wire.editingAttributeGroup}">
    <div class="details pl-3! font-shabnam!">
        <div class="color-cell">
            <button class="open" x-on:click="open = !open"><i class="fa-solid text-sm"
                    x-bind:class="!open ? 'fa-chevron-down' : 'fa-chevron-up'"></i></button>
        </div>
        <div class="color-cell">
            <div x-show="!$wire.editingAttributeGroup" x-on:dblclick="
            $wire.editingAttributeGroup = true;
            $focusTo('attributeGroupNameInput')
            ">
                {{ $attributeGroup->name }}
            </div>
            <form wire:submit="updateAttributeGroup" class="products-attribute-editing-con" x-cloak
                x-show="$wire.editingAttributeGroup">

                <x-blade.manager.input-text title="نام مجموعه" x-ref="attributeGroupNameInput"
                    name="updateAttributeGroupForm.name" />

                <button type="button" class="close" x-on:click="$wire.editingAttributeGroup = false">
                    <i class="fa-solid fa-times"></i>
                </button>
                <button type="submit"
                    class="size-6 hover:bg-success rounded-full hover:text-white transition text-sm cursor-pointer text-neutral flex items-center justify-center shrink-0"
                    x-on:click="$wire.editingAttributeGroup = false">
                    <i class="fa-solid fa-check"></i>
                </button>
            </form>
        </div>
        <div class="color-cell">
            <button type="button" class="edit flex! items-center! justify-center! group bg-gray-100" x-on:click="
            $wire.editingAttributeGroup = true;
            $focusTo('attributeGroupNameInput')
            ">
                <i class="fa-solid fa-edit"></i>
            </button>
            <button type="button" class="check">
                <i class="fa-solid fa-check"></i>
            </button>
            <button type="button" class="xmark">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="color-cell">
            <button type="button" class="delete bg-gray-100! group hover:bg-error! flex! items-center! justify-center!"
                wire:confirm="آیا از حذف این مجموعه مطمئنید؟" wire:click="deleteAttributeGroup">
                <i class="fa-solid fa-trash-can text-neutral! group-hover:text-white!"></i>
            </button>
        </div>
    </div>
    <div class="keys-container">
        <div class="inner-keys-container">
            @foreach ($attributeGroup->attributes as $attribute)
                <livewire:components.manager.attributes.attribute-row :key="$attribute->id" :$attribute />
            @endforeach
        </div>
        <div class="add-key">
            <button class="add" x-show="!$wire.showAddAttribute" x-on:click="
                    $wire.showAddAttribute = true;
                    $focusTo('updateAttributeInput')
                ">
                <i class="fa-solid fa-plus"></i>
                <span>افزودن ویژگی</span>
            </button>
            <button class="close" x-show="$wire.showAddAttribute" x-on:click="$wire.showAddAttribute = false">
                <i class="fa-solid fa-times"></i>
            </button>
            <form wire:submit="addAttribute" x-show="$wire.showAddAttribute">
                <x-blade.manager.input-text title="ویژگی جدید" name="createAttributeForm.key"
                    x-ref="addAttributeInput" />
                <button class="submit"><i class="fa-solid fa-check"></i></button>
            </form>
        </div>
    </div>
</div>