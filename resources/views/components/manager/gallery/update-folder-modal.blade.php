<div class="back-cover expand" x-show="$wire.visible" x-transition.opacity x-cloak
    x-on:keydown.escape.window="$dispatch('hide-update-folder-modal')">
    <form class="create-folder-modal-container" x-on:click.outside="$dispatch('hide-update-folder-modal')"
        wire:submit="update" x-data="{ visible: @entangle('visible') }" x-init="$watch('visible', value => {
            if (value) $nextTick(() => $refs.nameInput.select());
        })">
        <h1 class="title">ویرایش نام پوشه</h1>
        <x-blade.manager.input-text title="نام پوشه" name="form.name" x-ref="nameInput" />
        <div class="buttons-container">
            <x-blade.manager.text-button value="انصراف" wire:click="dispatch('hide-update-folder-modal')" />
            <x-blade.manager.filled-button type="submit" value="تایید" target="update" />
        </div>
    </form>
</div>