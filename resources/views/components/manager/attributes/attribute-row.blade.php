<form wire:submit="update" class="attribute-row">
    <button type="button" class="edit" x-show="!$wire.editing" x-on:click="
        $wire.editing = true;
        $focusTo('updateAttributeInput')
    "><i class="fa-solid fa-edit text-sm    "></i></button>
    <div class="actions" x-show="$wire.editing">
        <button type="button" class="cancel" x-on:click="$wire.editing = false">
            <i class="fa-solid fa-times"></i>
        </button>
        <button class="check"><i class="fa-solid fa-check"></i></button>
    </div>
    <div class="key-name" x-show="!$wire.editing" x-on:dblclick="
        $wire.editing = true;
        $focusTo('updateAttributeInput');
    ">
        {{ $attribute->key }}
    </div>
    <div x-show="$wire.editing">
        <x-blade.manager.input-text title="ویژگی" wire:model="form.key" x-ref="updateAttributeInput" />
    </div>
    <button type="button" class="remove" wire:confirm="آیا از حذف این ویژگی مطمئنید؟" wire:click="delete"><i
            class="fa-solid fa-trash-can text-sm"></i></button>
</form>