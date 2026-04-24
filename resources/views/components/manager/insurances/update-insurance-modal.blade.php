<div class="back-cover" x-show="showEditInsuranceModal" x-transition.opacity x-cloak>
    <div class="w-90 h-fit max-h-[calc(100vh-100px)] overflow-auto flex flex-col bg-white p-5 gap-5 rounded-2xl z-20"
        x-data="{
            hideModal() {
                showEditInsuranceModal = false;
                $wire.resetData()
            }
        }" x-on:click.outside="hideModal()" x-on:success.window="hideModal()"
        x-on:keydown.espace="hideModal">
        <h2 class="flex w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-gray-800 text-[22px]">
            ویرایش بیمه
        </h2>
        <form wire:submit.prevent="update()" class="flex flex-col w-full gap-[20px]">
            <x-blade.manager.input-text title="نام بیمه" name="form.name" />
            <x-blade.manager.input-text title="نام ارائه دهنده" name="form.provider" />
            <x-blade.manager.input-text title="سریال بیمه" name="form.insuranceCode" />
            <div class="w-full h-fit flex items-center gap-2.5 font-[shabnam] font-light text-lg">
                <span class="shrink-0">
                    مدت بیمه:
                </span>
                <input type="number" x-on:change="if ($event.target.value < 0) $event.target.value = 0"
                    class="outlined-input shrink-1! grow-0! w-16!" placeholder dir="ltr" wire:model="form.duration"
                    wire:model="{{ $insurance->duration ?? '' }}" />
                <span class="shrink-0">
                    ماه
                </span>
            </div>
            @error('form.duration')
                <span class="error-message -mt-4!">
                    {{ $message }}
                </span>
            @enderror
            <x-blade.manager.input-text type="number" title="هزینه" class="hide-arrows" name="form.price"
                value="{{ $insurance->price ?? '' }}" x-on:change="if ($event.target.value < 0) $event.target.value = 0"
                dir="ltr" />
            <x-blade.manager.textarea title="توضیحات" name="form.description" class="h-20!"
                value="{{ $insurance->description ?? '' }}" />
            <div class="flex gap-2 justify-end">
                <x-blade.manager.text-button value="انصراف" x-on:click="hideModal" />
                <x-blade.manager.filled-button type="submit" value="ثبت تغییرات" target="update" />
            </div>
        </form>
    </div>
</div>
