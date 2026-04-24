<div class="fixed w-full h-full bg-black/40 top-0 right-0 z-20 flex items-center justify-center max-sm:px-4 backdrop:blur-2xl"
    x-show="showDeleteMessage" x-on:click.outside="showDeleteMessage = false; selectedOrderForDeleteImage = null"
    x-cloak>
    <div class="flex p-5 bg-white h-fit rounded-xl z-10 flex-col gap-5 sm:min-w-100 max-sm:w-full">
        <div class="flex items-center gap-3 text-error">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <h2 class="font-shabnam font-[500] max-md:text-sm">حذف تصویر</h2>
        </div>
        <p class="font-shabnam">آیا از حذف تصویر اطمینان دارید؟</p>
        <div class="w-full flex gap-5 flex-nowrap">
            <button type="button"
                class="w-full py-3 bg-error not-disabled:hover:bg-red-600 text-white rounded-lg font-shabnam disabled:bg-neutral cursor-pointer font-[500]"
                x-on:click="$wire.deleteImage(selectedOrderForDeleteImage)" wire:loading.attr="disabled"
                x-on:notify.window="showDeleteMessage = false; selectedOrderForDeleteImage = null">
                <span wire:loading.remove>بله</span>
                <i class="fa-solid fa-spinner animate-spin" wire:loading></i>
            </button>
            <button type="button"
                class="w-full py-3 bg-neutral-light hover:bg-gray-200 text-black rounded-lg font-shabnam cursor-pointer font-[500]"
                x-on:click="showDeleteMessage = false; selectedOrderForDeleteImage = null">
                خیر
            </button>
        </div>
    </div>
</div>