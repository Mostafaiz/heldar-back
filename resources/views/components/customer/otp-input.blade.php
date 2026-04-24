<div x-data="{ otp: [...Array(6)].map((_, i) => $wire.value[i] ?? '') }" class="digits-container" x-init="$watch('otp', () => $wire.value = otp.join(''))">
    <input type="hidden" wire:model="value" maxlength="6" x-ref="input">
    @for ($index = 0; $index < 6; $index++)
        <input type="text" maxlength="1" placeholder=""
            class="digit-input focus:border-primary! not-placeholder-shown:border-primary!"
            x-model="otp[{{ $index }}]" {{ $index === 0 ? 'autofocus' : '' }} x-on:focus="$event.target.select()"
            x-ref="otp-digit-{{ $index }}" inputmode="numeric"
            x-on:input="if({{ $index }} < 5 && $event.target.value) $focusTo('otp-digit-{{ $index + 1 }}')"
            x-on:keydown="if({{ $index }} > 0 && $event.keyCode === 8) $focusTo('otp-digit-{{ $index - 1 }}')"
            x-on:paste.prevent="code = $event.clipboardData.getData('text'); if(/^\d{6}$/.test(code)) otp = code.split('') " />
    @endfor
</div>
