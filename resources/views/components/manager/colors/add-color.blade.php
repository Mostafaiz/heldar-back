<form wire:submit.prevent="store" class="add-color-form">

    <x-blade.manager.input-text name="form.name" title="نام رنگ" id="color-name-input" />

    <div class="input-con color-code-con">
        <span>انتخاب رنگ: </span>
        <input type="color" id="color-code-input" wire:model="form.code" style="display: none;">
        <label class="color-code-label" for="color-code-input" :style="{ background: $wire.form.code }"></label>
        <span id="color-code-span" x-text="$wire.form.code"></span>
    </div>
    @error('form.code')
        <span class="error-message color">
            {{ $message }}
        </span>
    @enderror
    <div class="input-con submit-button-con">
        <x-blade.manager.filled-button type="submit" value="ثبت رنگ" target="store" />
    </div>
</form>