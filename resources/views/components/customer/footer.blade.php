<div class="min-h-60 h-fit w-full max-md:pb-20 mt-auto flex flex-col gap-3">
    @if (Route::is('customer.products'))
        <div class="w-full flex justify-end px-4">
            <button
                class="px-3 py-2 rounded-full bg-white max-md:text-sm font-[500] shadow-md cursor-pointer text-black flex items-center justify-center gap-2 font-shabnam border border-primary"
                x-on:click="window.scrollTo({top: 0, behavior: 'smooth'}); ">
                <i class="fa-solid fa-angle-up text-sm"></i>
                رفتن به بالا
            </button>
        </div>
    @endif
    <footer class="bg-primary w-full h-full flex justify-center py-10">
        <div class="w-80 h-full flex flex-col font-shabnam text-white gap-4">
            <span class="font-[500] text-xl">ارتباط با ما</span>
            <hr class="text-neutral-light">
            <div class="flex gap-2 items-center">
                <button class="size-6 flex items-center justify-center bg-green-500 rounded-full">
                    <i class="fa-brands fa-whatsapp"></i>
                </button>
                <a href="https://wa.me/{{ $phone }}" target="_blank"
                    class="font-[500] text-lg">{{ $phone }}</a>
            </div>
            <hr class="text-neutral-light">
            <p>
                {{ $address }}
            </p>
            <a href="{{ route('customer.about') }}" wire:navigate>
                درباره ما
                <i class="fa-solid fa-angle-left"></i>
            </a>
        </div>
    </footer>
</div>
