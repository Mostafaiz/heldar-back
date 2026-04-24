<div class="w-full max-w-[1366px] 2xl:p-0 lg:pt-0 lg:pl-4 flex flex-nowrap gap-5">
    <div class="w-full flex flex-col gap-5">
        <div class="w-full flex flex-nowrap justify-between items-center pr-5 pl-3 relative" x-data="{ showMenu: false }">
            <div class="flex flex-nowrap gap-3 items-center">
                <h1 class="font-shabnam font-[500] w-fit">سبد خرید</h1>
                @if (count($products))
                    <span class="font-shabnam text-xs text-gray-500">
                        {{ count($products) }}
                        کالا
                    </span>
                @endif
            </div>
            <button class="cursor-pointer size-10 bg-surface" x-on:click="showMenu = true">
                <i class="fa-solid fa-ellipsis-vertical text-neutral text-lg"></i>
            </button>
            <div class="absolute h-fit w-40 bg-surface border border-gray-100 rounded-md shadow left-5 top-12 flex flex-col font-shabnam text-sm text-neutral-dark"
                x-show="showMenu" x-cloak x-on:click.outside="showMenu = false" x-on:click="showMenu = false">
                <button
                    class="group w-full flex items-center h-12 px-4 gap-3 cursor-pointer lg:hover:bg-neutral-light disabled:text-neutral disabled:cursor-default disabled:hover:bg-surface"
                    x-bind:disabled="!$wire.products.length" wire:click="removeAllItems">
                    <i class="fa-solid fa-trash-can text-error group-disabled:text-neutral"></i>
                    حذف همه
                </button>
            </div>
        </div>
        <div class="w-full flex flex-col gap-3 px-5">
            @if (count($products))
                @foreach ($products as $key => $product)
                    <div class="w-full h-fit flex flex-nowrap gap-4 lg:gap-6" wire:key="product-{{ $key }}">
                        <a href="{{ route('customer.product', $product->id) }}" wire:navigate
                            class="w-15 h-full flex flex-col gap-4 justify-between shrink-0">
                            <img src="{{ asset('storage/' . $product->pattern->firstImage->path) }}" alt=""
                                class="w-full aspect-square rounded-lg object-contain">
                        </a>
                        <div class="w-full flex flex-col gap-3">
                            <a href="{{ route('customer.product', $product->id) }}" wire:navigate
                                class="w-full font-shabnam font-[500] text-sm lg:text-base line-clamp-1 overflow-hidden text-neutral-dark">
                                {{ $product->name }}
                            </a>
                            @if (isset($product->pattern->name))
                                <div class="w-full flex flex-nowrap items-center gap-2">
                                    <div
                                        class="h-4 min-w-4 outline outline-gray-200 rounded-full shrink-0 flex items-center justify-center">
                                        @foreach ($product->pattern->colors as $color)
                                            <div class="size-full min-w-2 first:rounded-r-full last:rounded-l-full"
                                                @style(['background-color: ' . $color->code])></div>
                                        @endforeach
                                    </div>
                                    <span
                                        class="font-shabnam text-xs text-gray-600">{{ $product->pattern->name }}</span>
                                </div>
                            @endif
                        </div>
                        <div
                            class="shrink-0 flex max-md:flex-col flex-row-reverse md:items-center gap-2 md:gap-3 items-end">
                            <div class="w-25 md:w-30 my-auto shrink-0 md:h-10 h-8 rounded-lg border border-gray-300 flex flex-nowrap justify-between items-center"
                                x-data="{ max: false }" wire:replace>
                                <button type="button"
                                    class="h-full aspect-square max-md:text-sm flex items-center justify-center font-shabnam text-primary cursor-pointer disabled:cursor-default"
                                    x-on:click="$event.preventDefault(); $wire.increaseItem({{ $product->index }}).then(data => max = !data)"
                                    wire:loading.attr="disabled" wire:target="increaseItem({{ $product->index }})">
                                    <i class="fa-solid fa-plus" wire:loading.remove
                                        wire:target="increaseItem({{ $product->index }})"></i>
                                    <i class="fa-solid fa-spinner animate-spin" wire:loading
                                        wire:target="increaseItem({{ $product->index }})"></i>
                                </button>
                                <span
                                    class="font-shabnam text-primary size-full flex items-center justify-center font-[500] flex-col">
                                    <span>{{ $product->quantity }}</span>
                                    <span class="text-xs text-neutral font-[300] -mt-2" x-show="max"
                                        x-cloak>حداکثر</span>
                                </span>
                                @if ($product->quantity > 1)
                                    <button type="button"
                                        class=" h-full aspect-square max-md:text-sm flex items-center justify-center font-shabnam text-primary cursor-pointer disabled:cursor-default"
                                        x-on:click="$event.preventDefault(); $wire.decreaseItem({{ $product->index }}); max = false"
                                        wire:loading.attr="disabled" wire:target="decreaseItem({{ $product->index }})">
                                        <i class="fa-solid fa-minus" wire:loading.remove
                                            wire:target="decreaseItem({{ $product->index }})"></i>
                                        <i class="fa-solid fa-spinner animate-spin" wire:loading
                                            wire:target="decreaseItem({{ $product->index }})"></i>
                                    </button>
                                @elseif ($product->quantity == 1)
                                    <button type="button"
                                        class="h-full aspect-square flex max-md:text-xs text-sm items-center justify-center font-shabnam text-error cursor-pointer disabled:cursor-default"
                                        x-on:click="$event.preventDefault(); $wire.removeItem({{ $product->index }})"
                                        wire:loading.attr="disabled" wire:target="removeItem({{ $product->index }})">
                                        <i class="fa-solid fa-trash-can" wire:loading.remove
                                            wire:target="removeItem({{ $product->index }})"></i>
                                        <i class="fa-solid fa-spinner animate-spin" wire:loading
                                            wire:target="removeItem({{ $product->index }})"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="flex flex-col gap-1">
                                <div class="font-shabnam text-neutral-dark">
                                    <span
                                        class="font-[500] max-md:text-sm">{{ number_format($product->price * $product->quantity) }}</span>
                                    <span class="text-xs">تومان</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="text-gray-200">
                @endforeach
            @else
                <p class="w-full font-shabnam leading-40 font-[500] text-neutral-dark text-center">
                    سبد خرید شما خالی است.
                </p>
            @endif
            <div class="w-full h-50"></div>
        </div>
    </div>
    <div class="w-100 sticky top-38 h-fit lg:flex flex-col gap-3 hidden">
        <div class="w-full h-fit border border-gray-200 rounded-lg flex flex-col gap-5 p-5 font-shabnam">
            <div class="w-full flex justify-between items-center">
                <span class="text-sm text-gray-500 font-[500]">قیمت کالاها:</span>
                <div class="flex items-center gap-1 flex-nowrap">
                    <span class="font-[500] text-gray-600">
                        {{ number_format($totalPrice) }}
                    </span>
                    <span class="text-xs text-gray-500 font-[500]">تومان</span>
                </div>
            </div>
            @if (!$canBuy && count($products))
                <div class="w-full rounded-md px-3 py-2 bg-red-50 text-error text-sm font-shabnam font-[500]">
                    حداقل سفارش 10 عدد می‌باشد.
                </div>
            @endif
            @if (count($products) && $canBuy)
                <a href="{{ route('customer.checkout') }}" wire:navigate
                    class="w-full bg-primary rounded-lg py-3 text-white flex justify-center font-shabnam font-[500] hover:bg-primary-dark transition cursor-pointer truncate px-3">
                    تایید و تکمیل سفارش
                </a>
            @else
                <button type="button"
                    x-on:click="$wire.dispatch('notify', {type: 'warning', message: 'حداقل سفارش 10 عدد می‌باشد!'})"
                    class="w-full bg-gray-300 rounded-lg py-3 text-white flex justify-center font-shabnam font-[500] transition truncate px-3">
                    تایید و تکمیل سفارش
                </button>
            @endif
        </div>
        <p class="font-shabnam text-[13px] text-justify text-gray-500 w-full">
            هزینه این سفارش هنوز پرداخت نشده‌ و در صورت اتمام موجودی، کالاها از سبد حذف می‌شوند.
        </p>
    </div>
    <div class="w-full fixed bottom-15 h-17 bg-surface flex flex-nowrap gap-5 px-4 items-center lg:hidden">
        @if (count($products) && $canBuy)
            <a href="{{ route('customer.checkout') }}" wire:navigate
                class="w-full bg-primary rounded-lg py-3 text-white font-shabnam flex justify-center font-[500] hover:bg-primary-dark transition cursor-pointer truncate px-3">
                تایید و تکمیل سفارش
            </a>
        @else
            <div x-on:click="$wire.dispatch('notify', {type: 'warning', message: 'حداقل سفارش 10 عدد می‌باشد!'})"
                class="w-full bg-gray-300 relative rounded-lg py-3 text-white font-shabnam flex justify-center font-[500] transition max-[390px]:text-xs px-3">
                @if (!$canBuy && count($products))
                    <div
                        class="absolute text-center -top-5.5 text-error text-xs max-[370px]:text-[10px] font-shabnam font-[500] lg:hidden">
                        حداقل سفارش 10 عدد می‌باشد
                    </div>
                @endif
                تایید و تکمیل سفارش
            </div>
        @endif
        <div class="w-full h-full flex flex-col items-end py-3 font-shabnam justify-between">
            <span class="text-xs text-gray-500">جمع سبد خرید</span>
            <div class="flex gap-1 flex-nowrap items-center">
                <span class="text-neutral-dark text-xl font-[500]">
                    {{ number_format($totalPrice) }}
                </span>
                <span class="text-gray-800 text-xs">تومان</span>
            </div>
        </div>
    </div>
</div>
