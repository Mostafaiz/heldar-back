@props(['text'])

<div class="back-cover" x-show="open" x-transition.opacity x-cloak>
    <div class="delete-confirm-message" {{ $attributes }}>
        <h1 class="w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-red-700 text-[22px] flex gap-4 items-center">
            <i class="fa-solid fa-trash-can"></i>
            حذف دسته‌بندی
        </h1>
        <span class="text">{{ $text }}</span>
        <div class="buttons-container">
            <x-blade.manager.text-button value="خیر" class="cancel" x-on:click="open = false" />
            <x-blade.manager.text-button value="بله" class="ok"
                x-on:click="$wire.deleteCategory(categoryId); open = false" />
        </div>
    </div>
</div>