{{-- <div class="w-full max-w-[1366px] px-4 2xl:p-0 flex flex-col gap-6">
    <livewire:components.customer.homepage.slider />
    <div class="w-full flex items-center justify-center font-shabnam font-[500]">
        <hr class="text-primary/50 w-full">
        <span class="px-2 absolute bg-white text-primary">دسته‌بندی‌ها</span>
    </div>
    <div class="w-full gap-3 md:flex grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))]">
        @foreach ($categories as $category)
            <a href="{{ route('customer.products', $category->id) }}" wire:navigate
                class="h-fit w-full p-2 flex flex-col items-center gap-2 mx-full font-shabnam-en">
                <div class="w-full aspect-square relative">
                    <img src="{{ asset('storage/' . $category->image?->path ?? '') }}"
                        alt="{{ $category->image?->alt ?? '' }}"
                        class="size-full absolute object-cover rounded-2xl shrink-1">
                </div>
                <span class="w-full text-center">{{ $category->name }}</span>
            </a>
        @endforeach
    </div>
    <div class="w-full flex items-center justify-center font-shabnam font-[500]">
        <hr class="text-primary/50 w-full">
        <span class="px-2 absolute bg-white text-primary">آخرین محصولات</span>
    </div>
    <div class="w-full gap-3 md:flex grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))]">
        @foreach ($products as $product)
            <a href="{{ route('customer.products', $product->id) }}" wire:navigate
                class="h-fit w-full p-2 flex flex-col items-center gap-2 mx-full font-shabnam-en">
                <div class="w-full aspect-square relative">
                    <img src="{{ asset('storage/' . $product->image?->path ?? '') }}"
                        alt="{{ $product->image?->alt ?? '' }}"
                        class="size-full absolute object-cover rounded-2xl shrink-1">
                </div>
                <span class="w-full text-center text-sm">{{ $product->name }}</span>
                <span class="font-shabnam text-primary font-[500] text-sm">
                    {{ number_format($product->price) }}
                    تومان
                </span>
            </a>
        @endforeach
    </div>
</div> --}}
<div class="w-full max-w-[1366px] px-4 2xl:p-0 flex flex-col gap-5">
    <livewire:components.customer.homepage.slider />
    <div class="w-full flex flex-wrap items-center justify-center gap-4 lg:gap-5">
        @foreach ($categories as $category)
            <a href="{{ route('customer.products', $category->id) }}" wire:navigate
                class="h-fit max-[424px]:w-40 w-45 md:w-60 lg:w-70 xl:w-80 grow-0 p-2 flex flex-col items-center gap-2 mx-full font-shabnam-en rounded-lg shadow-md border border-gray-100">
                <img src="{{ asset('storage/' . $category->image?->path ?? '') }}"
                    alt="{{ $category->image?->alt ?? '' }}" class="w-full object-cover rounded-md aspect-square">
                <span class="line-clamp-1 text-center">{{ $category->name }}</span>
            </a>
        @endforeach
    </div>
</div>
