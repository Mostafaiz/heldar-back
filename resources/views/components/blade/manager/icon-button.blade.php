@props(['type' => 'button', 'class' => '', 'icon' => ''])

<button class="icon-button {{ $class }}" type="{{ $type }}" {{ $attributes }}>
    <i class="fa-solid {{ $icon }}"></i>
</button>