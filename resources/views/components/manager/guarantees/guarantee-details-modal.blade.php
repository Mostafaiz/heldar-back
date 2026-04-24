@props([])

<div class="back-cover" x-show="showGuaranteeDetailsModal" x-transition.opacity x-cloak>
    <div class="w-90 h-fit max-h-[calc(100vh-100px)] overflow-auto flex flex-col bg-white p-5 gap-5 rounded-2xl z-20 font-[shabnam]"
        x-data="{
            hideModal() {
                showGuaranteeDetailsModal = false;
                $wire.resetData()
            }
        }" x-on:click.outside="hideModal()" x-on:success.window="hideModal()">
        <h2 class="flex items-center w-full gap-3 h-auto m-0 p-0 font-[500] font-[shabnam] text-gray-800 text-2xl">
            جزئیات گارانتی
        </h2>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-shield-halved"></i>
            نام گارانتی:
            <span class="text-black">
                {{ $guarantee->name ?? '-' }}
            </span>
        </div>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-building"></i>
            نام ارائه دهنده:
            <span class="text-black">
                {{ $guarantee->provider ?? '-' }}
            </span>
        </div>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-barcode"></i>
            سریال:
            <span class="text-black">
                {{ $guarantee->serial ?? '-' }}
            </span>
        </div>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-clock"></i>
            مدت زمان:
            <span class="text-black">
                {{ $guarantee->duration ?? '-' }}
                ماهه
            </span>
        </div>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-coins"></i>
            هزینه:
            <span class="text-black">
                {{ $guarantee->price ?? '-' }}
                تومان
            </span>
        </div>
        <div class="w-full flex flex-col justify-center gap-2 text-gray-500">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-newspaper"></i>
                توضیحات:
            </div>
            <p class="text-black">
                {{ $guarantee->description ?? '-' }}
            </p>
        </div>
        <div class="w-full flex items-center gap-2 text-gray-500">
            <i class="fa-solid fa-calendar"></i>
            تاریخ افزودن:
            <p class="text-black">
                {{ jalali($guarantee->createdAt ?? '-', 'H:i - Y/m/d') }}
            </p>
        </div>
        <x-blade.manager.filled-button value="بستن" class="shrink-0" x-on:click="hideModal()" />
    </div>
</div>
