@props(['title', 'class' => '', 'options' => []])

<select class="{{ $class }} outlined-input" {{ $attributes }}>
	<option value="" selected hidden>دسته‌بندی والد</option>
	<option value="">بدون دسته‌بندی والد</option>
	@foreach ($options as $option)
		<livewire:components.manager.categories.category-row :category="$option" wire:key="option-row-{{ $option->id }}" />
	@endforeach
</select>