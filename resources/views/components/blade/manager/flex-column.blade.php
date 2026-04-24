@props(['id'=>''])

<div class="list-column" {{ $id ? 'id=' . $id : '' }}>
    {{ $slot }}
</div>