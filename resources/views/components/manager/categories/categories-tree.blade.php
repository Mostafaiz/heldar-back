<div class="category-row {{ $isChild ? 'child' : '' }}"
    style="background-color: rgb(84, 136, 212, {{ 0.1 * $depth }});">
    <div class="details">
        <div class="category-cell name">{{ $category->name }}</div>
        <div class="category-cell">
            <a href="{{ route('manager.categories.edit', $category->id) }}" wire:navigate>
                <x-blade.manager.icon-button class="edit text-xs" icon="fa-pen" />
            </a>
        </div>
        <div class="category-cell">
            <x-blade.manager.icon-button class="delete text-sm" icon="fa-trash-can"
                x-on:click="open = true, categoryId = {{ $category->id }}" />
        </div>
    </div>
    @if ($category->children && $category->children->count())
        @foreach ($category->children as $child)
            <livewire:components.manager.categories.categories-tree :category="$child" :depth="$depth + 0.2" :isChild="true"
                wire:key="category-{{ $child->id }}" />
        @endforeach
    @endif
</div>