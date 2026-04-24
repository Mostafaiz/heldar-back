<div class="fixed h-dvh z-5 w-full top-0 bg-black/30 opacity-0 invisible transition flex flex-nowrap gap-5"
    style="backdrop-filter: blur(2px)" x-bind:class="{ 'opacity-100! visible!': showCategoryMenu }" x-ref="backCover">
    <div class="h-dvh shadow-md lg:hidden transform-[translateX(100%)] transition duration-300 ease-in-out w-60 bg-white flex flex-col font-shabnam pt-22"
        x-bind:style="showCategoryMenu ? 'transform: translateX(0)' : ''"
        x-on:click.away="if ($event.target == $refs.backCover) showCategoryMenu = false">
        <div class="w-full flex justify-between items-center mb-4 pr-5 pl-3">
            <h3 class="font-[500]">دسته‌بندی‌ها</h3>
            <button
                class="size-8 rounded-full cursor-pointer shadow-md hover:bg-neutral-light transition flex items-center justify-center -ml-15 bg-white"
                x-on:click="showCategoryMenu = false">
                <i class="fa-duotone fa-solid fa-angles-right"></i>
            </button>
        </div>
        <hr class="text-neutral-light">
        @foreach ($categories as $category)
            <a href="{{ route('customer.products', $category->id) }}"
                class="transition relative before:absolute py-4 pr-7 before:h-full before:w-0 before:transition-all focus:before:w-full before:right-0 before:top-0 before:bg-primary/15">
                {{ $category->name }}
            </a>
            <hr class="text-neutral-light mr-7">
        @endforeach
    </div>
</div>
