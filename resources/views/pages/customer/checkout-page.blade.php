@use('App\Enums\Payment\PaymentMethodEnum')

<div class="w-full max-w-[1366px] 2xl:p-0 lg:pt-0 lg:pl-4 flex flex-col flex-nowrap pt-2">
    <div
        class="w-full flex flex-nowrap gap-2 z-2 pl-5 h-14 items-center font-shabnam border-b font-[500] sticky top-0 bg-white max-lg:border-b border-gray-200">
        <a href="{{ route('customer.cart') }}" wire:navigate
            class="size-10 flex items-center justify-center cursor-pointer">
            <i class="fa-solid fa-arrow-right"></i>
        </a>
        <span class="text-sm lg:text-base">پرداخت</span>
    </div>
    <div class="flex lg:gap-4 mt-5">
        <div class="w-full flex flex-col flex-nowrap grow shrink min-w-0 lg:gap-10 gap-2">
            <div class="w-full flex relative flex-col p-5 font-shabnam text-sm font-[500] rounded-xl border-gray-200">
                <div class="mb-7 w-full flex items-center mt-3 gap-2">
                    <span
                        class="absolute right-1/2 bg-primary-lighter translate-x-1/2 border border-neutral-600 text-primary-dark flex items-center gap-1 rounded-full py-1 px-2 w-fit">
                        <i class="fa-regular fa-location-dot text-xs"></i>
                        انتخاب آدرس
                    </span>
                    <hr class="w-full text-neutral-600">
                </div>
                @if (count($addresses))
                    @foreach ($addresses as $key => $address)
                        <label @class([
                            'flex flex-nowrap not-last:border-b nth-[2]:rounded-t-lg last:rounded-b-lg border-neutral-200 h-fit py-3 cursor-pointer',
                            'bg-primary-lighter' => $selectedAddressId === $key,
                        ])>
                            <div class="flex items-center justify-center h-full w-14 shrink-0">
                                <input type="radio" class="size-4" value="{{ $key }}"
                                    wire:model.live="selectedAddressId">
                            </div>
                            <div class="flex flex-col flex-nowrap justify-center gap-1 pl-2 w-full">
                                <span>{{ $address->name }}</span>
                                <p class="font-normal text-gray-600 w-full">
                                    {{ $address->fullAddress }}
                                    <br>
                                    کد پستی: {{ $address->zipcode }}
                                </p>
                            </div>
                        </label>
                    @endforeach
                @else
                    <div class="w-full flex items-center flex-col gap-4">
                        <span class="text-sm font-shabnam text-neutral">هیچ آدرسی موجود نیست.</span>
                        <a href="{{ route('customer.profile') }}" wire:navigate
                            class="px-3 py-2 w-fit flex items-center justify-center bg-primary text-white rounded-lg transition hover:bg-primary-dark">
                            افزودن آدرس
                        </a>
                    </div>
                @endif
            </div>
            <div
                class="w-full flex flex-col shrink-1 relative gap-5 p-5 grow-0 font-shabnam text-sm font-[500] rounded-xl border-gray-200">
                <div class="mb-2 w-full flex items-center gap-2">
                    <span
                        class="absolute right-1/2 bg-primary-lighter flex items-center gap-1 text-primary-dark translate-x-1/2 border border-neutral-600 rounded-full py-1 px-2 w-fit">
                        <i class="fa-regular fa-box"></i>
                        محصولات
                    </span>
                    <hr class="w-full text-neutral-600">
                </div>
                <div class="w-full flex flex-nowrap grow-0 gap-3 overflow-x-scroll scroll-smooth"
                    style="scrollbar-width: thin">
                    @foreach ($cartItems as $item)
                        <div
                            class="w-30 h-40 bg-gray-50 border-gray-200 rounded-md shrink-0 flex flex-col flex-nowrap gap-5 p-3">
                            <img src="{{ asset('storage/' . $item->pattern->firstImage->path) }}"
                                alt="{{ $item->pattern->firstImage->path }}"
                                class="w-full aspect-square rounded object-cover">
                            @if ($item->pattern)
                                <div class="w-full flex flex-nowrap items-center gap-2">
                                    <div
                                        class="h-4 min-w-4 outline outline-gray-200 rounded-full shrink-0 flex items-center justify-center">
                                        @foreach ($item->pattern->colors as $color)
                                            <div class="size-full min-w-2 first:rounded-r-full last:rounded-l-full"
                                                @style(['background-color: ' . $color->code])></div>
                                        @endforeach
                                    </div>
                                    <span class="font-shabnam text-xs text-gray-600 line-clamp-1">
                                        {{ $item->pattern->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div
                class="w-full flex flex-col gap-3 p-5 font-shabnam text-sm font-[500] relative border-gray-200 rounded-xl">
                <div class="mb-4 w-full flex items-center gap-2">
                    <span
                        class="absolute right-1/2 bg-primary-lighter text-primary-dark translate-x-1/2 border border-neutral-600 rounded-full py-1 px-2 w-fit">
                        <i class="fa-regular fa-truck text-xs ml-1"></i>
                        نحوه ارسال
                    </span>
                    <hr class="w-full text-neutral-600">
                </div>
                @if (count($shippings))
                    <div class="w-full flex flex-nowrap justify-center gap-25 max-[460px]:gap-10">
                        @foreach ($shippings as $key => $shipping)
                            <label @class([
                                'flex flex-col items-center justify-center flex-nowrap h-16 rounded-lg cursor-pointer',
                                'h-fit!' => $selectedShippingId === $key + 1,
                            ])>
                                <div class="w-full flex flex-nowrap items-center justify-center gap-3 h-16 shrink-0">
                                    <div class="flex items-center justify-center h-full shrink-0">
                                        <input type="radio" class="size-4" value="{{ $key + 1 }}"
                                            wire:model.live="selectedShippingId">
                                    </div>
                                    <div class="flex flex-nowrap justify-between items-center shrink-0">
                                        <span>{{ $shipping->name }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="w-full flex items-center flex-col gap-4">
                        <span class="text-sm font-shabnam text-neutral">هیچ پست ارسالی موجود نیست.</span>
                    </div>
                @endif
            </div>
            <div
                class="w-full flex flex-col gap-3 p-5 font-shabnam text-sm font-[500] relative border-gray-200 rounded-xl">
                <div class="mb-4 w-full flex items-center gap-2">
                    <span
                        class="absolute right-1/2 translate-x-1/2 flex items-center gap-1 border bg-primary-lighter text-primary-dark border-neutral-600 rounded-full py-1 px-2 w-fit">
                        <i class="fa-regular fa-credit-card"></i>
                        شیوه پرداخت
                    </span>
                    <hr class="w-full text-neutral-600">
                </div>
                <label @class([
                    'flex flex-nowrap rounded-lg border-gray-300 border h-16 cursor-pointer',
                    'border-primary bg-primary-lighter' =>
                        $paymentMethod === PaymentMethodEnum::GATEWAY->value,
                ])>
                    <div class="flex items-center justify-center h-full w-14 shrink-0">
                        <input type="radio" class="size-4" value="{{ PaymentMethodEnum::GATEWAY->value }}"
                            wire:model.live="paymentMethod">
                    </div>
                    <div class="size-full flex flex-nowrap justify-between items-center gap-.5 pl-4">
                        <span>پرداخت اینترنتی زرین پال</span>
                    </div>
                </label>
                <label @class([
                    'flex flex-nowrap flex-col items-center pb-4 h-16 overflow-hidden rounded-lg border-gray-300 border cursor-pointer',
                    'border-primary bg-primary-lighter h-fit!' =>
                        $paymentMethod === PaymentMethodEnum::CHEQUE->value,
                ])
                    x-show="$wire.currentUserLevel == 'silver' || $wire.currentUserLevel == 'gold'" x-cloak>
                    <div class="w-full flex flex-nowrap h-16 shrink-0">
                        <div class="flex items-center justify-center h-full w-14 shrink-0">
                            <input type="radio" class="size-4" value="{{ PaymentMethodEnum::CHEQUE->value }}"
                                wire:model.live="paymentMethod">
                        </div>
                        <div class="size-full flex flex-nowrap justify-between items-center gap-.5 pl-4">
                            <span>کارت به کارت</span>
                        </div>
                    </div>

                    @if ($paymentMethod === PaymentMethodEnum::CHEQUE->value)
                        <div class="w-full flex flex-col gap-3 px-4">
                            <hr class="w-full text-gray-300">

                            <div class="flex items-center gap-2 h-10">
                                <span class="font-shabnam">مبلغ فاکتور را به یکی از حساب‌های زیر واریز نمایید.</span>
                            </div>

                            <div class="flex flex-col gap-2 max-h-80 overflow-y-auto">
                                @forelse ($this->paymentCards as $card)
                                    <label @class([
                                        'flex flex-col overflow-hidden cursor-pointer px-5 bg-primary-light/20 rounded-lg shrink-0',
                                        'h-fit!' => $selectedManagerUserId == $card->id,
                                    ])>
                                        <div class="flex justify-center flex-col gap-3 h-fit py-4 shrink-0">
                                            <span>
                                                {{ $card->owner_name }}
                                                -
                                                {{ $card->bank_name }}
                                            </span>
                                            <div class="font-shabnam-en">
                                                <span>شماره کارت:</span>
                                                <span dir="ltr">
                                                    {{ card_number_format($card->card_number) }}
                                                </span>
                                            </div>
                                            <div class="font-shabnam-en">
                                                <span>شماره شبا:</span>
                                                <span dir="ltr">
                                                    IR{{ $card->iban_number }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <span>هیچ کارتی موجود نیست.</span>
                                @endforelse
                            </div>

                            <div class="flex items-center gap-2 h-10">
                                <span class="font-shabnam">ارسال عکس سند واریزی</span>
                            </div>

                            <label
                                class="w-full max-w-70 cursor-pointer aspect-square border-dashed border-2 border-primary-dark bg-primary-light/30 rounded-lg flex items-center justify-center flex-col relative"
                                x-data="{ loading: false }" x-on:stop-loading.window="loading = false"
                                x-on:start-loading.window="loading = true">
                                @if ($image)
                                    <img class="size-full object-contain" src="{{ asset($image->temporaryUrl()) }}"
                                        alt="">
                                    <button type="button" wire:click="removeImage"
                                        class="size-5.5 rounded-full bg-error text-white flex items-center justify-center absolute top-3 right-3 cursor-pointer">
                                        <i class="fa-solid fa-xmark size-fit"></i>
                                    </button>
                                @else
                                    <input type="file" class="hidden"
                                        x-on:change="uploadChequeImage($event.target.files)"
                                        wire:loading.attr="disabled">
                                    <i class="fa-solid fa-circle-plus text-6xl text-primary" x-show="!loading"></i>
                                    <span class="text-sm mt-3 text-primary-dark px-2" x-show="!loading">برای بارگذاری
                                        کلیک کنید.</span>
                                    <i class="fa-solid fa-spinner text-3xl animate-spin text-primary"
                                        x-show="loading"></i>
                                @endif
                            </label>

                            <div class="flex items-center gap-2 h-10">
                                <span class="font-shabnam">این کار را می‌توانید بعدا از قسمت سفارشات نیز انجام
                                    دهید.</span>
                            </div>
                        </div>
                    @endif
                </label>
                <label @class([
                    'flex flex-nowrap flex-col items-center pb-4 h-16 overflow-hidden rounded-lg border-gray-300 border cursor-pointer',
                    'border-primary bg-primary-lighter h-fit!' =>
                        $paymentMethod === PaymentMethodEnum::MANAGER->value,
                ]) x-show="$wire.isManager" x-cloak>
                    <div class="w-full flex flex-nowrap h-16 shrink-0">
                        <div class="flex items-center justify-center h-full w-14 shrink-0">
                            <input type="radio" class="size-4" value="{{ PaymentMethodEnum::MANAGER->value }}"
                                wire:model.live="paymentMethod">
                        </div>

                        <div class="size-full flex flex-nowrap justify-between items-center gap-.5 pl-4">
                            <span>پرداخت از طریق مدیریت</span>
                            <i class="fa-solid fa-spinner animate-spin" wire:loading wire:target="loadUsers"></i>
                        </div>
                    </div>

                    @if ($paymentMethod === PaymentMethodEnum::MANAGER->value)
                        <div class="w-full flex flex-col gap-3 px-4">
                            <hr class="w-full text-gray-300">

                            <div class="flex items-center gap-2 h-10">
                                <span class="font-shabnam">انتخاب کاربر</span>
                                <input type="text"
                                    class="border border-gray-300 rounded-md bg-gray-100 outline-none focus:border-2 px-2 focus:border-primary h-8 font-shabnam"
                                    placeholder="جستجو" wire:model.live.debounce.500ms="userSearch">
                                <i class="fa-solid fa-spinner animate-spin text-neutral" wire:loading
                                    wire:target="userSearch"></i>
                            </div>

                            <div class="flex flex-col gap-2 max-h-80 overflow-y-auto">
                                @foreach ($users as $user)
                                    <label @class([
                                        'flex flex-col h-13 overflow-hidden cursor-pointer bg-primary-light/20 px-5 rounded-lg shrink-0',
                                        'h-fit!' => $selectedManagerUserId == $user->id,
                                    ])>
                                        <div class="flex items-center gap-3 h-13 shrink-0">
                                            <input type="radio" class="size-4" value="{{ $user->id }}"
                                                wire:model.live="selectedManagerUserId">

                                            @if ($user->name == '' && $user->family == '')
                                                <span class="text-neutral">بدون نام</span>
                                            @else
                                                <span>{{ $user->name }} {{ $user->family }}</span>
                                            @endif

                                            <span>{{ $user->username }}</span>
                                        </div>
                                        آدرس:
                                        <div class="flex items-center gap-3 shrink-0 h-13">
                                            @foreach ($user->addresses as $address)
                                                <label
                                                    class="flex items-center gap-2 px-2 py-1 rounded-lg bg-amber-200 cursor-pointer">
                                                    <input type="radio" class="size-4" value="{{ $address->id }}"
                                                        wire:model.live="selectedAddressIdForManager">
                                                    {{ $address->name }}
                                                </label>
                                            @endforeach
                                            @if ($user->addresses->isEmpty())
                                                <span>هیچ آدرسی موجود نیست</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <span id="manager-error" class="error-message"></span>
                        </div>
                    @endif
                </label>
            </div>
            <hr class="w-full h-2 border-none bg-gray-100 lg:hidden">
            <div class="w-full flex flex-col gap-4 p-5 lg:hidden">
                <div class="w-full flex flex-nowrap justify-between font-shabnam text-sm text-neutral">
                    <span>قیمت کالاها ({{ count($cartItems) }})</span>
                    <span>{{ number_format($totalPrice) }} تومان</span>
                </div>
                @if ($totalDiscount)
                    <hr class="text-neutral-light">
                    <div class="w-full flex flex-nowrap justify-between font-shabnam text-sm text-neutral">
                        <span>سود شما از این خرید</span>
                        <span>{{ number_format($totalDiscount) }} تومان</span>
                    </div>
                @endif
                <hr class="text-neutral-light">
                @if ($selectedShippingId != null)
                    <div class="w-full flex flex-nowrap justify-between font-shabnam text-sm text-neutral">
                        <span>هزینه ارسال</span>
                        <span>{{ number_format($shippings[$selectedShippingId - 1]->price) }} تومان</span>
                    </div>
                @endif
            </div>
            <div class="w-full h-17"></div>
            <div
                class="w-full h-17 flex flex-nowrap gap-5 items-center lg:hidden px-5 fixed border-t border-gray-100 bg-white bottom-0">
                <button type="submit" wire:click="submit" wire:loading.attr="disabled"
                    class="w-full bg-primary rounded-lg py-3 text-white font-shabnam font-[500] hover:bg-primary-dark transition cursor-pointer truncate px-3">
                    <div wire:loading.remove wire:target="submit">
                        <span x-show="$wire.paymentMethod == '{{ PaymentMethodEnum::GATEWAY->value }}'">پرداخت</span>
                        <span x-show="$wire.paymentMethod == '{{ PaymentMethodEnum::CHEQUE->value }}'" x-cloak>ثبت
                            سفارش</span>
                        <span x-show="$wire.paymentMethod == '{{ PaymentMethodEnum::MANAGER->value }}'" x-cloak>ثبت
                            سفارش</span>
                    </div>
                    <i class="fa-solid fa-spinner animate-spin" wire:loading wire:target="submit"></i>
                </button>
                <div class="w-full h-full flex flex-col items-end py-3 font-shabnam justify-between">
                    <span class="text-xs text-gray-500">قیمت کل</span>
                    <div class="flex gap-1 flex-nowrap items-center">
                        <span class="text-neutral-dark text-xl font-[500]">
                            {{ number_format($totalPrice - $totalDiscount + ($selectedShippingId != null ? $shippings[$selectedShippingId - 1]->price : 0)) }}
                        </span>
                        <span class="text-gray-800 text-xs">تومان</span>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="w-80 h-fit border rounded-xl border-gray-200 shrink-0 hidden lg:flex flex-col p-4 gap-4 sticky top-19">
            <div class="w-full flex flex-nowrap justify-between font-shabnam text-sm text-neutral">
                <span>قیمت کالاها ({{ count($cartItems) }})</span>
                <span>{{ number_format($totalPrice) }} تومان</span>
            </div>
            @if ($totalDiscount)
                <hr class="text-neutral-light">
                <div class="w-full flex flex-nowrap justify-between font-shabnam text-sm text-neutral">
                    <span>سود شما از این خرید</span>
                    <span>{{ number_format($totalDiscount) }} تومان</span>
                </div>
            @endif
            @if ($selectedShippingId != null)
                <hr class="text-neutral-light">
                <div class="w-full flex flex-nowrap justify-between font-shabnam text-sm text-neutral">
                    <span>هزینه ارسال</span>
                    <span>{{ number_format($shippings[$selectedShippingId - 1]?->price) }} تومان</span>
                </div>
            @endif
            <div class="w-full h-full flex items-center justify-between mt-5 font-shabnam">
                <span class="text-sm text-gray-500">مبلغ قابل پرداخت</span>
                <div class="flex gap-1 flex-nowrap items-center">
                    <span class="text-neutral-dark text-xl font-[500]">
                        {{ number_format($totalPrice + ($selectedShippingId != null ? $shippings[$selectedShippingId - 1]->price : 0)) }}
                    </span>
                    <span class="text-gray-800 text-xs">تومان</span>
                </div>
            </div>
            <button type="button" wire:click="submit" wire:loading.attr="disabled"
                class="w-full bg-primary rounded-lg py-3 text-white font-shabnam disabled:bg-primary-light font-[500] hover:bg-primary-dark transition cursor-pointer truncate px-3">
                <div wire:loading.remove wire:target="submit">
                    <span x-show="$wire.paymentMethod == '{{ PaymentMethodEnum::GATEWAY->value }}'">پرداخت</span>
                    <span x-show="$wire.paymentMethod == '{{ PaymentMethodEnum::CHEQUE->value }}'" x-cloak>ثبت
                        سفارش</span>
                    <span x-show="$wire.paymentMethod == '{{ PaymentMethodEnum::MANAGER->value }}'" x-cloak>ثبت
                        سفارش</span>
                </div>
                <i class="fa-solid fa-spinner animate-spin" wire:loading wire:target="submit"></i>
            </button>
        </div>
    </div>
</div>

@script
    <script>
        window.uploadChequeImage = (files) => {
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

            $wire.dispatch('start-loading');

            $wire.upload('image', file,
                () => {
                    $wire.dispatch('stop-loading');
                }, () => {
                    $wire.dispatch('stop-loading');
                    $wire.dispatch('notify', {
                        type: 'error',
                        message: 'خطا در بارگذاری عکس!'
                    });
                }
            );
        }
    </script>
@endscript
