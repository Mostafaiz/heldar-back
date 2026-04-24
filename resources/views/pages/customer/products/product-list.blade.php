<div class="bg-white w-full font-shabnam text-gray-900 md:p-4 md:pt-0 md:-mt-5 lg:mt-0 lg:pt-5 md:gap-5 flex flex-col lg:flex-row flex-nowrap"
    x-data="{ showFilterPanel: false }">
    <div class="relative w-100 flex flex-col">
        <x-customer.products.filter-panel :categories="$categories" :brands="$brands" :colors="$colors" :sizes="$sizes"
            :filters="$filters" :minPrice="$minPrice" :maxPrice="$maxPrice" />
    </div>
    <div class="w-full h-10 flex flex-nowrap items-center lg:hidden border-b border-gray-200 -mt-5">
        <button type="button"
            class="w-full h-full border-gray-300 font-shabnam py-2 px-4 text-xs font-[500] bg-surface text-gray-700 flex items-center justify-center gap-2 relative"
            x-on:click="showFilterPanel = true"
            x-bind:class="{ 'bg-primary-light/30! text-primary-dark!': $wire.totalFilters }">
            <i class="fa-regular fa-filter"></i>
            فیلترها
            <div class="size-4 bg-accent rounded-full font-shabnam text-white flex items-center justify-center border-2 border-surface box-content"
                x-show="$wire.totalFilters" x-cloak>
                {{ $totalFilters }}
            </div>
        </button>
        <button type="button" x-data="{ showOrderOptions: false }" x-on:click="showOrderOptions = !showOrderOptions"
            class="border-r w-full h-6 border-gray-300 font-shabnam py-2 px-4 text-xs font-[500] bg-surface text-gray-700 cursor-pointer flex items-center justify-center gap-2 relative"
            x-on:click.outside="showOrderOptions = false"
            x-bind:class="{ 'h-full! border-primary-light': $wire.totalFilters }">
            <i class="fa-regular fa-arrow-down-short-wide" wire:loading.remove wire:target="changeOrder"></i>
            <i class="fa-solid fa-spinner animate-spin" wire:loading wire:target="changeOrder"></i>
            {{ __("products.sort.$orderBy") }}
            <div class="absolute h-fit top-10 left-2 flex flex-col rounded-md text-sm bg-white w-[calc(100%-16px)] shadow-md border-gray-200 border"
                x-cloak x-transition x-show="showOrderOptions">
                <span type="button" wire:click="changeOrder('newest')"
                    class="w-full h-12 cursor-pointer flex items-center px-4 hover:bg-neutral-light transition">
                    جدیدترین
                </span>
                <hr class="text-neutral-light px-3">
                <span type="button" wire:click="changeOrder('cheapest')"
                    class="w-full h-12 cursor-pointer flex items-center px-4 hover:bg-neutral-light transition">
                    ارزان‌ترین
                </span>
                <hr class="text-neutral-light">
                <span type="button" wire:click="changeOrder('most-expensive')"
                    class="w-full h-12 cursor-pointer flex items-center px-4 hover:bg-neutral-light transition">
                    گران‌ترین
                </span>
                <hr class="text-neutral-light">
                <span type="button" wire:click="changeOrder('bestseller')"
                    class="w-full h-12 cursor-pointer flex items-center px-4 hover:bg-neutral-light transition">
                    پرفروش‌ترین
                </span>
            </div>
        </button>
    </div>
    <div class="w-full flex flex-col gap-5 max-md:px-5">
        @if ($search)
            <div class="text-xl font-[500] flex flex-nowrap items-center gap-2 max-md:hidden">
                <button type="button"
                    class="size-10 flex items-center justify-center rounded-full hover:bg-neutral-light transition cursor-pointer text-neutral"
                    wire:click="removeSearch">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                جستجو برای:
                {{ $search }}
            </div>
        @endif
        <div class="w-full flex gap-3 items-center max-lg:hidden text-sm">
            <div>مرتب‌سازی:</div>
            <button type="button" @class([
                'px-3 py-2 rounded-lg cursor-pointer transition hover:bg-primary-lighter',
                'bg-primary text-white hover:bg-primary!' => $orderBy === 'newest',
            ]) wire:click="changeOrder('newest')">
                جدیدترین
            </button>
            <button type="button" @class([
                'px-3 py-2 rounded-lg cursor-pointer transition hover:bg-primary-lighter',
                'bg-primary text-white hover:bg-primary!' => $orderBy === 'cheapest',
            ]) wire:click="changeOrder('cheapest')">
                ارزان‌ترین
            </button>
            <button type="button" @class([
                'px-3 py-2 rounded-lg cursor-pointer transition hover:bg-primary-lighter',
                'bg-primary text-white hover:bg-primary!' => $orderBy === 'most-expensive',
            ]) wire:click="changeOrder('most-expensive')">
                گران‌ترین
            </button>
            <button type="button" @class([
                'px-3 py-2 rounded-lg cursor-pointer transition hover:bg-primary-lighter',
                'bg-primary text-white hover:bg-primary!' => $orderBy === 'bestseller',
            ]) wire:click="changeOrder('bestseller')">
                پرفروش‌ترین
            </button>
        </div>
        <hr class="text-neutral-200 max-lg:hidden">
        @if ($products == null)
            <p class="text-center text-gray-600 bg-white rounded-xl p-4 mx-auto my-10 w-full">
                هیج محصولی وجود ندارد.
            </p>
        @else
            <div
                class="bg-white max-md:mt-5 font-shabnam text-gray-900 grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))] md:grid-cols-[repeat(auto-fill,minmax(250px,1fr))] overflow-hidden gap-2 md:gap-3 w-full">
                @foreach ($products as $product)
                    <a href="{{ route('customer.product', $product->id) }}" wire:navigate>
                        <article class="border border-gray-200 shadow-md rounded-lg h-full flex flex-col">
                            <img src="{{ asset('storage/' . $product->image->path) }}" alt=""
                                class="w-full aspect-square object-contain rounded-t-lg">
                            <span
                                class="px-3 text-sm w-full h-fit flex items-center justify-center py-2 text-center">{{ $product->name }}</span>
                            <span
                                class="text-sm text-primary text-center w-full flex items-center justify-center pb-2 font-[500] mt-auto gap-1">
                                {{ number_format($product->price) }}
                                <span class="text-xs">تومان</span>
                            </span>
                        </article>
                    </a>
                @endforeach
                @if ($hasMorePages)
                    <div class="w-full flex justify-center" x-intersect="$wire.loadMore()">
                        <div class="size-full" wire:loading>
                            <article
                                class="bg-gray-100 size-full flex flex-nowrap items-center gap-2 flex-col w-full shrink-0 px-2 py-2 motion-safe:animate-pulse">
                                <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                                    <div
                                        class="size-15 rounded-full bg-conic from-gray-300 to-gray-100 animate-spin from-30% to-30% flex items-center justify-center">
                                        <div class="size-12 rounded-full bg-gray-200"></div>
                                    </div>
                                </div>
                                <div class="w-full flex flex-col gap-2 h-full">
                                    <div class="w-full md:h-7 h-4 bg-gray-200"></div>
                                    <div class="w-8/10 md:h-7 h-4 bg-gray-200"></div>
                                </div>
                            </article>
                        </div>
                    </div>
                @endif
                @for ($i = 0; $i < 14; $i++)
                    <div wire:loading>
                        <article
                            class="bg-gray-100 size-full flex flex-nowrap items-center gap-2 flex-col w-full shrink-0 px-2 py-2 motion-safe:animate-pulse">
                            <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                                <div
                                    class="size-15 rounded-full bg-conic from-gray-300 to-gray-100 animate-spin from-30% to-30% flex items-center justify-center">
                                    <div class="size-12 rounded-full bg-gray-200"></div>
                                </div>
                            </div>
                            <div class="w-full flex flex-col gap-2 h-full">
                                <div class="w-full md:h-7 h-4 bg-gray-200"></div>
                                <div class="w-8/10 md:h-7 h-4 bg-gray-200"></div>
                            </div>
                        </article>
                    </div>
                @endfor
            </div>
        @endif
    </div>
</div>
