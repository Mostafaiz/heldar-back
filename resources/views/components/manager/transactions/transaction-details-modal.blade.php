<div class="back-cover fixed!" x-show="showTransactionDetailsModal" x-transition.opacity x-cloak>
    <div
        class="w-[calc(100%-100px)] max-w-200 h-fit max-h-[calc(100vh-100px)] bg-white rounded-2xl flex flex-col p-5 gap-5 shadow-xl overflow-auto">
        <div class="flex items-center w-full justify-between">
            <h1 class="font-[shabnam] font-[500] text-2xl m-0 p-0 flex items-center gap-3">
                جزئیات تراکنش
                <i class="fa-solid fa-spinner loading-icon text-[16px]" wire:loading></i>
            </h1>
            <div class="group size-10 flex items-center justify-center cursor-pointer hover:bg-gray-100 rounded-full transition"
                x-on:click="showTransactionDetailsModal = false; $wire.resetData()">
                <i class=" fa-solid fa-xmark text-gray-400 text-2xl"></i>
            </div>
        </div>
        <div class="w-full flex flex-col gap-5 overflow-x-auto relative">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>شماره تماس:</span>
                <span class="font-[500]">{{ $transaction?->user->username }}</span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>نام کاربر:</span>
                <span class="font-[500]">{{ $transaction?->user->name }}</span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>مبلغ پرداختی:</span>
                <span class="font-[500]">{{ number_format($transaction?->amount) }}</span>
                <span class="-mr-2">تومان</span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>نوع پرداخت:</span>
                <span class="font-[500]">
                    @if ($transaction?->method === 'online' && !$transaction?->adminId)
                        زرین‌پال
                    @elseif ($transaction?->method === 'online' && $transaction?->adminId)
                        مدیریت
                    @elseif ($transaction?->method === 'cheque')
                        کارت‌به‌کارت
                    @endif
                </span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>وضعیت تراکنش:</span>
                <span class="font-[500]">
                    @if ($transaction?->status === 'pending')
                        <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-amber-100 text-amber-700">در
                            انتظار
                            بررسی</span>
                    @elseif ($transaction?->status === 'uploading')
                        <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-amber-100 text-amber-700">در
                            انتظار
                            آپلود</span>
                    @elseif ($transaction?->status === 'success')
                        <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-green-100 text-green-700">تراکنش
                            موفق</span>
                    @elseif ($transaction?->status === 'accepted')
                        <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-green-100 text-green-700">تراکنش
                            موفق</span>
                        <span class="text-xs mr-3 font-[500]">تایید تصویر</span>
                    @elseif ($transaction?->status === 'failed')
                        <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-red-100 text-red-700">تراکنش
                            ناموفق</span>
                    @elseif ($transaction?->status === 'rejected')
                        <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-red-100 text-red-700">تراکنش
                            ناموفق</span>
                        <span class="text-xs mr-3 font-[500]">رد تصویر</span>
                    @endif
                </span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>شماره سند:</span>
                <span class="font-[500]">{{ $transaction?->code ?? '---' }}</span>
            </div>
            @if ($transaction?->method === 'online' && $transaction?->adminId === null)
                <hr class="text-gray-200">
                <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                    <span>کد ثبت:</span>
                    <span class="font-[500]">{{ $transaction?->refId ?? '---' }}</span>
                </div>
            @endif
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>تاریخ پرداخت:</span>
                <span class="font-[500]">{{ jalali($transaction?->createdAt) }}</span>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex flex-col gap-5 font-shabnam">
                <span>محصولات</span>
                <div class="flex w-full flex-col gap-4 rounded-lg border border-gray-200">
                    <div class="w-full flex flex-nowrap rounded-t-lg bg-gray-200 h-10 gap-2">
                        <span class="w-20 shrink-0 flex items-center justify-center">ردیف</span>
                        <span class="w-full flex items-center justify-center">نام کالا</span>
                        <span class="w-full flex items-center justify-center">کد کالا</span>
                        <span class="w-full flex items-center justify-center">رنگ</span>
                        <span class="w-20 shrink-0 flex items-center justify-center">تعداد</span>
                        <span class="w-full flex items-center justify-center">قیمت</span>
                    </div>
                    @if ($transaction?->items)
                        @foreach ($transaction->items as $key => $product)
                            <div class="w-full flex flex-nowrap last:rounded-b-lg min-h-10 gap-2">
                                <span class="w-20 shrink-0 flex items-center justify-center">{{ $key + 1 }}</span>
                                <span class="w-full flex items-center justify-center whitespace-normal break-words">
                                    {{ $product['product_name'] }}
                                </span>
                                <span class="w-full flex items-center justify-center truncate">
                                    {{ $product['product_code'] ?? '---' }}
                                </span>
                                <span class="w-full flex items-center justify-center truncate">
                                    {{ $product['pattern_name'] }}
                                </span>
                                <span class="w-20 shrink-0 flex items-center justify-center truncate">
                                    {{ $product['quantity'] }}
                                </span>
                                <span class="w-full flex items-center justify-center truncate">
                                    {{ number_format($product['unit_price']) }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <hr class="text-gray-200">
            <div class="w-full flex items-center flex-nowrap gap-3 font-shabnam">
                <span>آدرس:</span>
                <span class="font-[500]">{{ $transaction?->address }}</span>
            </div>
            <hr class="text-gray-200">
            <label class="w-full flex items-center flex-nowrap gap-3 font-shabnam cursor-pointer">
                <span>ارسال انجام شد:</span>
                <input type="checkbox" wire:model.live="shippingStatus">
            </label>
            <div class="w-full flex items-center gap-2 justify-end">
                <x-blade.manager.text-button value="بستن"
                    x-on:click="showTransactionDetailsModal = false; $wire.resetData()" />
                <a href="{{ route('customer.invoice', $transaction?->id ?? 0) }}" target="_blank"
                    class="button filled">
                    <i class="fa-solid fa-print"></i>
                    چـــاپ
                </a>
            </div>

            @if ($transaction?->method === 'cheque')
                <div
                    class="w-60 rounded-xl border-10 border-white absolute top-0 left-0 overflow-hidden flex flex-col font-shabnam gap-3 bg-white">
                    <span>تصویر:</span>
                    @if ($transaction?->chequeImage)
                        <a href="{{ asset('storage/' . $transaction?->chequeImage) }}" target="_blank">
                            <img src="{{ asset('storage/' . $transaction?->chequeImage) }}" alt="تصویر چک"
                                class="w-full aspect-square object-contain bg-neutral-light rounded-lg cursor-pointer">
                        </a>
                    @else
                        <div
                            class="w-full aspect-square bg-neutral-light gap-3 flex-col text-neutral rounded-lg flex items-center justify-center font-shabnam text-sm">
                            <i class="fa-solid text-gray-300 fa-clock text-6xl"></i>
                            کاربر هنوز تصویری بارگذاری نکرده.
                        </div>
                    @endif
                    @if ($transaction?->status === 'pending' || $transaction?->status === 'uploading')
                        <div class="flex items-center gap-2 cursor-pointer w-full h-11 px-3 bg-green-50 justify-center text-green-800 border border-green-700 rounded-md"
                            x-on:click="showAcceptTransactionWarning = true; selectedTransactionForAorR = {{ $transaction?->id }}">
                            تایید تصویر
                        </div>
                        <div class="flex items-center gap-2 cursor-pointer w-full h-11 px-3 bg-red-50 justify-center text-red-800 border border-red-700 rounded-md"
                            x-on:click="showRejectTransactionWarning = true; selectedTransactionForAorR = {{ $transaction?->id }}">
                            رد تصویر
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
