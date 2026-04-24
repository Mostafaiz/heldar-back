@if (isset($products))
    <div class="w-full flex flex-nowrap flex-col rounded-2xl border border-gray-300 relative py-5 bg-surface gap-5"
        x-data="{
            scrollBy(amount) {
                $refs.products.scrollBy({ left: amount, behavior: 'smooth' })
            }
        }">
        <div class="absolute lg:flex items-center justify-between px-5 top-1/2 w-full hidden">
            <button x-on:click="scrollBy(300)"
                class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-gray-300 cursor-pointer">
                <i class="fa-solid fa-angle-right text-gray-500"></i>
            </button>
            <button x-on:click="scrollBy(-300)"
                class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-gray-300 cursor-pointer">
                <i class="fa-solid fa-angle-left text-gray-500"></i>
            </button>
        </div>

        <div class="flex justify-between items-center rounded-sm font-shabnam px-5">
            <h3 class="text-black lg:text-lg font-[500]">بازم پولتو حروم کن</h3>
            <a href="{{ route('customer.products') }}"
                class="flex items-center cursor-pointer text-primary text-xs lg:text-sm gap-2" wire:navigate>
                <p class="font-[500]">نمایش همه</p>
                <i class="fa-solid fa-angle-left text-xs"></i>
            </a>
        </div>

        <div x-ref="products"
            class="overflow-x-auto flex flex-nowrap gap-5 lg:gap-10 touch-pan-y snap-x snap-mandatory px-5 hide-scrollbar">
            @foreach ($products as $product)
                <article
                    class="w-40 lg:w-58 shrink-0 snap-start bg-surface flex flex-col font-shabnam-en gap-5 pr-4 lg:pr-10">
                    <a href="{{ route('customer.product', $product->id) }}" class="w-full lg:h-auto h-30 aspect-square"
                        wire:navigate>
                        <img src="{{ asset('storage/' . $product->image->path) }}" alt="{{ $product->image->alt }}"
                            class="w-full h-30 lg:h-full object-contain rounded-md">
                    </a>
                    <div class="flex flex-col gap-5">
                        <a href="{{ route('customer.product', $product->id) }}"
                            class="text-sm font-[500] line-clamp-2 text-gray-900" wire:navigate>
                            {{ $product->name }}
                        </a>
                        <div class="flex justify-between font-shabnam">
                            @if ($product->discount)
                                <span class="text-xs font-[500] bg-error rounded-full h-fit py-0.5 px-2 text-white">
                                    {{ ceil(($product->discount / $product->price) * 100) }}%
                                </span>
                            @endif
                            <div class="flex flex-col items-end w-full text-lg font-[500]">
                                <div class="text-gray-900">
                                    <span>{{ number_format($product->price) }}</span>
                                    <span class="text-xs">تومان</span>
                                </div>
                                @if ($product->discount)
                                    <div class="text-sm line-through text-gray-400">
                                        <span>{{ number_format($product->price - $product->discount) }}</span>
                                        <span class="text-xs">تومان</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@else
    <div>
        <div></div>
    </div>
@endif
