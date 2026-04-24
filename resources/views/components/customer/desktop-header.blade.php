<header
    class="w-full pt-3 pb-2 border-b border-gray-200 flex-col gap-1 flex-nowrap items-center hidden lg:flex sticky z-10 top-0 bg-surface">
    <div class="w-full h-15 flex flex-nowrap items-center justify-center max-w-[1676px] px-5 gap-5 relative"
        x-data="{ showCart: false }">
        <div class="w-full flex items-center">
            <form wire:submit="search" class="w-40 h-10 rounded-md flex flex-nowrap">
                <button type="submit"
                    class="h-full aspect-square rounded-r-md flex items-center justify-center bg-primary text-white cursor-pointer">
                    <i class="fa-solid fa-search"></i>
                </button>
                <input type="text" class="bg-gray-100 rounded-l-md h-10 outline-none px-3 font-shabnam"
                    wire:model="searchText" placeholder="جستجو">
            </form>
        </div>
        <a href="{{ route('customer.index') }}" wire:navigate class="h-full aspect-square">
            <img src="{{ asset('images/dhbag-logo.png') }}" class="h-full object-contain" alt="">
        </a>
        <div class="flex flex-nowrap gap-4 justify-end items-center w-full">
            <a href="{{ route('customer.profile') }}" wire:navigate
                class="cursor-pointer flex items-center justify-center aspect-square p-3 rounded">
                <i class="fa-regular fa-user text-xl text-gray-600"></i>
            </a>
            <a href="{{ route('customer.cart') }}" wire:navigate
                class="cursor-pointer hover:bg-violet-100 flex items-center justify-center aspect-square p-3 rounded relative"
                x-on:mouseover="showCart = true" x-on:mouseout="showCart = false"
                x-bind:class="{ 'bg-violet-100': showCart }">
                <i class="fa-regular fa-shopping-cart text-xl text-gray-600"></i>
                @if ($itemCount)
                    <div
                        class="absolute h-5 min-w-5 px-1 w-fit rounded-full bg-error bottom-1 right-1 flex items-center justify-center text-surface font-shabnam text-xs font-[500] pt-0.5">
                        {{ $itemCount }}
                    </div>
                @endif
            </a>
        </div>
        @unless (Route::is('customer.cart'))
            <livewire:components.customer.mini-cart />
        @endunless
    </div>
    <div class="w-full max-w-[1676px] px-6 gap-7 flex flex-nowrap items-center justify-center font-shabnam">
        <a href="{{ route('customer.index') }}" wire:navigate
            class="flex gap-2 items-center flex-nowrap text-black cursor-pointer">
            <span>صفحه نخست</span>
        </a>
        <button type="button" class="flex gap-2 py-3 items-center flex-nowrap text-black cursor-pointer relative"
            x-data="{ showMenu: false }" x-on:mouseover="showMenu = true" x-on:mouseout="showMenu = false">
            <a href="{{ route('customer.products') }}" wire:navigate>محصولات</a>
            <i class="fa-solid fa-angle-down text-sm"></i>
            <div class="w-40 h-fit bg-surface rounded-lg shadow-md absolute border border-gray-200 top-11 cursor-default flex flex-col overflow-hidden"
                x-on:mouseover="showMenu = true" x-on:mouseout="showMenu = false" x-show="showMenu" x-cloak
                x-transition>
                @foreach ($categories as $category)
                    <a href="{{ route('customer.products', $category->id) }}" wire:navigate
                        class="w-full flex items-center py-3 px-3 hover:bg-neutral-light transition">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </button>
        <a href="{{ route('customer.orders') }}" wire:navigate
            class="flex gap-2 items-center flex-nowrap text-black cursor-pointer">
            <span>سفارشات من</span>
        </a>
        <a href="{{ route('customer.demands') }}" wire:navigate
            class="flex gap-2 items-center flex-nowrap text-black cursor-pointer">
            <span>درخواست‌های من</span>
        </a>
        <a href="{{ route('customer.about') }}" wire:navigate
            class="flex gap-2 items-center flex-nowrap text-black cursor-pointer">
            <span>درباره ما</span>
        </a>
    </div>
</header>
