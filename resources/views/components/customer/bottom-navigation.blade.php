<div class="w-full bg-surface h-15 fixed bottom-0 border-t border-gray-200 flex flex-nowrap lg:hidden">
    <a href="/" class="w-full h-full flex flex-col gap-1 text-neutral-dark font-shabnam justify-center items-center"
        wire:current.exact="text-primary" wire:navigate>
        <i class="fa-light fa-home text-lg"></i>
        <span class="text-xs">خانه</span>
    </a>
    <a href="/cart" class="w-full h-full flex flex-col gap-1 text-neutral-dark font-shabnam justify-center items-center"
        wire:current.exact="text-primary" wire:ignore.self wire:navigate>
        <i class="fa-light relative fa-cart-shopping text-lg">
            @if ($itemCount)
                <div
                    class="size-4.5 absolute -right-2 top-1 bottom-0 flex items-center justify-center pt-0.5 text-surface text-xs rounded-full bg-error font-shabnam">
                    {{ $itemCount }}
                </div>
            @endif
        </i>
        <span class="text-xs">سبد خرید</span>
    </a>
    <a href="/products"
        class="w-full h-full flex flex-col gap-1 text-neutral-dark font-shabnam justify-center items-center"
        wire:current="text-primary" wire:navigate>
        <i class="fa-light fa-box text-lg"></i>
        <span class="text-xs">محصولات</span>
    </a>
    <a href="/orders"
        class="w-full h-full flex flex-col gap-1 text-neutral-dark font-shabnam justify-center items-center"
        wire:current.exact="text-primary" wire:navigate>
        <i class="fa-light fa-basket-shopping text-lg"></i>
        <span class="text-xs">سفارش‌ها</span>
    </a>
    <a href="/demands"
        class="w-full h-full flex flex-col gap-1 text-neutral-dark font-shabnam justify-center items-center"
        wire:current.exact="text-primary" wire:navigate>
        <i class="fa-light fa-bell text-lg"></i>
        <span class="text-xs">درخواست‌ها</span>
    </a>
</div>
