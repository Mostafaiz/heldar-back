@props(['category', 'filters'])

<div class="flex flex-col border-b last:border-none border-gray-200" x-data="{ opened: false }">
    <div class="flex flex-nowrap items-center justify-between">
        <label class="flex w-full gap-5 items-center cursor-pointer h-13">
            <input type="checkbox" value="{{ $category->id }}"
                class="size-4.5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                wire:model.live="filters.category">
            <span>{{ $category->name }}</span>
        </label>
        @if (isset($category->children))
            <button class="cursor-pointer" x-on:click="opened = !opened">
                <i class="fa-solid fa-angle-down text-sm text-neutral" x-bind:class="{ 'fa-angle-up': opened }"></i>
            </button>
        @endif
    </div>
    <div class="w-full flex flex-col h-fit pr-4 overflow-hidden" x-bind:class="{ 'h-0!': !opened }">
        @if (isset($category->children))
            @foreach ($category->children as $category)
                <x-customer.categories.filter-panel-category-row :category="$category" :filters="$filters" />
            @endforeach
        @endif
    </div>
</div>