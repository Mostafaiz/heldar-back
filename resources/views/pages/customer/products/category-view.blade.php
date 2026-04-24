<div class="w-full flex flex-col gap-3">
    <section
        class="w-full flex flex-nowrap gap-5 p-5 md:p-4 overflow-auto hide-scrollbar touch-pan-x snap-x snap-mandatory">
        @if ($categories)
            @foreach ($categories as $category)
                <a href="{{ route('customer.categories', $category->id) }}" wire:navigate>
                    <div class="flex flex-col items-center group cursor-pointer bg-gray-50 rounded-xl p-3 gap-3 shrink-0">
                        <div class="size-20 aspect-square rounded-lg flex items-center justify-center duration-300">
                            <img src="{{ asset('storage/' . $category->image->path) }}"
                                class="size-full object-contain rounded-lg" alt="">
                        </div>
                        <span class="font-shabnam text-gray-700 text-sm">
                            {{ $category->name }}
                        </span>
                    </div>
                </a>
            @endforeach
        @endif
    </section>
    <hr class="w-full text-gray-100">
    <section class="w-full">
        <livewire:pages.customer.products.product-list :id="$categoryId" />
    </section>
</div>