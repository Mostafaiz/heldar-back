<div class="opened-panel flex flex-col justify-start items-start">
    <div class="inner-container">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>
        <div class="w-full h-fit min-h-20 rounded-xl flex flex-col border border-gray-200 shadow font-[shabnam]"
            x-data="{ tableGrid: 'grid-cols-[40px_150px_350px_1fr_1fr_50px_90px_1fr_70px]' }">
            <div class="w-full min-w-250 h-10 bg-gray-50 grid grid-rows-1 rounded-t-xl border-b border-gray-200 px-5 gap-4 text-sm text-gray-600"
                x-bind:class="tableGrid">
                <div class="flex items-center">
                    ردیف
                </div>
                <div class="flex items-center">
                    کاربر
                </div>
                <div class="flex items-center">
                    محصول
                </div>
                <div class="flex items-center">
                    کد کالا
                </div>
                <div class="flex items-center">
                    رنگ کالا
                </div>
                <div class="flex items-center">
                    تعداد
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
            @if (!$demands->isEmpty())
                @foreach ($demands as $key => $demand)
                    <div class="w-full min-w-250 h-20 even:bg-white odd:bg-gray-50 grid grid-rows-1 border-b last:rounded-b-xl border-gray-200 p-4 gap-4"
                        x-bind:class="tableGrid">
                        <div class="flex items-center justify-center">
                            {{ $key + 1 }}
                        </div>
                        <div class="flex items-center">
                            {{ $demand->user->username }}
                        </div>
                        <div class="flex gap-4">
                            <div class="h-full aspect-square bg-gray-300 rounded-lg shrink-0 relative">
                                <img src="{{ asset('/storage/' . $demand->productVariant->pattern->files[0]->path) }}"
                                    class="peer w-full h-full object-contain bg-gray-200 rounded-lg border border-gray-300">
                                <div
                                    class="invisible peer-hover:visible absolute size-80 bg-white z-2 rounded-xl p-2 shadow-lg border border-gray-200 top-0 right-14">
                                    <img src="{{ asset('/storage/' . $demand->productVariant->pattern->files[0]->path) }}"
                                        class="size-full aspect-square object-contain bg-gray-200 rounded-lg">
                                </div>
                            </div>
                            <a href="{{ route('customer.product', $demand->product->id) }}" target="_blank"
                                class="truncate leading-12 cursor-pointer" title="{{ $demand->productName }}">
                                {{ $demand->product->name }}
                            </a>
                        </div>
                        <div class="flex gap-3 items-center">
                            {{ $demand->productVariant->sku }}
                        </div>
                        <div class="flex items-center">
                            {{ $demand->productVariant->pattern->name }}
                        </div>
                        <div class="flex items-center">
                            {{ $demand->count }}
                        </div>
                        <div class="flex items-center">
                            @if ($demand->productVariant->quantity)
                                <div class="px-2 py-1 rounded-full bg-green-100 text-green-600 text-sm flex gap-1.5 items-center">
                                    <div class="size-2 rounded-full bg-green-500"></div>
                                    <span>موجود</span>
                                </div>
                            @else
                                <div class="px-2 py-1 rounded-full bg-red-50 text-red-700 text-sm flex gap-1.5 items-center">
                                    <div class="size-2 rounded-full bg-red-600"></div>
                                    <span>ناموجود</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center">
                            {{ jalali($demand->created_at) }}
                        </div>
                        <div class="flex items-center">
                            <button type="button" wire:click="removeDemand({{ $demand->id }})"
                                class="group px-3 py-2 rounded-md cursor-pointer transition hover:bg-red-50 hover:text-error flex flex-nowrap items-center gap-2 text-sm text-neutral">
                                <i class="fa-solid fa-trash-can group-hover:text-error transition"></i>
                                <span>حذف</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <span class="w-full h-20 flex items-center justify-center font-shabnam text-neutral font-[500]">هیچ درخواستی
                    موجود
                    نیست.</span>
            @endif
        </div>
    </div>
</div>