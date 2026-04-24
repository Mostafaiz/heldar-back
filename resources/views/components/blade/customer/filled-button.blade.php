@props(['value', 'type' => 'button'])

<button type="{{ $type }}" class="filled-button" {{ $attributes }}>
	{{ $value }}
</button>