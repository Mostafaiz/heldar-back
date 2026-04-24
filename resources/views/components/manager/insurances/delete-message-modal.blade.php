<div class="back-cover" x-show="showDeleteInsuranceMessage" x-transition.opacity x-cloak>
    <div class="w-80 h-fit flex flex-col bg-white p-5 gap-5 rounded-2xl z-20"
        x-data="{ hideModal() { showDeleteInsuranceMessage = false; selectedInsuranceForDelete = null } }"
        x-on:click.outside="hideModal()" x-on:success.window="hideModal()">
        <h2 class="w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-red-700 text-[22px]">
            <i class="fa-solid fa-trash-can ml-3"></i>
            حذف بیمه
        </h2>
        <p class="w-full h-auto m-0 p-0 font-[shabnam] text-[17px]">
            آیا از حذف بیمه اطمینان دارید؟
        </p>
        <div class="flex flex-nowrap gap-[10px] justify-end items-center w-full">
            <x-blade.manager.text-button value="خیر" x-on:click="hideModal()" />
            <x-blade.manager.text-button value="بله" target="delete" wire:click="delete(selectedInsuranceForDelete)" />
        </div>
    </div>
</div>