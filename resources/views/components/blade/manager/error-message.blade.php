@props([])

<div class="error-alert z-[100]! font-normal!" x-data="{ showErrorMessage: false, message: ''}"
    @exception.window="showErrorMessage = true; message = $event.detail.message" x-init="$watch('showErrorMessage', value => {
            if (value) setTimeout(() => showErrorMessage = false, 2000)
        })" x-show="showErrorMessage" x-on:click="showErrorMessage = false" x-cloak
    x-transition:enter="error-alert enter-transition" x-transition:enter-start="error-alert enter-start"
    x-transition:enter-end="error-alert enter-end" x-transition:leave="error-alert leave-transition"
    x-transition:leave-start="error-alert leave-start" x-transition:leave-end="error-alert leave-end">
    <i class="fa-solid fa-exclamation"></i>
    <span x-text="message"></span>
</div>