<div class="back-cover expand" x-show="$wire.visible" x-transition.opacity x-cloak>
    <div class="delete-confirm-message" wire:click.outside="visible = false">
        <h1 class="title text-2xl!">
            <i class="fa-solid fa-trash-can text-[22px]!"></i>
            حذف {{ $keyword }}
        </h1>
        <span class="text">
            آیا از حذف {{ $keyword }} اطمینان دارید؟
        </span>

        @if ($content !== '')
            <div class="warning-content">
                <span class="warning-title">با تایید این فرایند، {{ $keyword }} از بخش‌های مربوطه حذف خواهد شد:</span>
                <p>{!! $content !!}</p>
            </div>
        @else
            <div class="warning-content gray">
                <span class="warning-title">این {{ $keyword }} به هیچ بخشی متصل نیست.</span>
            </div>
        @endif
        <div class="buttons-container">
            <x-blade.manager.text-button value="خیر" wire:click="visible = false" />
            <x-blade.manager.filled-button type="submit" value="بله" target="delete" wire:click="delete" />
        </div>
    </div>
</div>