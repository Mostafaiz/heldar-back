<div class="back-cover" x-show="$wire.visible" x-transition.opacity x-cloak>
    @if(isset($image))
        <div class="image-details-modal-container" wire:click.outside="hideModal">
            <div class="image-view-container">
                <img src="{{ asset('storage/' . $image->path) }}" class="image">
            </div>
            <div class="image-details-container">
                <p class="break-words whitespace-normal"><span>نام: </span>{{ $image->name . '.' . $image->mimeType }}</p>
                <p><span>برچسب: </span>{{ $image->alt }}</p>
                <p><span>تاریخ افزودن: </span><span class="date-time">{{ jalali($image->createdAt, 'H:i - Y/m/d') }}</span>
                </p>
            </div>
            <x-blade.manager.filled-button type="button" value="بستن" wire:click="hideModal" />
        </div>
    @endif
</div>