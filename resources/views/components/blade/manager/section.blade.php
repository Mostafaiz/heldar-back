@props(['class' => ''])

<div class="section {{ $class }}" {{ $attributes }}>
    {{ $slot }}
</div>