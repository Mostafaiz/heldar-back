@props([])

<div class="success-alert font-shabnam font-normal!" x-data="{ showSuccessMessage: false, message: '', route: null}"
    @success.window="showSuccessMessage = true; message = $event.detail.message; route = $event.detail.route" x-init="$watch('showSuccessMessage', value => {
            if (value) setTimeout(() => {
                showSuccessMessage = false;
                if (route != null) Livewire.navigate(route);
            }, 2000)
        })" x-show="showSuccessMessage" x-on:click="showSuccessMessage = false" x-cloak
    x-transition:enter="success-alert enter-transition" x-transition:enter-start="success-alert enter-start"
    x-transition:enter-end="success-alert enter-end" x-transition:leave="success-alert leave-transition"
    x-transition:leave-start="success-alert leave-start" x-transition:leave-end="success-alert leave-end">
    <i class="fa-solid fa-check"></i>
    <span x-text="message"></span>
</div>