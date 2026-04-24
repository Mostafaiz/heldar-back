@props(['title', 'value', 'name' => '', 'class' => ''])

<div x-id="['input-text']" class="textarea-con">
    <textarea type="text"
        class="textarea not-focus:placeholder-shown:bg-primary-lighter! focus:border-primary! not-placeholder-shown:border-primary! peer {{ $class }}"
        :id="$id('input-text')" placeholder="" wire:model="{{ $name }}" {{ $attributes }}>{{ $value ?? '' }}</textarea>
    <label :for="$id('input-text')"
        class="input-label peer-focus:text-primary-dark! peer-not-placeholder-shown:text-primary-dark! peer-not-focus:peer-placeholder-shown:bg-primary-lighter!">{{ $title }}</label>
</div>
