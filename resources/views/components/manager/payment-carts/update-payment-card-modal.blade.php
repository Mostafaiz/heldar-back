<div class="back-cover" x-show="showEditPaymentCardModal" x-transition.opacity x-cloak>
    <div class="w-100 h-fit max-h-[calc(100vh-100px)] overflow-auto flex flex-col bg-white p-5 gap-5 rounded-2xl z-20"
        x-data="{
            hideModal() {
                showEditPaymentCardModal = false;
                $wire.resetData()
            }
        }" x-on:click.outside="hideModal()" x-on:success.window="hideModal()"
        x-on:keydown.espace="hideModal()">
        <h2 class="flex w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-gray-800 text-[22px]">
            ویرایش کارت اعتباری
        </h2>
        <form wire:submit.prevent="update" class="flex flex-col w-full gap-[20px] items-end!">
            <div class="w-full flex flex-nowrap gap-5">
                <x-blade.manager.input-text title="نام دارنده کارت" name="form.ownerName" required />
                <x-blade.manager.input-text title="نام بانک" name="form.bankName" required />
            </div>
            <x-blade.manager.input-text title="شماره کارت" name="form.cardNumber" wire:ignore x-init="cardNumberFormat($el)"
                dir="ltr" required />
            <x-blade.manager.input-text title="شماره شبا" name="form.IBANnumber" wire:ignore x-init="IBANnumberFormat($el)"
                dir="ltr" required label="IR" />
            <div class="flex gap-2 justify-end">
                <x-blade.manager.text-button value="انصراف" x-on:click="hideModal" />
                <x-blade.manager.filled-button type="submit" value="ثبت تغییرات" target="update" />
            </div>
        </form>
    </div>
</div>
