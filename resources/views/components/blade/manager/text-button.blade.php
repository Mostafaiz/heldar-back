@props(['type' => 'button', 'value', 'class' => '', 'icon' => '', 'target' => ''])

<button type="{{ $type }}" class="button text {{ $class }}" {{ $attributes }}>
    @if ($icon != '')
        <i class="fa-solid fa-{{ $icon }}"></i>
    @endif
    {{ $value }}
    @if ($target != '')
        <span class="icon" wire:loading wire:target="{{ $target }}">
            <i class="fa-solid fa-spinner"></i>
        </span>
    @endif
</button>