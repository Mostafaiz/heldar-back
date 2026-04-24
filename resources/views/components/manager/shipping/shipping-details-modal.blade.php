<div class="back-cover" x-show="showShippingDetailsModal" x-transition.opacity x-cloak>
    <div class="w-90 h-fit max-h-[calc(100vh-100px)] overflow-auto flex flex-col bg-white p-5 gap-5 rounded-2xl z-20 font-[shabnam]"
        x-data="{ hideModal() { showShippingDetailsModal = false;
                $wire.resetData() } }" x-on:click.outside="hideModal()" x-on:success.window="hideModal()">
        <h2 class="flex items-center w-full gap-3 h-auto m-0 p-0 font-[500] font-[shabnam] text-gray-800 text-2xl">
            جزئیات پست ارسال
            {{-- <i class="fa-solid fa-spinner loading-icon text-[16px]" x-show="$wire.loading"></i> --}}
        </h2>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-shield-halved"></i>
            نام پست:
            <span class="text-black">
                {{ $shipping->name ?? '-' }}
            </span>
        </div>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-coins"></i>
            هزینه:
            <span class="text-black">
                {{ number_format($shipping?->price) ?? '-' }}
                تومان
            </span>
        </div>
        <div class="w-full flex flex-col justify-center gap-2 text-gray-500">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-newspaper"></i>
                توضیحات:
            </div>
            <p class="text-black">
                {{ $shipping->description ?? '-' }}
            </p>
        </div>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-calendar"></i>
            تاریخ افزودن:
            <p class="text-black">
                {{ jalali($shipping->createdAt ?? '-', 'H:i - Y/m/d') }}
            </p>
        </div>
        <x-blade.manager.filled-button value="بستن" class="shrink-0" x-on:click="hideModal()" />
    </div>
</div>
