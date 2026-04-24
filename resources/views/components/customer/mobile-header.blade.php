<header class="w-full h-18 px-3 py-4 pt-3 sticky top-0 bg-surface lg:hidden border-b border-gray-200 z-10"
    x-data="{ showSearch: false }">
    <div class="w-full flex flex-nowrap justify-between" x-cloak x-transition>
        <div class="flex items-center">
            <button type="button" x-on:click="showCategoryMenu = !showCategoryMenu"
                class="size-10 flex items-center justify-center cursor-pointer">
                <i class="fa-regular fa-bars text-black text-lg"></i>
            </button>
            <button type="button" class="size-10 flex items-center justify-center cursor-pointer"
                x-on:click="showSearch = true; document.querySelector('#searchInput').focus()">
                <i class="fa-regular fa-search text-black text-lg"></i>
            </button>
        </div>
        <a href="{{ route('customer.index') }}" wire:navigate>
            <img src="{{ asset('images/dhbag-logo.png') }}" class="h-13 -mt-1.5 aspect-square">
        </a>
        <div class="flex items-center">
            <a href="{{ route('customer.cart') }}" wire:navigate
                class="size-10 mt-0.5 flex items-center justify-center cursor-pointer relative">
                @if ($cartItemCount)
                    <div
                        class="size-4.5 absolute right-1 bottom-1 flex items-center justify-center pt-0.5 text-surface text-xs rounded-full bg-error font-shabnam">
                        {{ $cartItemCount }}
                    </div>
                @endif
                <i class="fa-regular fa-bag-shopping text-lg text-black"></i>
            </a>
            <a href="{{ route('customer.profile') }}" wire:navigate
                class="size-10 flex items-center justify-center cursor-pointer">
                <i class="fa-regular fa-user text-lg text-black"></i>
            </a>
        </div>
    </div>
    <form wire:submit.prevent="search"
        class="absolute z-10 w-[calc(100%-40px)] right-5 h-full pt-4 -top-full bg-white transition-all"
        x-bind:class="{ 'top-0!': showSearch }">
        <button type="submit"
            class="size-8 rounded-full flex items-center justify-center absolute right-2 top-5 cursor-pointer">
            <i class="fa-solid fa-search text-sm text-neutral"></i>
        </button>
        <input type="text"
            class="w-full h-10 border border-gray-400 rounded-full px-10 text-sm font-shabnam outline-none focus:border-2 focus:border-primary"
            id="searchInput" placeholder="جستجو" wire:model="searchText">
        <button type="button"
            class="size-8 rounded-full flex items-center justify-center absolute left-2 top-5 cursor-pointer"
            x-on:click="showSearch = false">
            <i class="fa-solid fa-xmark text-neutral"></i>
        </button>
    </form>
</header>
