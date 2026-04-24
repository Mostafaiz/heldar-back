@use('\App\Enums\ProductLevelsEnum')

<div class="back-cover" x-show="showUserLevelModal" x-on:success.window="hideModal" x-data="{
    hideModal() {
        showUserLevelModal = false;
        $wire.resetData()
    }
}"
    x-init="$watch('showUserLevelModal', value => {
        if (value) $nextTick(() => $refs.phoneInput.focus());
    })" x-transition.opacity x-cloak>
    <div class="w-100 h-fit bg-white rounded-2xl font-[shabnam] flex flex-col gap-5 p-5 box-border flex-nowrap"
        x-on:click.outside="hideModal" x-on:keydown.escape="hideModal">
        <h1 class="p-0 m-0 text-2xl font-[500]">تغییر سطح کاربر</h1>
        <div class="w-full flex flex-nowrap gap-5">
            <div class="w-full">
                <input type="radio" class="peer hidden" wire:model.live="selectedLevel" name="level"
                    value="{{ ProductLevelsEnum::GOLD->value }}">
                <label
                    class="w-full border-2 border-gray-200 peer-checked:border-primary rounded-lg peer-checked:bg-primary-lighter bg-gray-50 h-13 flex items-center justify-center font-shabnam text-lg gap-3 cursor-pointer"
                    x-on:click="$wire.set('selectedLevel', 'gold')">
                    طلایی
                </label>
            </div>
            <div class="w-full">
                <input type="radio" class="peer hidden" wire:model.live="selectedLevel" name="level"
                    value="{{ ProductLevelsEnum::SILVER->value }}">
                <label
                    class="w-full border-2 border-gray-200 peer-checked:border-primary rounded-lg peer-checked:bg-primary-lighter bg-gray-50 h-13 flex items-center justify-center font-shabnam text-lg gap-3 cursor-pointer"
                    x-on:click="$wire.set('selectedLevel', 'silver')">
                    نقره‌ای
                </label>
            </div>
            <div class="w-full">
                <input type="radio" class="peer hidden" wire:model.live="selectedLevel" name="level"
                    value="{{ ProductLevelsEnum::BORONZE->value }}">
                <label
                    class="w-full border-2 border-gray-200 peer-checked:border-primary rounded-lg peer-checked:bg-primary-lighter bg-gray-50 h-13 flex items-center justify-center font-shabnam text-lg gap-3 cursor-pointer"
                    x-on:click="$wire.set('selectedLevel', 'boronze')">
                    برنزی
                </label>
            </div>
        </div>
    </div>
</div>
