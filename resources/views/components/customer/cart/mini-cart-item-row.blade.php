<div class="w-full h-50 flex flex-nowrap gap-4" x-data="{ loading: false }" x-on:stop-loading.window="loading = false">
    <div class="w-1/3 h-full flex flex-col gap-4 justify-between">
        <img src="{{ asset('storage/' . $product->pattern->firstImage->path) }}" alt=""
            class="w-full aspect-square rounded-lg object-contain">
        <div class="w-full h-10 rounded-lg border border-gray-300 flex flex-nowrap justify-between items-center">
            <button type="button"
                class="h-full aspect-square flex items-center justify-center font-shabnam text-primary cursor-pointer disabled:text-gray-300"
                x-on:click="$wire.increaseItem({{ $product->index }}); loading = true" x-bind:disabled="loading">
                <i class="fa-solid fa-plus"></i>
            </button>
            <span class="font-shabnam text-primary size-full flex items-center justify-center text-lg font-[500]"
                x-show="!loading">
                {{ $product->quantity }}
            </span>
            <i class="fa-solid fa-spinner animate-spin size-fit text-primary" x-show="loading"></i>
            @if ($product->quantity > 1)
                <button
                    class=" h-full aspect-square flex items-center justify-center font-shabnam text-primary cursor-pointer disabled:text-gray-300"
                    x-on:click="$wire.decreaseItem({{ $product->index }}); loading = true" x-bind:disabled="loading">
                    <i class="fa-solid fa-minus"></i>
                </button>
            @elseif ($product->quantity == 1)
                <button
                    class=" h-full aspect-square flex items-center justify-center font-shabnam text-error cursor-pointer disabled:text-gray-300"
                    x-on:click="$wire.removeItem({{ $product->index }}); loading = true" x-bind:disabled="loading">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            @endif
        </div>
    </div>
    <div class="w-2/3 flex flex-col justify-between gap-4">
        <div class="w-full flex flex-col gap-3">
            <p class="w-full font-shabnam font-[500] line-clamp-3 overflow-hidden text-neutral-dark">
                {{ $product->name }}
            </p>
            @if ($product->pattern)
                <div class="w-full flex flex-nowrap items-center gap-2">
                    <div
                        class="h-4 min-w-4 outline outline-gray-200 rounded-full shrink-0 flex items-center justify-center">
                        @foreach ($product->pattern->colors as $color)
                            <div class="size-full min-w-2 first:rounded-r-full last:rounded-l-full" @style(['background-color: ' . $color->code])>
                            </div>
                        @endforeach
                    </div>
                    <span class="font-shabnam text-xs text-gray-600">
                        {{ $product->pattern->name }}
                    </span>
                </div>
            @endif
            @if ($product->insurance)
                <div class="w-full flex flex-nowrap gap-2 items-center">
                    <i class="fa-solid fa-shield-halved text-primary text-xs"></i>
                    <span class="font-shabnam text-gray-600 text-xs truncate">
                        {{ $product->insurance->name }}
                    </span>
                </div>
            @endif
            @if ($product->guarantee)
                <div class="w-full flex flex-nowrap gap-2 items-center">
                    <i class="fa-solid fa-award text-primary text-xs"></i>
                    <span class="font-shabnam text-gray-600 text-xs truncate">
                        {{ $product->guarantee->name }}
                    </span>
                </div>
            @endif
        </div>
        <div class="flex flex-col gap-1">
            @if ($product->discount)
                <span
                    class="font-[500] text-xs text-red-600 font-shabnam">{{ number_format($product->discount * $product->quantity) }}
                    تومان تخفیف</span>
                <div class="font-shabnam text-neutral-dark">
                    <span
                        class="font-[500] text-2xl">{{ number_format(($product->price - $product->discount) * $product->quantity) }}</span>
                    <span class="text-xs">تومان</span>
                </div>
            @else
                <div class="font-shabnam text-neutral-dark">
                    <span class="font-[500] text-2xl">{{ number_format($product->price * $product->quantity) }}</span>
                    <span class="text-xs">تومان</span>
                </div>
            @endif
        </div>
    </div>
</div>
