<div class="opened-panel flex! flex-col justify-start items-start" x-data="{ showTransactionDetailsModal: false, showAcceptTransactionWarning: false, showRejectTransactionWarning: false, selectedTransactionForAorR: null }">
    <div class="inner-container flex! flex-col">
        <x-blade.manager.section class="title-con h-25!">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>
        <div class="w-full h-fit rounded-xl flex flex-col border border-gray-200 shadow font-[shabnam]"
            x-data="{ tableGrid: 'grid-cols-[40px_1fr_1fr_1fr_1fr_1fr_1fr_1fr_100px]' }">
            <div class="w-full sticky top-0 min-w-250 shrink-0 h-10 bg-gray-50 grid grid-rows-1 rounded-t-xl border-b border-gray-200 px-5 gap-4 text-sm text-gray-600"
                x-bind:class="tableGrid">
                <div class="flex items-center">
                    id
                </div>
                <div class="flex items-center">
                    شماره تماس
                </div>
                <div class="flex items-center">
                    نام کاربر
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
            @if (count($transactions))
                @foreach ($transactions as $key => $transaction)
                    <div class="w-full min-w-250 h-20 even:bg-white odd:bg-gray-50 grid grid-rows-1 border-b last:rounded-b-xl border-gray-200 p-4 gap-4"
                        x-bind:class="tableGrid">
                        <div class="flex items-center justify-center">
                            {{ $transaction->id }}
                        </div>
                        <div class="pt-3 truncate">
                            {{ $transaction->user->username }}
                        </div>
                        <div class="pt-3 truncate">
                            {{ $transaction->user->name }}
                        </div>
                        <div class="pt-3 truncate">
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
                                <span
                                    class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-amber-100 text-amber-700">
                                    در انتظار بررسی
                                </span>
                            @elseif ($transaction->status === 'uploading')
                                <span
                                    class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-amber-100 text-amber-700">در
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
                                <span
                                    class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-red-100 text-red-700">تراکنش
                                    ناموفق</span>
                            @elseif ($transaction->status === 'rejected')
                                <span
                                    class="px-3 py-1 text-sm w-fit font-[500] rounded-full bg-red-100 text-red-700">تراکنش
                                    ناموفق</span>
                                <span class="text-xs mr-3 font-[500]">رد تصویر</span>
                            @endif
                        </div>
                        <div class="pt-3 truncate">
                            {{ $transaction->code ?? '---' }}
                        </div>
                        <div class="pt-3 truncate">
                            {{ jalali($transaction->createdAt, 'H:i - Y/m/d') }}
                        </div>
                        <div class="pt-1 truncate">
                            <button type="button"
                                x-on:click="showTransactionDetailsModal = true; $wire.dispatch('get-transaction-data',[{{ $transaction->id }}])"
                                class="group px-3 py-2 rounded-md cursor-pointer transition hover:bg-primary-light/20 hover:text-primary-dark flex flex-nowrap items-center gap-2 text-sm text-neutral">
                                <i class="fa-solid fa-bars group-hover:text-primary-dark transition"></i>
                                <span>جزئیات</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <span class="w-full h-20 flex items-center justify-center font-shabnam text-neutral font-[500]">هیچ
                    تراکنشی
                    موجود
                    نیست.</span>
            @endif
            @if (isset($paginator))
                <div
                    class="w-full sticky bottom-0 bg-gradient-to-t rounded-b-lg from-white/90 to-white/0 backdrop:blur-3xl pb-5 h-20 flex justify-center items-end gap-3.5 shrink-0 font-[shabnam]">
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
                        class="flex items-center aspect-square justify-center gap-2 min-w-10 w-auto h-10 rounded-xl cursor-pointer bg-primary text-white p-2.5 transition text-lg">
                        <span wire:loading.remove>{{ $paginator->currentPage }}</span>
                        <i class="fa-solid fa-spinner animate-spin" wire:loading></i>
                    </button>
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
    <livewire:components.manager.transactions.transaction-details-modal />
    <x-manager.transactions.accept-transaction-warning />
    <x-manager.transactions.reject-transaction-warning />
</div>
