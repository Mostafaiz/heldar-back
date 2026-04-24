@props([])

<div class="back-cover" x-show="showNewAdminModal" x-on:success.window="hideModal"
    x-data="{ hideModal () { showNewAdminModal = false; $wire.resetData() } }" x-init="$watch('showNewAdminModal', value => {
        if (value) $nextTick(() => $refs.phoneInput.focus());
    })" x-transition.opacity x-cloak>
    <form class="w-100 h-fit bg-white rounded-2xl font-[shabnam] flex flex-col gap-5 p-5 box-border flex-nowrap"
        wire:submit.prevent="addAdmin" x-on:click.outside="hideModal" x-on:keydown.escape="hideModal">
        <h1 class="p-0 m-0 text-2xl font-[500]">افزودن مدیر</h1>
        <x-blade.manager.input-text title="شماره تلفن" name="form.phone" dir="ltr" x-ref="phoneInput" />
        <div class="flex justify-end gap-2 w-full">
            <x-blade.manager.text-button value="انصراف" x-on:click="hideModal" />
            <x-blade.manager.filled-button value="تایید" type="submit" target="addAdmin" />
        </div>
    </form>
</div>