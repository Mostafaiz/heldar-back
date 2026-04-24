@props(['value', 'type' => 'button', 'class' => ''])

<button type="{{ $type }}" class="button tonal {{ $class }}" {{ $attributes }}>
	{{ $value }}
</button>