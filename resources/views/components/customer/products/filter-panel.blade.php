@props(['categories', 'brands', 'colors', 'sizes', 'filters', 'minPrice', 'maxPrice'])

<div class="lg:w-80 h-full w-full px-5 pb-3 lg:pt-6 pt-2 bg-surface border border-gray-100 shadow shrink-0 rounded-xl fixed bottom-0 right-0 z-10 lg:z-1 flex flex-col lg:gap-2 lg:h-fit overflow-auto lg:static lg:top-0 lg:right-0"
    id="filter-panel" x-bind:class="{ '-bottom-full!': !showFilterPanel }" x-cloak>
    <div class="flex lg:justify-between gap-5 items-center lg:static sticky shrink-0 top-0 bg-surface lg:h-fit h-15">
        <i class="fa-solid fa-xmark text-xl text-gray-600 lg:hidden!" x-on:click="showFilterPanel = false"></i>
        <h1 class="text-xl text-gray-800 font-[500] flex items-center gap-3">
            فیلتر محصولات
            <i class="fa-solid fa-spinner text-base text-primary animate-spin" wire:loading
                wire:loading="loadAllProducts"></i>
        </h1>
        <button class="text-primary text-sm cursor-pointer invisible lg:visible" x-show="$wire.clearFilterButton"
            wire:click="resetFilters" x-cloak>
            حذف فیلترها
        </button>
    </div>

    <div class="w-full flex flex-col grow-0 gap-1 pb-2 shrink-1 lg:pb-0 h-full overflow-auto">
        <div class="w-full flex flex-col overflow-hidden flex-nowrap shrink-0" x-data="{ opened: false }" x-cloak
            x-bind:class="{ 'h-12': !opened }">
            <button class="w-full h-12 grow-0 flex items-center justify-between cursor-pointer shrink-0"
                x-on:click="opened = !opened">
                <span class="flex gap-3 flex-nowrap items-center">
                    دسته‌بندی
                    @if ($filters['category'])
                        <div class="size-5 rounded-full bg-primary font-shabnam text-white text-sm font-[500]">
                            {{ count($filters['category']) }}
                        </div>
                    @endif
                </span>
                <i class="fa-solid fa-angle-down text-sm text-gray-500" x-bind:class="{ 'fa-angle-up': opened }"></i>
            </button>
            <div class="w-full h-auto max-h-100 flex flex-col overflow-scroll pl-4">
                @foreach ($categories as $category)
                    <x-customer.categories.filter-panel-category-row :category="$category" :filters="$filters" />
                @endforeach
            </div>
        </div>

        {{--
        <hr class="text-gray-200">

        <div class="w-full flex flex-col pb-5 overflow-hidden flex-nowrap shrink-0" x-data="{ opened: false }" x-cloak
            x-bind:class="{ 'h-12': !opened }">
            <button class="w-full h-12 flex items-center justify-between cursor-pointer shrink-0"
                x-on:click="opened = !opened">
                <span class="flex gap-3 flex-nowrap items-center">
                    برند
                    @if ($filters['brand'])
                    <div class="size-5 rounded-full bg-primary font-shabnam text-white text-sm font-[500]">
                        {{ count($filters['brand']) }}
                    </div>
                    @endif
                </span>
                <i class="fa-solid fa-angle-down text-sm text-gray-500" x-bind:class="{ 'fa-angle-up': opened }"></i>
            </button>
            <div class="w-full h-auto max-h-100 flex flex-col gap-2 overflow-scroll">
                @foreach ($brands as $brand)
                <label class="flex gap-5 items-center cursor-pointer h-12 shrink-0">
                    <input type="checkbox" value="{{ $brand }}"
                        class="size-4.5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                        wire:model.live="filters.brand">
                    <span>{{ $brand }}</span>
                </label>
                @endforeach
            </div>
        </div> --}}

        {{--
        <hr class="text-gray-200">

        <div class="w-full flex flex-col pb-5 overflow-hidden flex-nowrap gap-5 shrink-0" x-data="{ opened: false }"
            x-cloak x-bind:class="{ 'h-12': !opened }">
            <button class="w-full h-12 flex items-center justify-between cursor-pointer shrink-0"
                x-on:click="opened = !opened">
                <span class="flex gap-3 flex-nowrap items-center">
                    محدوده قیمت
                    @if ($filters['minPrice'] != $minPrice || $filters['maxPrice'] != $maxPrice)
                    <div class="size-5 rounded-full bg-primary font-shabnam text-white text-sm font-[500]">
                        {{ count(array_filter([$filters['minPrice'] != $minPrice, $filters['maxPrice'] != $maxPrice]))
                        }}
                    </div>
                    @endif
                </span>
                <i class="fa-solid fa-angle-down text-sm text-gray-500" x-bind:class="{ 'fa-angle-up': opened }"></i>
            </button>
            <div class="range_container gap-10" wire:ignore>
                <div class="w-full flex flex-col font-shabnam text-xl font-[500] gap-5">
                    <div class="w-ful flex flex-nowrap items-center justify-between gap-4">
                        <span class="text-neutral-dark">از</span>
                        <div class="flex flex-nowrap items-center gap-3">
                            <input type="text"
                                class="font-[700] text-4xl text-accent-dark border-b border-gray-200 w-full shrink-1 outline-none focus:border-accent-dark"
                                value="{{ $filters['minPrice'] }}" dir="ltr" id="fromInput" disabled>
                            <span class="text-neutral-dark text-base">تومان</span>
                        </div>
                    </div>
                    <div class="w-ful flex flex-nowrap items-center justify-between gap-4">
                        <span class="text-neutral-dark">تا</span>
                        <div class="flex flex-nowrap items-center gap-3">
                            <input type="text"
                                class="font-[700] text-4xl text-accent-dark border-b border-gray-200 w-full shrink-1 outline-none focus:border-accent-dark"
                                value="{{ $filters['maxPrice'] }}" dir="ltr" id="toInput" disabled>
                            <span class="text-neutral-dark text-base">تومان</span>
                        </div>
                    </div>
                </div>
                <div class="sliders_control flex">
                    <div class="w-full h-1.5 bg-accent absolute -top-0.5 rounded-full" id="test"></div>
                    <input id="fromSlider" type="range" value="{{ $filters['minPrice'] }}" min="{{ $minPrice }}"
                        max="{{ $maxPrice }}" wire:model.live.debounce.500ms="filters.minPrice" />
                    <input id="toSlider" type="range" value="{{ $filters['maxPrice'] }}" min="{{ $minPrice }}"
                        max="{{ $maxPrice }}" wire:model.live.debounce.500ms="filters.maxPrice" />
                    <div
                        class="w-full flex flex-no wrap items-center justify-between font-shabnam text-neutral text-sm">
                        <span>ارزان‌ترین</span>
                        <span>گران‌ترین</span>
                    </div>
                </div>
            </div>
        </div> --}}

        <hr class="text-gray-200">

        <div class="w-full flex flex-col pb-5 overflow-hidden flex-nowrap shrink-0" x-data="{ opened: false }" x-cloak
            x-bind:class="{ 'h-12': !opened }">
            <button class="w-full h-12 flex items-center justify-between cursor-pointer shrink-0"
                x-on:click="opened = !opened">
                <span class="flex gap-3 flex-nowrap items-center">
                    رنگ
                    @if ($filters['color'])
                        <div class="size-5 rounded-full bg-primary font-shabnam text-white text-sm font-[500]">
                            1
                        </div>
                    @endif
                </span>
                <i class="fa-solid fa-angle-down text-sm text-gray-500" x-bind:class="{ 'fa-angle-up': opened }"></i>
            </button>
            <div class="w-full h-auto max-h-100 flex flex-col gap-2 overflow-scroll pl-4">
                <label class="flex gap-5 items-center cursor-pointer h-12 shrink-0">
                    <input type="radio" value="" name="color"
                        class="size-4.5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                        wire:model.live="filters.color">
                    <span>همه رنگ‌ها</span>
                </label>
                @foreach ($colors as $color)
                    <label class="flex gap-5 items-center cursor-pointer h-12 shrink-0">
                        <div class="size-4.5 rounded-full"></div>
                        <input type="radio" value="{{ $color['id'] }}" name="color"
                            class="size-4.5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                            wire:model.live="filters.color">
                        <span>{{ $color['name'] }}</span>
                        <span class="size-5 rounded-full mr-auto outline outline-neutral"
                            x-bind:style="'background-color: {{ $color['code'] }}'"></span>
                    </label>
                @endforeach
            </div>
        </div>

        {{--
        <hr class="text-gray-200"> --}}

        {{-- <div class="w-full flex flex-col pb-5 overflow-hidden flex-nowrap shrink-0" x-data="{ opened: false }"
            x-cloak x-bind:class="{ 'h-12': !opened }">
            <button class="w-full h-12 flex items-center justify-between cursor-pointer shrink-0"
                x-on:click="opened = !opened">
                <span class="flex gap-3 flex-nowrap items-center">
                    سایز
                    @if ($filters['size'])
                    <div class="size-5 rounded-full bg-primary font-shabnam text-white text-sm font-[500]">
                        {{ count($filters['size']) }}
                    </div>
                    @endif
                </span>
                <i class="fa-solid fa-angle-down text-sm text-gray-500" x-bind:class="{ 'fa-angle-up': opened }"></i>
            </button>
            <div class="w-full h-auto max-h-100 flex flex-col gap-2 overflow-scroll">
                @foreach ($sizes as $size)
                <label class="flex gap-5 items-center cursor-pointer h-12 shrink-0">
                    <input type="checkbox" value="{{ $size['id'] }}"
                        class="size-4.5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                        wire:model.live="filters.size">
                    <span>{{ $size['name'] }}</span>
                </label>
                @endforeach
            </div>
        </div> --}}

        <hr class="text-gray-200">

        <div class="flex justify-between items-center text-right h-13 shrink-0">
            <span>فقط محصولات با تخفیف</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" wire:model.live="filters.discount">
                <div class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-primary transition-colors"></div>
                <div
                    class="absolute left-0.5 top-0.5 size-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform">
                </div>
            </label>
        </div>

        <hr class="text-gray-200">

        <div class="flex justify-between items-center text-right h-13 shrink-0">
            <span>فقط محصولات موجود</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" wire:model.live="filters.quantity">
                <div class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-primary transition-colors"></div>
                <div
                    class="absolute left-0.5 top-0.5 size-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform">
                </div>
            </label>
        </div>
    </div>

    <div class="w-full h-15 flex flex-nowrap gap-3 lg:hidden shrink-0 bg-surface items-center border-t border-gray-100">
        <button
            class="bg-primary py-3 px-5 h-10 rounded-lg text-surface w-3/5 text-sm flex items-center justify-center disabled:bg-primary-light"
            wire:loading.attr="disabled" x-on:click="showFilterPanel = false">
            <span wire:loading.remove>مشاهده محصولات</span>
            <i class="fa-solid fa-spinner animate-spin size-fit" wire:loading></i>
        </button>
        <button
            class="bg-surface py-3 px-5 h-10 rounded-lg text-error border border-error w-2/5 text-sm flex items-center justify-center disabled:bg-gray-300 disabled:border-none disabled:text-surface"
            x-bind:disabled="!$wire.clearFilterButton" wire:click="resetFilters">
            حذف فیلترها
        </button>
    </div>
</div>

@push('styles')
    @vite('resources/css/customer/dual-range.scss')
@endpush

@vite(['resources/js/customer/filter-panel.js'])
