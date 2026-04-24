<div x-show="showUpdateAddressModal" class="fixed inset-0 flex items-center justify-center bg-black/50 z-20" x-cloak>
    <div class="bg-white lg:rounded-xl w-full lg:w-100 p-5 h-dvh flex flex-col gap-5 lg:h-fit"
        x-on:click.away="showUpdateAddressModal = false"
        x-on:keydown.escape="showUpdateAddressModal = false; $wire.resetData()"
        x-on:notify.window="showUpdateAddressModal = false">
        <div class="w-full flex justify-between items-center">
            <div class="flex flex-nowrap items-center gap-2">
                <h2 class="font-shabnam font-[500] text-sm">ویرایش آدرس</h2>
                <i class="fa-solid fa-spinner animate-spin text-neutral" wire:loading></i>
            </div>
            <button class="size-7 cursor-pointer" x-on:click="showUpdateAddressModal = false; $wire.resetData()">
                <i class="fa-solid fa-xmark text-xl text-gray-500"></i>
            </button>
        </div>
        <form class="flex flex-col h-full gap-5" wire:submit.prevent="update">
            <input type="text" placeholder="نام آدرس"
                class="p-3 bg-gray-200 outline-none border-2 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                wire:model="form.name">
            @error('form.name')
                <span class="error-message -mt-3!">
                    *
                    {{ $message }}
                </span>
            @enderror
            <div class="flex gap-5">
                <div class="w-full flex flex-col">
                    <select wire:model="form.provinceId" class="w-full p-3 bg-gray-200 rounded-lg font-shabnam text-sm">
                        <option x-bind:value="null" selected hidden>استان</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                    @error('form.provinceId')
                        <span class="error-message">
                            *
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="w-full flex flex-col">
                    <select wire:model="form.cityId"
                        class="w-full p-3 bg-gray-200 rounded-lg font-shabnam text-sm disabled:text-neutral">
                        <option x-bind:value="null" selected hidden>
                            شهر
                        </option>
                        @if ($cities)
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" x-bind:selected="{{ $city->id }} == {{ $form->cityId ?? 0 }}">
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('form.cityId')
                        <span class="error-message">
                            *
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <textarea type="text" placeholder="آدرس کامل"
                class="p-3 bg-gray-200 outline-none border-2 max-h-80 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                wire:model="form.fullAddress"></textarea>
            @error('form.fullAddress')
                <span class="error-message -mt-3!">
                    *
                    {{ $message }}
                </span>
            @enderror
            <input type="text" placeholder="کدپستی"
                class="p-3 bg-gray-200 outline-none border-2 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                wire:model="form.zipcode">
            @error('form.zipcode')
                <span class="error-message -mt-3!">
                    *
                    {{ $message }}
                </span>
            @enderror
            <div class="mt-auto flex flex-nowrap gap-4">
                <button type="submit" x-on:success.window="showUpdateAddressModal = false"
                    class="w-2/3 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-shabnam cursor-pointer font-[500]"
                    wire:loading.attr="disabled" wire:target="update">
                    <span wire:loading.remove wire:target="update">تایید</span>
                    <i class="fa-solid fa-spinner animate-spin size-fit" wire:loading wire:target="update"></i>
                </button>
                <button type="button"
                    class="w-1/3 py-3 bg-gray-200 hover:bg-gray-300 transition rounded-lg font-shabnam cursor-pointer text-gray-500"
                    x-on:click="showUpdateAddressModal = false; $wire.resetData()">انصراف</button>
            </div>
        </form>
    </div>
</div>