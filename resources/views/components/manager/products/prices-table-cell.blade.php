@props(['inputType' => 'text', 'model' => '', 'priceformat' => false, 'english' => false])

<div class="flex items-center gap-2 px-3 border-r-1 border-gray-300" x-data="{ editing: false }"
    x-bind:class="{ 'px-0!': editing }" x-on:click="editing = true">
    <i class="fa-solid fa-pen text-gray-400 text-sm cursor-pointer"
        x-on:click="editing = true; $nextTick(() => { $refs.input.select() })" x-show="!editing"></i>
    <input type="{{ $inputType }}"
        class="w-full h-full outline-none focus:border-2 border-gray-600 bg-white pr-2.5 rounded"
        @if ($model != '') x-model="{{ $model }}" @endif x-on:blur="editing = false" x-ref="input"
        @if ($priceformat) x-price-format @endif
        x-bind:class="{ 'font-[shabnam-en]!': {{ $english ? 'true' : 'false' }} }" {{ $attributes }}>
</div>
