<div class="opened-panel index-categories-panel" x-data="{ open: false, categoryId: null }">
    <div class="inner-container">
        <div class="items title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
            <div class="buttons-section">
                <a href="{{ route('manager.categories.create') }}" class="button filled" wire:navigate>افزودن
                    دسته‌بندی</a>
            </div>
        </div>
        <div class="items all-categories-con">
            <div class="categories-table">
                @if (count($categories) == 0)
                    <span class="empty">دسته‌بندی موجود نیست.</span>
                @else
                    @foreach ($categories as $category)
                        <livewire:components.manager.categories.categories-tree :category="$category" :depth="0.7"
                            :isChild="false" :key="now()->timestamp" />
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <x-blade.manager.confirm-message @click.outside="open = false" text="آیا از حذف دسته‌بندی اطمینان دارید؟" />
</div>

@assets
@vite(['resources/css/manager/categories-index.scss'])
@endassets