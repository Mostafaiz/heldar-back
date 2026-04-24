@props(['class' => ''])

<div @class(['back-cover', $class]) x-show="showDeleteProductMessage" x-transition.opacity x-cloak>
    <div class="w-100 h-fit flex flex-col bg-white p-5 gap-5 rounded-2xl z-20"
        x-data="{ hideModal() { showDeleteProductMessage = false; setTimeOut(() => selectedProductForDelete = null, 1500) } }"
        x-on:click.outside="hideModal()" x-on:success.window="hideModal()">
        <h2 class="w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-red-700 text-[22px]">
            <i class="fa-solid fa-trash-can ml-3"></i>
            حذف محصول
        </h2>
        <p class="w-full h-auto m-0 p-0 font-[shabnam] text-[17px]">
            آیا از حذف محصول اطمینان دارید؟
        </p>
        <p class="w-full truncate text-sm text-gray-600 font-[shabnam] -mt-2" x-text="selectedProductForDelete[1]"></p>
        <div class="flex flex-nowrap gap-[10px] justify-end items-center w-full">
            <x-blade.manager.text-button value="خیر" x-on:click="hideModal()" />
            <x-blade.manager.text-button value="بله" target="delete" wire:click="delete(selectedProductForDelete[0])" />
        </div>
    </div>
</div>