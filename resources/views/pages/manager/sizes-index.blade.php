<div class="opened-panel flex flex-col justify-start items-start"
    x-data="{ showDeleteSizeMessage: false, selectedSizeForDelete: null }">
    <div class="inner-container">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>

        <div class="flex w-full flex-nowrap gap-[20px]">
            <x-blade.manager.section class="grow-1">
                <x-blade.manager.section-title title="افزوده شده">
                    مشخصه دیگه
                </x-blade.manager.section-title>

                <x-blade.manager.flex-column id="sizes-list-container">
                    @foreach ($sizes as $key => $size)
                        <div class="flex flex-nowrap justify-start items-center px-5 gap-3.5 font-[shabnam] border-gray-400 border-1 w-full py-3 box-border rounded-lg"
                            x-data="{ editing: false }" x-cloak :class="editing ? 'pr-2.5' : ''" :key="$size->id"
                            x-on:success.window="editing = false">
                            <div class="grow-1 shrink-1"
                                x-effect="if (editing) $nextTick(() => $refs.nameInput{{ $key }}.select())"
                                x-id="['input-text']">
                                <span x-show="!editing">{{ $size->name }}</span>
                                <div class="flex gap-3 items-center" x-show="editing">
                                    <input type="text" class="outlined-input w-60! h-7! box-border" value="{{ $size->name }}"
                                        :id="$id('input-text')" x-ref="nameInput{{ $key }}"
                                        x-on:keydown.enter="if ($refs.nameInput{{ $key }}.value != '{{ $size->name }}') $wire.update({{ $size->id }}, $refs.nameInput{{ $key }}.value); else editing = false" x-on:keydown.escape="editing = false" />
                                    <i class="fa-solid fa-spinner loading-icon text-gray-500" wire:loading wire:target="update"></i>
                                </div>
                                @if (!empty($updateForm->id) && $updateForm->id === $size->id)
                                    @error("updateForm.name")
                                        <span class="error-message" x-show="editing">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>
                            <div class="shrink-0 w-fit flex gap-3.5">
                                <x-blade.manager.icon-button icon="fa-solid fa-pen" class="text-[12px] hover:bg-blue-700!"
                                    x-on:click="editing = true" x-show="!editing" />
                                <x-blade.manager.icon-button icon="fa-solid fa-xmark" class="text-[14px] hover:bg-red-700!"
                                    x-on:click="editing = false; $wire.resetErrors(); $refs.nameInput{{ $key }}.value = '{{ $size->name }}'"
                                    x-show="editing" />
                                <x-blade.manager.icon-button icon="fa-solid fa-check"
                                    class="text-[14px] hover:bg-green-600!"
                                    x-on:click="if ($refs.nameInput{{ $key }}.value != '{{ $size->name }}') $wire.update({{ $size->id }}, $refs.nameInput{{ $key }}.value); else editing = false"
                                    x-show="editing" />
                            </div>
                            <div class="shrink-0"
                                x-on:click="showDeleteSizeMessage = true; selectedSizeForDelete = {{ $size->id }}">
                                <x-blade.manager.icon-button icon="fa-solid fa-trash-can"
                                    class="text-[13px] hover:bg-red-700!" />
                            </div>
                        </div>
                    @endforeach
                </x-blade.manager.flex-column>
            </x-blade.manager.section>

            <x-blade.manager.section class="w-[350px] h-fit sticky! top-5">
                <x-blade.manager.section-title title="افزودن">
                    نام سایز را وارد کنید
                </x-blade.manager.section-title>

                <form wire:submit.prevent="create" class="flex flex-col w-full gap-[20px] items-end!">
                    <x-blade.manager.input-text title="نام سایز" name="createForm.name" />
                    <x-blade.manager.filled-button type="submit" value="ثبت سایز" target="create" />
                </form>
            </x-blade.manager.section>
        </div>

        <div class="back-cover" x-show="showDeleteSizeMessage" x-transition.opacity x-cloak>
            <div class="w-80 h-fit flex flex-col bg-white p-5 gap-5 rounded-2xl z-20"
                x-data="{ hideModal() { showDeleteSizeMessage = false; selectedSizeForDelete = null } }"
                x-on:click.outside="hideModal()">
                <h2 class="w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-red-700 text-[22px]">
                    <i class="fa-solid fa-trash ml-3"></i>
                    حذف سایز
                </h2>
                <p class="w-full h-auto m-0 p-0 font-[shabnam] text-[17px]">
                    آیا از حذف سایز اطمینان دارید؟
                </p>
                <div class="flex flex-nowrap gap-[10px] justify-end items-center w-full">
                    <x-blade.manager.text-button value="خیر" x-on:click="hideModal()" />
                    <x-blade.manager.text-button value="بله" target="deleteFolder" x-on:click="hideModal()"
                        wire:click="delete(selectedSizeForDelete)" />
                </div>
            </div>
        </div>

    </div>
</div>