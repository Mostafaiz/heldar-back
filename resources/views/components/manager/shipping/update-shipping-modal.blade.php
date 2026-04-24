<div class="back-cover" x-show="showEditShippingModal" x-transition.opacity x-cloak>
    <div class="w-90 h-fit max-h-[calc(100vh-100px)] overflow-auto flex flex-col bg-white p-5 gap-5 rounded-2xl z-20"
        x-data="{ hideModal() { showEditShippingModal = false;
                $wire.resetData() } }" x-on:click.outside="hideModal()" x-on:success.window="hideModal()"
        x-on:keydown.espace="hideModal()">
        <h2 class="flex w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-gray-800 text-[22px]">
            ویرایش پست ارسال
        </h2>
        <form wire:submit.prevent="update" class="flex flex-col w-full gap-[20px] items-end!">
            <x-blade.manager.input-text title="نام پست" name="form.name" required />
            <x-blade.manager.input-text title="قیمت" name="form.price" label="تومان" required />
            <x-blade.manager.textarea title="توضیحات" name="form.description" class="h-20!" />
            <div class="flex gap-2 justify-end">
                <x-blade.manager.text-button value="انصراف" x-on:click="hideModal" />
                <x-blade.manager.filled-button type="submit" value="ثبت تغییرات" target="update" />
            </div>
        </form>
    </div>
</div>
