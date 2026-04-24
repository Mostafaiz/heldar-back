<div class="absolute w-100 h-fit max-h-[calc(100dvh-100px)] bg-white border border-gray-300 shadow-lg rounded-lg left-5 top-13.5 flex flex-col p-4 pl-1 gap-5"
    x-on:mouseover="showCart = true" x-on:mouseout="showCart = false" x-show="showCart" x-cloak>
    @if (count($products))
        <span class="font-shabnam text-gray-600 text-sm">
            {{ count($products) }}
            کالا
        </span>
        <div class="w-full flex flex-col gap-5 overflow-y-auto pl-3">
            @foreach ($products as $product)
                <div class="w-full h-fit flex flex-nowrap gap-4" x-data="{ loading: false }"
                    x-on:stop-loading.window="loading = false">
                    <a href="{{ route('customer.product', $product->id) }}" wire:navigate
                        class="w-15 h-full flex flex-col gap-4 justify-between shrink-0">
                        <img src="{{ asset('storage/' . $product->pattern->firstImage->path) }}" alt=""
                            class="w-full aspect-square rounded-lg object-contain">
                    </a>
                    <div class="w-2/3 flex flex-col justify-between gap-4">
                        <div class="w-full flex flex-col gap-3">
                            <a href="{{ route('customer.product', $product->id) }}" wire:navigate
                                class="w-full font-shabnam font-[500] overflow-hidden line-clamp-1 text-neutral-dark"
                                title="{{ $product->name }}">
                                {{ $product->name }}
                            </a>
                            @if ($product->pattern)
                                <div class="w-full flex flex-nowrap items-center gap-2">
                                    <div
                                        class="h-4 min-w-4 outline outline-gray-200 rounded-full shrink-0 flex items-center justify-center">
                                        @foreach ($product->pattern->colors as $color)
                                            <div class="size-full min-w-2 first:rounded-r-full last:rounded-l-full"
                                                @style(['background-color: ' . $color->code])></div>
                                        @endforeach
                                    </div>
                                    <span class="font-shabnam text-xs text-gray-600">
                                        {{ $product->pattern->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="shrink-0 flex flex-col gap-1 items-end">
                        <div wire:replace
                            class="w-25 h-9 rounded-lg border border-gray-300 flex flex-nowrap justify-between items-center"
                            x-data="{ max: false }">
                            <button type="button"
                                class="h-full aspect-square text-sm flex items-center justify-center font-shabnam text-primary cursor-pointer disabled:cursor-default"
                                x-on:click="$event.preventDefault(); $wire.increaseItem({{ $product->index }}).then(data => max = !data)"
                                wire:loading.attr="disabled" wire:target="increaseItem({{ $product->index }})">
                                <i class="fa-solid fa-plus" wire:loading.remove
                                    wire:target="increaseItem({{ $product->index }})"></i>
                                <i class="fa-solid fa-spinner animate-spin" wire:loading
                                    wire:target="increaseItem({{ $product->index }})"></i>
                            </button>
                            <span
                                class="font-shabnam text-primary size-full flex items-center justify-center font-[500] flex-col">
                                <span>
                                    {{ $product->quantity }}
                                </span>
                                <span class="text-xs text-neutral font-[300] -mt-2" x-show="max" x-cloak>حداکثر</span>
                            </span>
                            @if ($product->quantity > 1)
                                <button type="button"
                                    class=" h-full aspect-square flex items-center text-sm justify-center font-shabnam text-primary cursor-pointer disabled:cursor-default"
                                    x-on:click="$event.preventDefault(); $wire.decreaseItem({{ $product->index }}); max = false"
                                    wire:loading.attr="disabled" wire:target="decreaseItem({{ $product->index }})">
                                    <i class="fa-solid fa-minus" wire:loading.remove
                                        wire:target="decreaseItem({{ $product->index }})"></i>
                                    <i class="fa-solid fa-spinner animate-spin" wire:loading
                                        wire:target="decreaseItem({{ $product->index }})"></i>
                                </button>
                            @elseif ($product->quantity == 1)
                                <button type="button"
                                    class="h-full aspect-square flex items-center text-xs justify-center font-shabnam text-error cursor-pointer disabled:cursor-default"
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
                                    class="font-[500]">{{ number_format($product->price * $product->quantity) }}</span>
                                <span class="text-xs">تومان</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <hr class="text-gray-200 ml-3">
        <div class="w-full flex flex-nowrap justify-between items-center pl-3">
            <div class="w-full shrink-1 flex flex-col gap-1">
                <span class="text-xs text-gray-500 font-shabnam">مبلغ قابل پرداخت</span>
                <div class="font-shabnam text-neutral-dark">
                    <span class="font-[500] text-2xl">
                        {{ number_format($totalPrice) }}
                    </span>
                    <span class="text-xs">تومان</span>
                </div>
            </div>
            <a href="{{ route('customer.cart') }}" wire:navigate
                class="py-3 px-4 bg-primary font-shabnam rounded-lg text-surface shrink-0 cursor-pointer font-[500]">
                ثبت سفارش
            </a>
        </div>
    @else
        <span class="w-full h-20 flex items-center justify-center font-shabnam font-[500]">سبد خرید شما خالی است</span>
    @endif
</div>
