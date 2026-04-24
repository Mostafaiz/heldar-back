<div class="w-full max-w-[1366px] px-4 2xl:px-0 flex flex-col gap-5 items-center mb-20">
    <h1 class="font-[500] text-xl font-shabnam">درخواست‌های من</h1>
    @if ($isLoggedIn)
        <div class="w-full h-fit min-h-20 rounded-xl flex flex-col border border-gray-200 font-[shabnam] max-w-260"
            x-data="{ tableGrid: 'grid-cols-[50px_1fr_1fr_1fr_1fr_1fr_100px] max-md:grid-cols-[40px_6fr_5fr_5fr_3fr_4fr_4fr]' }">
            <div class="w-full h-10 bg-gray-50 grid grid-rows-1 rounded-t-xl border-b border-gray-200 max-md:gap-0 text-sm text-gray-600"
                x-bind:class="tableGrid">
                <div class="flex justify-center items-center max-md:text-xs border-l border-gray-200">
                    ردیف
                </div>
                <div class="flex items-center max-md:justify-center border-l border-gray-200 md:pr-3 max-md:text-xs">
                    نام کالا
                </div>
                <div class="flex items-center max-md:justify-center border-l border-gray-200 md:pr-3 max-md:text-xs">
                    رنگ کالا
                </div>
                <div class="flex items-center max-md:justify-center border-l border-gray-200 md:pr-3 max-md:text-xs">
                    کد کالا
                </div>
                <div class="flex items-center max-md:justify-center border-l border-gray-200 md:pr-3 max-md:text-xs">
                    تعداد
                </div>
                <div
                    class="flex items-center max-md:justify-center max-md:text-center border-l border-gray-200 md:pr-3 max-md:text-xs">
                    وضعیت موجودی
                </div>
                <div class="flex items-center">

                </div>
            </div>
            @if (!$demands->isEmpty())
                @foreach ($demands as $key => $demand)
                    <div class="w-full min-h-20 even:bg-white odd:bg-gray-50 grid grid-rows-1 border-b last:border-b-0 last:rounded-b-xl border-gray-200 max-md:gap-0"
                        x-bind:class="tableGrid">
                        <div class="flex items-center justify-center border-l border-gray-200">
                            {{ $key + 1 }}
                        </div>
                        <div
                            class="flex md:items-center max-md:justify-center max-md:text-center max-sm:text-xs gap-1 max-md:gap-0 max-md:flex-col max-md:text-sm border-l border-gray-200 md:px-3">
                            {{ $demand->product->name }}
                        </div>
                        <div
                            class="flex items-center max-md:text-xs max-sm:flex-col flex-wrap max-md:justify-center border-l border-gray-200 md:px-3 max-sm:text-center">
                            {{ $demand->productVariant->pattern->name }}
                        </div>
                        <div class="flex items-center max-md:justify-center border-l max-md:text-xs border-gray-200 md:px-3">
                            {{ $demand->productVariant->sku ?? '---' }}
                        </div>
                        <div
                            class="flex justify-center flex-col gap-0.5 max-md:text-xs border-l border-gray-200 md:px-3 max-md:text-center max-sm:text-xs">
                            {{ $demand->count }}
                        </div>
                        <div
                            class="flex justify-center flex-col gap-0.5 border-l border-gray-200 max-md:text-center max-md:items-center md:px-3">
                            @if ($demand->productVariant->quantity)
                                <span
                                    class="px-2 py-1 w-fit rounded-full bg-success max-sm:hidden text-white text-sm max-md:text-xs">موجود</span>
                                <span class="rounded-full text-sm max-md:text-xs sm:hidden">موجود</span>
                            @else
                                <span
                                    class="px-2 py-1 w-fit rounded-full bg-error text-white text-sm max-md:text-xs max-sm:hidden">ناموجود</span>
                                <span class="rounded-full text-sm max-md:text-xs sm:hidden">ناموجود</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-center">
                            <button type="button" wire:click="removeDemand({{ $demand->id }})"
                                class="group px-3 py-2 rounded-md cursor-pointer transition hover:bg-red-50 hover:text-error flex flex-nowrap items-center gap-2 text-sm text-neutral">
                                <i class="fa-solid fa-trash-can group-hover:text-error transition"></i>
                                <span class="max-md:hidden">حذف</span>
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
    @else
        <div class="w-full flex items-center justify-center flex-col mt-10 gap-5">
            <span class="font-shabnam">
                هنوز به حساب کاربری خود وارد نشده‌اید.
            </span>
            <a href="{{ route('login') }}"
                class="bg-primary p-3 rounded-lg font-shabnam font-[500] text-white w-fit cursor-pointer hover:bg-primary-dark transition-colors">
                ورود | ثبت نام
            </a>
        </div>
    @endif
</div>