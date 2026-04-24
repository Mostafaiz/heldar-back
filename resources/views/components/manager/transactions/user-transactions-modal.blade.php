<div class="back-cover fixed!" x-show="showUserTransactionsModal" x-transition.opacity x-cloak>
    <div
        class="w-[calc(100%-100px)] max-w-400 h-fit max-h-[calc(100vh-100px)] bg-white rounded-2xl flex flex-col p-5 gap-5 shadow-xl ">
        <div class="flex items-center w-full justify-between">
            <h1 class="font-[shabnam] font-[500] text-2xl m-0 p-0 flex items-center gap-3">
                تراکنش های کاربر
                {{ $user?->name . ' ' . $user?->family }}
                {{ $user?->username }}
                <i class="fa-solid fa-spinner loading-icon text-[16px]" wire:loading></i>
            </h1>
            <div class="group size-10 flex items-center justify-center cursor-pointer hover:bg-gray-100 rounded-full transition"
                x-on:click="showUserTransactionsModal = false; $wire.resetData()">
                <i class=" fa-solid fa-xmark text-gray-400 text-2xl"></i>
            </div>
        </div>
        <div class="w-full h-fit min-h-20 rounded-xl flex flex-col border border-gray-200 font-[shabnam] overflow-y-auto"
            x-data="{ tableGrid: 'grid-cols-[40px_1fr_1fr_1fr_1fr_1fr_100px]' }">
            <div class="w-full sticky top-0 min-w-250 h-10 bg-gray-50 grid shrink-0 grid-rows-1 rounded-t-xl border-b border-gray-200 px-5 gap-4 text-sm text-gray-600"
                x-bind:class="tableGrid">
                <div class="flex items-center">
                    id
                </div>
                <div class="flex items-center">
                    مبلغ
                </div>
                <div class="flex items-center">
                    نوع پرداخت
                </div>
                <div class="flex items-center">
                    وضعیت
                </div>
                <div class="flex items-center">
                    شماره سند
                </div>
                <div class="flex items-center">
                    تاریخ پرداخت
                </div>
                <div class="flex items-center">

                </div>
            </div>
            @forelse ($transactions as $key => $transaction)
                <div class="w-full min-w-250 h-20 even:bg-white odd:bg-gray-50 grid grid-rows-1 border-b last:rounded-b-xl border-gray-200 p-4 gap-4"
                    x-bind:class="tableGrid">
                    <div class="flex items-center justify-center">
                        {{ $transaction->id }}
                    </div>
                    <div class="flex items-center">
                        {{ number_format($transaction->amount) }} تومان
                    </div>
                    <div class="pt-3 truncate">
                        @if ($transaction->method === 'online' && !$transaction->adminId)
                            زرین‌پال
                        @elseif ($transaction->method === 'online' && $transaction->adminId)
                            مدیریت
                        @elseif ($transaction->method === 'cheque')
                            کارت‌به‌کارت
                        @endif
                    </div>
                    <div class="flex justify-center flex-col gap-0.5">
                        @if ($transaction->status === 'pending')
                            <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-amber-100 text-amber-700">در
                                انتظار
                                بررسی</span>
                        @elseif ($transaction->status === 'uploading')
                            <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-amber-100 text-amber-700">در
                                انتظار
                                آپلود</span>
                        @elseif ($transaction->status === 'success')
                            <span
                                class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-green-100 text-green-700">تراکنش
                                موفق</span>
                        @elseif ($transaction->status === 'accepted')
                            <span
                                class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-green-100 text-green-700">تراکنش
                                موفق</span>
                            <span class="text-xs mr-3 font-[500]">تایید تصویر</span>
                        @elseif ($transaction->status === 'failed')
                            <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-red-100 text-red-700">تراکنش
                                ناموفق</span>
                        @elseif ($transaction->status === 'rejected')
                            <span class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-red-100 text-red-700">تراکنش
                                ناموفق</span>
                            <span class="text-xs mr-3 font-[500]">رد تصویر</span>
                        @endif
                    </div>
                    <div class="flex items-center">
                        {{ $transaction->code ?? '---' }}
                    </div>
                    <div class="flex items-center">
                        {{ jalali($transaction->created_at) }}
                    </div>
                    <div class="flex items-center">
                        <button type="button"
                            x-on:click="showTransactionDetailsModal = true; $dispatch('get-transaction-data', [{{ $transaction->id }}])"
                            class=" group px-3 py-2 rounded-md cursor-pointer transition hover:bg-blue-50
                                hover:text-info flex flex-nowrap items-center gap-2 text-sm text-neutral">
                            <i class="fa-solid fa-bars group-hover:text-info transition"></i>
                            <span>جزئیات</span>
                        </button>
                    </div>
                </div>
            @empty
                <span class="w-full h-20 flex items-center justify-center font-shabnam text-neutral font-[500]">هیچ
                    تراکنشی
                    موجود
                    نیست.</span>
            @endforelse
        </div>
    </div>
</div>
