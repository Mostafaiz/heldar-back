<div class="back-cover fixed!" x-show="showRejectTransactionWarning" x-transition.opacity x-cloak>
    <div class="w-80 h-fit flex flex-col bg-white p-5 gap-5 rounded-2xl z-20" x-data="{
        hideModal() {
            showRejectTransactionWarning = false;
            selectedTransactionForAorR = null
        }
    }"
        x-on:click.outside="hideModal()" x-on:success.window="hideModal()">
        <h2 class="w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-red-600 text-[22px]">
            <i class="fa-solid fa-xmark-circle ml-3"></i>
            رد سفارش
        </h2>
        <p class="w-full h-auto m-0 p-0 font-[shabnam] text-[17px]">
            آیا مطمئن هستید؟
            <br>
            این عمل قابل بازگشت نیست.
        </p>
        <div class="flex flex-nowrap gap-[10px] justify-end items-center w-full">
            <x-blade.manager.text-button value="خیر" x-on:click="hideModal()" />
            <x-blade.manager.filled-button value="بله" target="rejectTransaction"
                x-on:click="$wire.rejectTransaction(selectedTransactionForAorR)" />
        </div>
    </div>
</div>
