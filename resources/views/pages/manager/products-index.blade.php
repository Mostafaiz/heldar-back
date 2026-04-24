@use('App\Enums\ProductLevelsEnum')

<div class="opened-panel">
    <div class="inner-container grid-rows-[100px_auto_100%_220px]!">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>
        <x-blade.manager.section>
            <x-blade.manager.section-title title="جستجو و فیلتر" />
            <x-blade.manager.input-text title="جستجو" class="max-w-100!" name="searchForm.keyword"
                wire:model.live.debounce.500ms="searchForm.keyword" />
            <div class="font-[shabnam] text-gray-500 text-sm flex items-center flex-nowrap gap-2">
                <i class="fa-solid fa-info-circle"></i>
                <span>
                    جستجو در محصولات با نام، نام انگلیسی و کد انبارداری (SKU)
                </span>
            </div>
        </x-blade.manager.section>
        <div class="w-full h-fit min-h-20 rounded-xl flex flex-col border border-gray-200 shadow font-[shabnam]"
            x-data="{ tableGrid: 'grid-cols-[350px_2.4fr_2fr_2fr_1.5fr_0.7fr] max-2xl:grid-cols-[250px_2.4fr_2fr_2fr_1.5fr_0.7fr]' }">
            <div class="w-full min-w-250 h-10 bg-gray-50 grid grid-rows-1 rounded-t-xl border-b border-gray-200 px-5 gap-4 text-sm text-gray-600"
                x-bind:class="tableGrid">
                <div class="flex items-center">
                    محصول
                </div>
                <div class="flex items-center">
                    قیمت
                </div>
                {{-- <div class="flex items-center">
                    موجودی
                </div> --}}
                <div class="flex items-center">
                    نوع محصول
                </div>
                <div class="flex items-center">
                    وضعیت
                </div>
                <div class="flex items-center">
                    تاریخ افزودن
                </div>
                <div class="flex items-center">

                </div>
            </div>
            @if (empty($products))
                <div class="w-full h-20 flex items-center justify-center text-gray-600">
                    هیچ محصولی موجود نیست.
                </div>
            @else
                @foreach ($products as $product)
                    <div class="w-full min-w-250 h-20 even:bg-white odd:bg-gray-50 grid grid-rows-1 border-b last:rounded-b-xl border-gray-200 p-4 gap-4 group"
                        x-bind:class="tableGrid">
                        <div class="flex gap-4">
                            <div class="h-full aspect-square bg-gray-300 rounded-lg shrink-0 relative">
                                <img src="{{ asset('/storage/' . $product->image) }}"
                                    class="peer w-full h-full object-contain bg-gray-200 rounded-lg border border-gray-300">
                                <div
                                    class="invisible peer-hover:visible absolute size-80 bg-white z-2 rounded-xl p-2 shadow-lg border border-gray-200 top-0 right-14">
                                    <img src="{{ asset('/storage/' . $product->image) }}"
                                        class="size-full aspect-square object-contain bg-gray-200 rounded-lg">
                                </div>
                            </div>
                            <a href="{{ route('customer.product', $product->productId) }}" target="_blank"
                                class="truncate leading-12 cursor-pointer" title="{{ $product->productName }}">
                                {{ $product->productName }}
                            </a>
                        </div>
                        <div class="flex justify-center flex-col">
                            <span>
                                {{ number_format($product->price) }}
                                تومان
                            </span>
                            @if ($product->discount)
                                <span class="text-xs text-neutral">
                                    {{ number_format($product->discount) }}
                                    تومان تخفیف
                                </span>
                            @endif
                        </div>
                        {{-- <div class="flex items-center">
                            {{ $product->quantity }}
                        </div> --}}
                        <div class="flex items-center">
                            @if ($product->level == ProductLevelsEnum::BORONZE->value)
                                برنزی
                            @elseif ($product->level == ProductLevelsEnum::SILVER->value)
                                نقره‌ای
                            @elseif ($product->level == ProductLevelsEnum::GOLD->value)
                                طلایی
                            @endif
                        </div>
                        <div class="flex items-center">
                            @if ($product->status)
                                <div
                                    class="px-2 py-1 rounded-full bg-green-100 text-green-600 text-sm flex gap-1.5 items-center">
                                    <div class="size-2 rounded-full bg-green-500"></div>
                                    <span>فعال</span>
                                </div>
                            @else
                                <div
                                    class="px-2 py-1 rounded-full bg-red-50 text-red-700 text-sm flex gap-1.5 items-center">
                                    <div class="size-2 rounded-full bg-red-600"></div>
                                    <span>غیرفعال</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center">
                            {{ jalali($product->createdAt) }}
                        </div>
                        <div class="flex items-center relative" x-data="{ showOptionsMenu: false }">
                            <button type="button"
                                class="border aspect-square border-transparent hover:bg-gray-100 p-2 group-hover:border-gray-200 rounded flex items-center justify-center cursor-pointer"
                                x-on:click="showOptionsMenu = true; selectedProductForDelete = ['{{ $product->productId }}', '{{ $product->productName }}']">
                                <i class="fa-solid fa-ellipsis text-gray-400"></i>
                            </button>
                            <div class="absolute bg-white w-50 h-fit flex flex-col gap-1 overflow-hidden rounded-xl shadow-2xl top-12 left-4 border-1 border-gray-200 box-border p-1.5 z-2"
                                x-show="showOptionsMenu" x-on:click="showOptionsMenu = false"
                                x-on:click.outside="showOptionsMenu = false" x-transition x-cloak>
                                <a href="{{ route('manager.products.edit', $product->productId) }}"
                                    class="group flex items-center w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition gap-1"
                                    wire:navigate>
                                    <i class="fa-solid fa-pen-to-square text-sm text-gray-400 ml-2"></i>
                                    ویرایش محصول
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            @if (isset($paginator))
                <div class="w-full h-auto flex justify-center items-center gap-3.5 shrink-0 my-5 font-[shabnam]">
                    <button type="button"
                        class="flex items-center justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-white border border-[#adadad] p-2.5 transition disabled:text-[#898989] disabled:border-[#d9d9d9] disabled:cursor-default hover:bg-[#eeeeee]"
                        wire:click="previousPage" {{ $paginator->currentPage < 2 ? 'disabled' : '' }}><i
                            class="fa-solid fa-angle-right"></i>قبلی</button>
                    @if ($paginator->currentPage > 1)
                        <button type="button"
                            class="flex items-center justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-white border border-[#adadad] p-2.5 transition text-lg hover:bg-[#eeeeee]"
                            wire:click="goToPage(1)">1</button>
                    @endif
                    @if ($paginator->currentPage > 3)
                        ...
                    @endif
                    @if ($paginator->currentPage > 2)
                        <button type="button"
                            class="flex items-center justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-white border border-[#adadad] p-2.5 transition text-lg hover:bg-[#eeeeee]"
                            wire:click="goToPage({{ $paginator->currentPage - 1 }})">{{ $paginator->currentPage - 1 }}</button>
                    @endif
                    <button type="button"
                        class="flex items-center justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-primary text-white p-2.5 transition text-lg">{{ $paginator->currentPage }}</button>
                    @if ($paginator->currentPage < $paginator->lastPage - 1)
                        <button type="button"
                            class="flex items-center justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-white border border-[#adadad] p-2.5 transition text-lg hover:bg-[#eeeeee]"
                            wire:click="goToPage({{ $paginator->currentPage + 1 }})">{{ $paginator->currentPage + 1 }}</button>
                    @endif
                    @if ($paginator->currentPage <= $paginator->lastPage - 3)
                        ...
                    @endif
                    @if ($paginator->currentPage < $paginator->lastPage)
                        <button type="button"
                            class="flex items-center justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-white border border-[#adadad] p-2.5 transition text-lg hover:bg-[#eeeeee]"
                            wire:click="goToPage({{ $paginator->lastPage }})">{{ $paginator->lastPage }}</button>
                    @endif
                    <button type="button"
                        class="flex items-center justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-white border border-[#adadad] p-2.5 transition disabled:text-[#898989] disabled:border-[#d9d9d9] disabled:cursor-default hover:bg-[#eeeeee]"
                        wire:click="nextPage"
                        {{ $paginator->currentPage >= $paginator->lastPage ? 'disabled' : '' }}>بعدی<i
                            class="fa-solid fa-angle-left"></i></button>
                </div>
            @endif
        </div>
    </div>
</div>
