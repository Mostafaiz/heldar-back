<form wire:submit.prevent="store" class="add-attribute-form">
    <x-blade.manager.input-text title="نام مجموعه ویژگی‌ها" name="form.name" />
    <h3>ویژگی‌ها</h3>
    <div class="keys-inputs-con" id="keys-inputs-con">
        @foreach ($form->attributes as $index => $attribute)
            <x-blade.manager.input-text :key="$index" title="ویژگی {{ $index + 1 }}" id="product-attribute-{{ $index + 1 }}"
                name="form.attributes.{{ $index }}" wire:keydown.enter="addAttributeInput" containerclass="column" />
        @endforeach
        <div class="input-con">
            <i class="fa-solid fa-plus-circle plus" wire:click="addAttributeInput"></i>
            <div class="outlined-input disabled" wire:click="addAttributeInput"></div>
        </div>
        @error('form.attributes')
            <span class="error-message">
                {{ $message }}
            </span>
        @enderror
    </div>
    <x-blade.manager.filled-button type="submit" value="ثبت ویژگی‌ها" target="store" tabindex="0" />
</form>

<script>
    window.addEventListener('focus', () => {
        const container = document.getElementById('keys-inputs-con');
        setTimeout(() => {
            const lastInput = container.lastElementChild.previousElementSibling;
            if (lastInput) {
                lastInput.children[0].focus();
            };
        }, 1);
    });
</script>