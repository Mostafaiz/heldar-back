@props(['title', 'name' => '', 'type' => 'text'])

<div x-id="['input-text']" class="input-con">
    <input type="{{ $type }}"
        class="peer outlined-input focus:border-primary! not-placeholder-shown:border-primary!" :id="$id('input-text')"
        placeholder="" wire:model="{{ $name }}" {{ $attributes }}>
    <label :for="$id('input-text')"
        class="input-label peer-focus:text-primary-dark! peer-not-placeholder-shown:text-primary-dark!">{{ $title }}</label>
    @error($name)
        <span class="error-message">{{ $message }}</span>
    @enderror
</div>
