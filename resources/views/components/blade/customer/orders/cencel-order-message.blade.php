<div class="fixed w-full h-full bg-black/40 top-0 right-0 z-20 flex items-center justify-center max-sm:px-4 backdrop:blur-2xl"
    x-show="showCancelOrderMessage" x-cloak>
    <div class="flex p-5 bg-white h-fit rounded-xl z-10 flex-col gap-5 sm:min-w-100 max-sm:w-full"
        x-on:click.away="showCancelOrderMessage = false; selectedOrderForCancel = null">
        <div class="flex items-center gap-3 text-error">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <h2 class="font-shabnam font-[500] max-md:text-sm">انصراف از سفارش</h2>
        </div>
        <div class="font-shabnam flex flex-col gap-2">
            <p>
                آیا می‌خواهید سفارش را لغو کنید؟
            </p>
            <p>
                این عمل قابل بازگشت نخواهد بود.
            </p>
        </div>
        <div class="w-full flex gap-5 flex-nowrap">
            <button type="button"
                class="w-full py-3 bg-error not-disabled:hover:bg-red-600 text-white rounded-lg font-shabnam disabled:bg-neutral cursor-pointer font-[500]"
                x-on:click="$wire.cancelOrder(selectedOrderForCancel)" wire:loading.attr="disabled"
                x-on:notify.window="showCancelOrderMessage = false; selectedOrderForCancel = null">
                <span wire:loading.remove>بله</span>
                <i class="fa-solid fa-spinner animate-spin" wire:loading></i>
            </button>
            <button type="button"
                class="w-full py-3 bg-neutral-light hover:bg-gray-200 text-black rounded-lg font-shabnam cursor-pointer font-[500]"
                x-on:click="showCancelOrderMessage = false; selectedOrderForCancel = null">
                خیر
            </button>
        </div>
    </div>
</div>