<div class="back-cover expand" x-show="$wire.visible" x-transition.opacity x-cloak
    x-on:keydown.escape.window="$dispatch('hide-create-folder-modal')">
    <form class="create-folder-modal-container" x-on:click.outside="$dispatch('hide-create-folder-modal')"
        wire:submit="store" x-data="{ visible: @entangle('visible') }" x-init="$watch('visible', value => {
            if (value) $nextTick(() => $refs.nameInput.focus());
        })">
        <h1 class="title">ایجاد پوشه</h1>
        <x-blade.manager.input-text title="نام پوشه" name="form.name" x-ref="nameInput" />
        <div class="buttons-container gap-2!">
            <x-blade.manager.text-button value="لغو" wire:click="dispatch('hide-create-folder-modal')" />
            <x-blade.manager.filled-button type="submit" value="تایید" target="store" />
        </div>
    </form>
</div>