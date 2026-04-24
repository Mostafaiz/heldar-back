<div class="fixed w-full h-full bg-black/40 top-0 right-0 z-20 flex items-center justify-center"
    x-show="showOrderDetailsModal" x-cloak>
    <div class="flex p-5 bg-white w-full lg:w-300 h-dvh lg:h-fit lg:rounded-xl z-10 flex-col gap-5"
        x-on:notify.window="showOrderDetailsModal = false; $wire.resetData()" x-cloak>
        <div class="w-full flex justify-between items-center">
            <div class="flex items-center gap-2">
                <h2 class="font-shabnam font-[500] max-md:text-sm">جزئیات سفارش</h2>
                <i class="fa-solid fa-spinner animate-spin text-neutral" wire:loading></i>
            </div>
            <button class="size-7 cursor-pointer" x-on:click="showOrderDetailsModal = false; $wire.resetData()">
                <i class="fa-solid fa-xmark text-xl text-gray-500"></i>
            </button>
        </div>
        <div class="w-full flex flex-col gap-5 relative text-sm overflow-y-auto lg:max-h-[calc(100dvh-200px)]">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>مبلغ پرداختی:</span>
                <span>
                    {{ number_format($transaction?->amount) }}
                    تومان
                </span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>وضعیت سفارش:</span>
                <span class="font-[500]">
                    @if ($transaction?->status === 'pending')
                        <span class="text-sm">در
                            انتظار
                            بررسی</span>
                    @elseif ($transaction?->status === 'uploading')
                        <span class="text-sm">در
                            انتظار
                            بارگذاری</span>
                    @elseif ($transaction?->status === 'success')
                        <span class="text-sm">تراکنش
                            موفق</span>
                    @elseif ($transaction?->status === 'accepted')
                        <span class="text-sm">تراکنش
                            موفق</span>
                        <span class="text-xs mr-3 font-[500]">تایید تصویر</span>
                    @elseif ($transaction?->status === 'failed')
                        <span class="text-sm text-error">تراکنش
                            ناموفق</span>
                    @elseif ($transaction?->status === 'rejected')
                        <span class="text-sm text-error">تراکنش
                            ناموفق</span>
                        <span class="text-xs mr-3 font-[500]">رد تصویر</span>
                    @endif
                </span>
            </div>
            @if ($transaction?->chequeImage)
                <hr class="text-gray-200 md:hidden">
                <div class="w-full flex items-center gap-3 font-shabnam md:hidden">
                    <a href="{{ asset('storage/' . $transaction?->chequeImage) }}" target="_blank"
                        class="font-[500] text-blue-700 bg-blue-100 rounded-md px-3 py-2">مشاهده سند</a>
                    |
                    <button
                        class="w-fit flex items-center flex-nowrap font-shabnam font-[500] text-red-700 bg-red-100 rounded-md px-3 py-2 cursor-pointer"
                        x-on:click="showDeleteMessage = true; selectedOrderForDeleteImage = {{ $transaction?->id }}">
                        حذف سند
                    </button>
                </div>
            @endif
            @if ($transaction?->status == 'uploading')
                <hr class="text-gray-200 md:hidden">
                <div class="w-full flex items-center flex-nowrap md:hidden gap-3">
                    <label
                        class="flex items-center flex-nowrap font-shabnam md:hidden bg-blue-100 rounded-md px-3 py-2">
                        <input type="file"
                            x-on:change="uploadChequeImage($event.target.files, {{ $transaction->id }})"
                            wire:loading.attr="disabled" class="hidden">
                        <span class="font-[500] text-blue-700">بارگذاری سند</span>
                    </label>
                    |
                    <button
                        class="w-fit flex items-center flex-nowrap font-shabnam font-[500] bg-red-100 text-red-700 rounded-md px-3 py-2 cursor-pointer"
                        x-on:click="showCancelOrderMessage = true; selectedOrderForCancel = {{ $transaction?->id }}">
                        لغو سفارش
                    </button>
                </div>
            @endif
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>وضعیت ارسال:</span>
                <span>{{ $transaction?->shipping_status ? 'ارسال شد' : 'ارسال نشده' }}</span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>شماره سند:</span>
                <span>{{ $transaction?->code ?? '---' }}</span>
            </div>
            @if ($transaction?->gateway !== null)
                <hr class="text-gray-200">
                <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                    <span>کد ثبت:</span>
                    <span>{{ $transaction?->refId ?? '---' }}</span>
                </div>
            @endif
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>تاریخ پرداخت:</span>
                <span>{{ jalali($transaction?->createdAt) }}</span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex flex-col gap-5 font-shabnam pb-2">
                <span>محصولات</span>
                <div class="flex w-full flex-col rounded-lg border border-gray-200 max-sm:text-xs">
                    <div class="w-full flex flex-nowrap rounded-t-lg bg-neutral-light h-10">
                        <span
                            class="w-20 max-md:w-10 shrink-0 flex items-center justify-center border-l border-gray-200">ردیف</span>
                        <span class="w-full flex items-center justify-center border-l border-gray-200">نام کالا</span>
                        <span class="w-full flex items-center justify-center border-l border-gray-200">کد کالا</span>
                        <span class="w-full flex items-center justify-center border-l border-gray-200">رنگ</span>
                        <span
                            class="w-20 max-md:w-10 shrink-0 flex items-center justify-center border-l border-gray-200">تعداد</span>
                        <span class="w-full flex items-center justify-center border-gray-200">قیمت
                            (تومان)</span>
                    </div>
                    @if ($transaction?->items)
                        @foreach ($transaction->items as $key => $product)
                            <div
                                class="w-full flex flex-nowrap last:rounded-b-lg min-h-10 h-fit border-b border-gray-200 last:border-none">
                                <span
                                    class="w-20 max-md:w-10 shrink-0 flex items-center justify-center border-l border-gray-200">{{ $key + 1 }}</span>
                                <span
                                    class="w-full flex items-center justify-center border-l border-gray-200 whitespace-normal break-words">
                                    {{ $product['product_name'] }}
                                </span>
                                <span
                                    class="w-full flex items-center justify-center border-l border-gray-200 truncate whitespace-normal max-sm:text-xs">
                                    {{ $product['product_code'] ?? '---' }}
                                </span>
                                <span
                                    class="w-full flex items-center justify-center border-l border-gray-200 truncate whitespace-normal text-center">
                                    {{ $product['pattern_name'] }}
                                </span>
                                <span
                                    class="w-20 max-md:w-10 shrink-0 flex items-center justify-center border-l border-gray-200">
                                    {{ $product['quantity'] }}
                                </span>
                                <span class="w-full flex items-center justify-center border-gray-200">
                                    {{ number_format($product['unit_price']) }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span class="h-full">آدرس:</span>
                <span>{{ $transaction?->address ?? '---' }}</span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center justify-end">
                <a href="{{ route('customer.invoice', $transaction?->id ?? 0) }}" target="_blank"
                    class="rounded-md bg-primary text-white flex items-center justify-center font-shabnam font-[500] px-3 py-2">
                    چاپ
                </a>
            </div>
            @if ($transaction?->status == 'uploading')
                <div
                    class="w-60 rounded-xl border-10 border-white absolute top-0 left-0 overflow-hidden flex flex-col font-shabnam gap-3 bg-white max-md:hidden">
                    <span>تصویر:</span>
                    <label
                        class="w-full aspect-square object-contain bg-neutral-light rounded-lg cursor-pointer flex items-center justify-center font-shabnam font-[500]"
                        x-data="{ loading: false }">
                        <input type="file"
                            x-on:change="uploadChequeImage($event.target.files, {{ $transaction->id }}); loading = true"
                            wire:loading.attr="disabled" class="hidden" x-on:notify.window="loading = false">
                        <div class="flex items-center gap-4 text-neutral flex-col">
                            <i class="fa-solid fa-upload text-4xl" x-show="!loading"></i>
                            <span x-show="!loading">بارگذاری سند</span>
                            <i class="fa-solid fa-spinner size-fit text-3xl animate-spin" x-show="loading"></i>
                        </div>
                    </label>
                    <button type="button"
                        class="w-full h-12 cursor-pointer not-disabled:hover:bg-red-50 not-disabled:hover:text-red-600 disabled:bg-neutral-light transition bg-neutral-light rounded-md font-shabnam flex items-center justify-center font-[500] gap-2"
                        x-on:click="showCancelOrderMessage = true; selectedOrderForCancel = {{ $transaction?->id }}"
                        wire:loading.attr="disabled" wire:target="cancelOrder">
                        <i class="fa-solid fa-xmark transition" wire:loading.remove wire:target="cancelOrder"></i>
                        <span class="transition" wire:loading.remove wire:target="cancelOrder">لغو سفارش</span>
                        <i class="fa-solid fa-spinner animate-spin" wire:loading wire:target="cancelOrder"></i>
                    </button>
                </div>
            @endif
            @if ($transaction?->chequeImage)
                <div href="{{ asset('storage/' . $transaction?->chequeImage) }}" target="_blank"
                    class="w-60 rounded-xl border-10 border-white absolute top-0 left-0 overflow-hidden flex flex-col font-shabnam gap-3 bg-white max-md:hidden">
                    <span>تصویر:</span>
                    <a href="{{ asset('storage/' . $transaction?->chequeImage) }}" target="_blank">
                        <img src="{{ asset('storage/' . $transaction?->chequeImage) }}" alt="تصویر چک"
                            class="w-full aspect-square object-contain bg-neutral-light rounded-lg cursor-pointer">
                    </a>
                    @if ($transaction?->status === 'pending')
                        <button class="absolute top-11 cursor-pointer right-3"
                            x-on:click="showDeleteMessage = true; selectedOrderForDeleteImage = {{ $transaction?->id }}">
                            <i class="fa-solid fa-xmark-circle text-error text-xl"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- @script
<script>
    window.uploadChequeImage = (files, id) => {
        const file = files[0];
        console.log('hi')

        console.log(files[0]);

        if (!file) return;

        const maxSize = 2 * 1024 * 1024; // 2MB
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];

        if (!validTypes.includes(file.type)) {
            event.target.value = '';
            $wire.dispatch('notify', { type: 'error', message: 'فایل انتخاب شده باید یک تصویر باشد!' });
            return;
        }

        if (file.size > maxSize) {
            event.target.value = '';
            $wire.dispatch('notify', { type: 'error', message: 'حداکثر حجم فایل 2 مگابایت است!' });
            return;
        }

        if (file.name.length > 150) {
            event.target.value = '';
            $wire.dispatch('notify', { type: 'error', message: 'نام فایل نمی‌تواند بیشتر از 150 کاراکتر باشد!' });
            return;
        }

        $wire.upload('image', file,
            () => {
                $wire.uploadImage(id).then(() => {
                    $wire.dispatch('stop-loading');
                });
            }, () => {
                $wire.dispatch('stop-loading');
            }
        );
    }
</script>
@endscript --}}
