@props(['value', 'type' => 'button'])

<button type="{{ $type }}" class="button outlined" {{ $attributes }}>
    {{ $value }}
</button>