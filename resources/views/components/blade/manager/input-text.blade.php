@props([
    'title' => '',
    'type' => 'text',
    'name' => '',
    'containerclass' => '',
    'class' => '',
    'required' => false,
    'label' => '',
    'english' => false,
    'priceformat' => false,
    'onlydigits' => false,
])

<div x-id="['input-text']" class="input-con {{ $containerclass }}">
    <div class="w-full flex flex-nowrap h-10">
        <input type="{{ $type }}" @class([
            'outlined-input shrink-1! peer focus:border-primary! not-placeholder-shown:border-primary! not-focus:placeholder-shown:bg-primary-lighter!',
            $class,
            'rounded-l-none!' => $label != '',
            'font-[shabnam-en]!' => $english,
        ]) x-bind:id="$id('input-text')" placeholder=""
            @if ($name != '') wire:model="{{ $name }}" @endif
            @if ($priceformat) x-price-format="" @endif
            @if ($onlydigits) x-only-digits @endif {{ $attributes }}>
        @if ($title != '')
            <label :for="$id('input-text')"
                class="input-label peer-focus:text-primary-dark! peer-not-placeholder-shown:text-primary-dark! peer-not-focus:peer-placeholder-shown:bg-primary-lighter! gap-1! select-none">
                {{ $title }}
                @if ($required)
                    <span class="text-red-700 font-normal">*</span>
                @endif
            </label>
        @endif
        @if ($label != '')
            <div
                class="h-full bg-gray-50 flex items-center justify-center px-3 shrink-0 border-1 border-r-0 border-[#e2e2e2] text-gray-500 rounded-l-[5px] select-none font-[shabnam]">
                {{ $label }}
            </div>
        @endif
    </div>
    @error($name)
        <span class="error-message">{{ $message }}</span>
    @enderror
</div>
