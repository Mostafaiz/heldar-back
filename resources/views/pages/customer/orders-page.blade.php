<div class="w-full max-w-[1366px] px-4 2xl:px-0 flex flex-col gap-5 items-center mb-20" x-data="{ showOrderDetailsModal: false, showDeleteMessage: false, selectedOrderForDeleteImage: null, showCancelOrderMessage: false, selectedOrderForCancel: null }">
    <h1 class="font-[500] text-xl font-shabnam">سفارشات من</h1>
    @if ($isLoggedIn)
        <div class="w-full h-fit min-h-20 rounded-xl flex flex-col border border-gray-200 font-[shabnam] max-w-260"
            x-data="{ tableGrid: 'grid-cols-[50px_1fr_1fr_1fr_60px_1fr_100px] max-md:grid-cols-[40px_6fr_6fr_6fr_3fr_5fr_4fr] max-[460px]:grid-cols-[40px_1fr_1fr_1fr_1fr_0.5fr]!' }">
            <div class="w-full h-10 bg-gray-50 grid grid-rows-1 rounded-t-xl border-b border-gray-200 max-md:gap-0 text-sm text-gray-600"
                x-bind:class="tableGrid">
                <div class="flex justify-center items-center max-md:text-xs border-l border-gray-200">
                    ردیف
                </div>
                <div class="flex items-center justify-center border-l border-gray-200">
                    مبلغ
                </div>
                <div class="flex items-center justify-center max-md:text-center max-md:text-xs border-l border-gray-200">
                    پرداخت از طریق
                </div>
                <div class="flex items-center justify-center border-l border-gray-200">
                    وضعیت
                </div>
                <div
                    class="flex max-[460px]:hidden items-center max-[460px]:text-xs justify-center border-l border-gray-200">
                    ارسال
                </div>
                <div
                    class="flex items-center justify-center max-md:text-center max-md:text-xs border-l border-gray-200">
                    تاریخ پرداخت
                </div>
                <div class="flex items-center">

                </div>
            </div>
            @if ($transactions != null)
                @foreach ($transactions as $key => $transaction)
                    <div class="w-full h-20 even:bg-white odd:bg-gray-50 grid grid-rows-1 border-b last:border-b-0 last:rounded-b-xl border-gray-200 max-md:gap-0"
                        x-bind:class="tableGrid">
                        <div class="flex items-center justify-center border-l border-gray-200">
                            {{ $key + 1 }}
                        </div>
                        <div
                            class="flex md:items-center justify-center text-center max-sm:text-xs gap-1 max-md:gap-0 max-md:flex-col max-md:text-sm border-l border-gray-200">
                            {{ number_format($transaction->amount) }}
                            <span class="text-sm max-md:text-xs text-gray-700">تومان</span>
                        </div>
                        <div
                            class="flex md:items-center wrap-anywhere justify-center text-center max-sm:text-xs gap-1 max-md:gap-0 max-md:flex-col max-md:text-sm border-l border-gray-200">
                            @if ($transaction->method === 'online' && !$transaction->adminId)
                                درگاه
                            @elseif ($transaction->method === 'online' && $transaction->adminId)
                                مدیریت
                            @elseif ($transaction->method === 'cheque')
                                کارت‌به‌کارت
                            @endif
                        </div>
                        <div class="flex items-center gap-1 max-sm:flex-col flex-wrap justify-center border-l border-gray-200 cursor-pointer"
                            x-on:click="showOrderDetailsModal = true; $wire.sendTransactionData({{ $transaction->id }})">
                            @if ($transaction->status === 'pending')
                                <span class="md:hidden text-xs font-[500]">در انتظار</span>
                                <div
                                    class="px-3 py-1 text-sm max-[460px]:text-xs w-fit font-[500] rounded-full bg-amber-100 text-amber-700">
                                    <span class="max-md:hidden">در انتظار بررسی</span>
                                    <span class="md:hidden">بررسی</span>
                                </div>
                            @elseif ($transaction->status === 'uploading')
                                <span class="md:hidden text-xs font-[500]">در انتظار</span>
                                <div
                                    class="px-2 md:px-3 py-1 text-sm max-[460px]:text-xs w-fit font-[500] rounded-full bg-blue-100 text-blue-700">
                                    <span class="max-md:hidden max-lg:text-xs">در انتظار بارگذاری</span>
                                    <span class="md:hidden text-[10px]">بارگذاری</span>
                                </div>
                            @elseif ($transaction->status === 'success')
                                <div
                                    class="px-3 py-1 text-sm max-[460px]:text-xs w-fit font-[500] rounded-full bg-green-100 text-green-700">
                                    <span class="max-md:hidden">تراکنش موفق</span>
                                    <span class="md:hidden">موفق</span>
                                </div>
                            @elseif ($transaction->status === 'accepted')
                                <div
                                    class="px-3 py-1 text-sm max-[460px]:text-xs w-fit font-[500] rounded-full bg-green-100 text-green-700">
                                    <span class="max-md:hidden">تراکنش موفق</span>
                                    <span class="md:hidden">موفق</span>
                                </div>
                            @elseif ($transaction->status === 'rejected' || $transaction->status === 'failed')
                                <div
                                    class="px-3 py-1 max-[460px]:px-2 text-sm max-[460px]:text-xs w-fit font-[500] rounded-full bg-red-100 text-red-700">
                                    <span class="max-md:hidden">تراکنش ناموفق</span>
                                    <span class="md:hidden">ناموفق</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-center max-[460px]:hidden border-l border-gray-200">
                            @if (($transaction->status == 'accepted' || $transaction->status == 'success') && !$transaction->shipping_status)
                                <i class="fa-solid fa-clock text-warning text-xl"></i>
                            @elseif ($transaction->shipping_status)
                                <div class="size-5 rounded-full bg-success flex items-center justify-center">
                                    <i class="fa-solid fa-check text-xs mt-[1px] text-white"></i>
                                </div>
                            @else
                                ---
                            @endif
                        </div>
                        <div class="flex justify-center px-1 flex-col gap-0.5 max-md:text-xs border-l border-gray-200 text-center max-sm:text-xs"
                            dir="ltr">
                            {{ jalali($transaction->created_at, 'Y/m/d - H:i') }}
                        </div>
                        <div class="flex items-center justify-center">
                            {{-- @if ($transaction->status == 'uploading')
                            <label type="button" x-on:click="showTransactionDetailsModal = true"
                                class="group px-3 py-2 rounded-md cursor-pointer transition hover:bg-blue-50 hover:text-info flex flex-nowrap items-center gap-2 text-sm text-neutral"
                                x-data="{ loading: false }">
                                <input type="file"
                                    x-on:change="uploadChequeImage($event.target.files, {{ $transaction->id }}); loading = true"
                                    x-bind:disabled="loading" class="hidden">
                                <i class="fa-solid fa-upload group-hover:text-info transition" x-show="!loading"></i>
                                <i class="fa-solid fa-spinner animate-spin group-hover:text-info transition" x-show="loading"
                                    x-cloak></i>
                                <span class="max-md:hidden">آپلود</span>
                            </label>
                            @else --}}
                            <button type="button"
                                x-on:click="showOrderDetailsModal = true; $wire.sendTransactionData({{ $transaction->id }})"
                                class="group px-3 py-2 max-[460px]:px-1 rounded-md cursor-pointer transition hover:bg-blue-50 hover:text-info flex flex-nowrap items-center gap-2 text-sm text-neutral">
                                <i class="fa-solid fa-bars group-hover:text-info transition"></i>
                                <span class="max-md:hidden">جزئیات</span>
                            </button>
                            {{-- @endif --}}
                        </div>
                    </div>
                @endforeach
            @else
                <span class="w-full h-20 flex items-center justify-center font-shabnam text-neutral font-[500]">هیچ
                    سفارشی
                    موجود
                    نیست.</span>
            @endif
        </div>

        <livewire:components.customer.orders.order-details-modal />
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

    <x-blade.customer.orders.delete-image-message />
    <x-blade.customer.orders.cencel-order-message />
</div>

@script
    <script>
        window.uploadChequeImage = (files, id) => {
            const file = files[0];

            if (!file) return;

            const maxSize = 2 * 1024 * 1024; // 2MB
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];

            if (!validTypes.includes(file.type)) {
                event.target.value = '';
                $wire.dispatch('notify', {
                    type: 'error',
                    message: 'فایل انتخاب شده باید یک تصویر باشد!'
                });
                return;
            }

            if (file.size > maxSize) {
                event.target.value = '';
                $wire.dispatch('notify', {
                    type: 'error',
                    message: 'حداکثر حجم فایل 2 مگابایت است!'
                });
                return;
            }

            if (file.name.length > 150) {
                event.target.value = '';
                $wire.dispatch('notify', {
                    type: 'error',
                    message: 'نام فایل نمی‌تواند بیشتر از 150 کاراکتر باشد!'
                });
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
@endscript
