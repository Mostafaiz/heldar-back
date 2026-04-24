<div class="w-full max-w-[1366px] px-4 2xl:px-0 flex flex-col gap-5" x-data="{ showOptionsMenu: false, showProfileModal: false, showAddAddressModal: false, showUpdateAddressModal: false }"
    x-on:keydown.escape="showProfileModal = false; $wire.resetProfileData()" x-init="if (localStorage.getItem('openProfile')) {
        showProfileModal = true;
        localStorage.removeItem('openProfile');
        $nextTick(() => $refs.nameInput.focus());
    }">
    @if (auth()->user())
        <div class="bg-white border border-gray-200 shadow-md rounded-xl flex flex-col">
            <div class="h-20 px-5 flex items-center justify-between relative">
                <div class="flex flex-col">
                    <h2 class="font-shabnam font-[500] text-black">
                        @if ($form->name != '' && $form->family != '')
                            {{ $form->name }}
                            {{ $form->family }}
                        @else
                            <span>بدون نام</span>
                        @endif
                    </h2>
                    <p class="text-gray-600 font-shabnam font-[500] text-sm">{{ $phone }}</p>
                </div>
                <button class="flex items-center justify-center text-gray-500 bg-white rounded-lg w-6 h-6 cursor-pointer"
                    x-on:click="showOptionsMenu = true">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <div class="flex flex-col w-40 border border-gray-200 shadow-md rounded-lg bg-white absolute left-5 top-15 x-cloak"
                    x-show="showOptionsMenu" x-on:click.outside="showOptionsMenu = false"
                    x-on:click="showOptionsMenu = false" x-cloak>
                    <button
                        class="w-full px-4 py-3 hover:bg-gray-100 text-sm flex items-center gap-3 cursor-pointer font-shabnam text-neutral-dark transition-colors"
                        x-on:click="showProfileModal = true; $nextTick(() => $refs.nameInput.focus())">
                        <i class="fa-solid fa-pen text-xs text-gray-500"></i>
                        ویرایش پروفایل
                    </button>
                    <button wire:click="logout"
                        class="w-full px-4 py-3 hover:bg-red-50 text-sm flex items-center gap-3 cursor-pointer font-shabnam text-red-600 transition-colors">
                        <i class="fa-solid fa-right-from-bracket text-xs text-red-500"></i>
                        خروج از حساب
                    </button>
                </div>
            </div>
            @if ($isManager)
                <hr class="text-gray-200">
                <a href="{{ route('manager.dashboard.index') }}" target="_blank"
                    class="px-5 py-4 font-shabnam text-sm cursor-pointer bg-info/10 text-info font-[500] transition flex items-center gap-4">
                    <div class="w-5 h-full flex items-center justify-center">
                        <i class="fa-solid fa-sliders text-base"></i>
                    </div>
                    <span>پنل مدیریت</span>
                </a>
            @endif
            <hr class="text-gray-200">
            <a href="{{ route('customer.orders') }}" wire:navigate
                class="px-5 py-4 font-shabnam text-sm cursor-pointer hover:bg-neutral-light transition flex items-center gap-4">
                <div class="w-5 h-full flex items-center justify-center">
                    <i class="fa-light fa-basket-shopping text-base text-gray-600"></i>
                </div>
                <span>سفارش‌های من</span>
            </a>
            <hr class="text-gray-200">
            <a href="{{ route('customer.demands') }}" wire:navigate
                class="px-5 py-4 font-shabnam text-sm cursor-pointer hover:bg-neutral-light transition flex items-center gap-4">
                <div class="w-5 h-full flex items-center justify-center">
                    <i class="fa-light fa-bell text-base text-gray-600"></i>
                </div>
                <span>درخواست‌های من</span>
            </a>
        </div>

        <div class="bg-white border border-gray-200 shadow-md rounded-xl p-5 flex flex-col gap-5">
            <div class="flex justify-between items-center">
                <h3 class="font-shabnam font-[700] text-sm">آدرس‌ها</h3>
                <button
                    class="bg-primary-light/20 border border-primary text-primary-dark px-4 py-2 rounded-lg font-shabnam font-[500] cursor-pointer text-sm"
                    x-on:click="showAddAddressModal = true">
                    + افزودن آدرس
                </button>
            </div>
            <div class="flex flex-col gap-4 font-shabnam">
                @if (count($addresses))
                    @foreach ($addresses as $address)
                        <div class="border border-gray-200 rounded-lg p-5 bg-gray-50 flex flex-col gap-3">
                            <div class="flex justify-between items-center">
                                <span class="font-shabnam font-[500] flex gap-2">
                                    <i class="fa-solid fa-location-dot text-sm text-gray-500"></i>
                                    <span class="font-shabnam font-[500]">{{ $address->name }}</span>
                                </span>
                                <div class="flex gap-3">
                                    <button
                                        class="flex items-center justify-center rounded-md bg-gray-200 w-6 h-6 sm:w-8 sm:h-8 cursor-pointer"
                                        x-on:click="showUpdateAddressModal = true; $wire.dispatch('load-address-for-edit', [{{ $address->id }}])">
                                        <i class="fa-solid fa-pen text-xs text-gray-500"></i>
                                    </button>
                                    <button
                                        class="flex items-center justify-center rounded-md bg-red-200 w-6 h-6 sm:w-8 sm:h-8 cursor-pointer"
                                        wire:click="deleteAddress({{ $address->id }})">
                                        <i class="fa-solid fa-trash text-xs text-red-500"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <p class="text-gray-600 text-sm truncate">{{ $address->fullAddress }}</p>
                                <p class="text-gray-600 text-sm truncate">کد پستی: {{ $address->zipcode }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="w-full flex justify-center">
                        <span class="w-fit text-sm text-gray-800">هیچ آدرسی موجود نیست.</span>
                    </div>
                @endif
            </div>
        </div>

        <livewire:pages.customer.profile.add-address />

        <div class="fixed w-full h-full bg-black/40 top-0 right-0 z-20 flex items-center justify-center"
            x-show="showProfileModal" x-cloak>
            <div class="flex p-5 bg-white w-full lg:w-100 h-dvh overflow-auto lg:h-fit lg:max-h-[calc(100%-50px)] lg:rounded-xl z-10 flex-col gap-5"
                x-on:click.outside="showProfileModal = false" x-on:notify.window="showProfileModal = false"
                style="scrollbar-width: thin" x-cloak>
                <div class="w-full flex justify-between items-center">
                    <h2 class="font-shabnam font-[500] text-sm">ویرایش پروفایل</h2>
                    <button class="size-7 cursor-pointer" x-on:click="showProfileModal = false">
                        <i class="fa-solid fa-xmark text-xl text-gray-500"></i>
                    </button>
                </div>
                <form class="flex flex-col w-full gap-5 h-full"
                    wire:submit.prevent="{{ count($addresses) ? 'submit' : 'submitWithAddress' }}">
                    <input type="text" placeholder="نام"
                        class="p-3 bg-gray-200 outline-none border-2 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                        wire:model="form.name" x-ref="nameInput">
                    @error('form.name')
                        <span class="text-xs font-shabnam font-[500] text-error -mt-3">
                            *
                            {{ $message }}
                        </span>
                    @enderror
                    <input type="text" placeholder="نام خانوادگی"
                        class="p-3 bg-gray-200 outline-none border-2 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                        wire:model="form.family">
                    @error('form.family')
                        <span class="text-xs font-shabnam font-[500] text-error -mt-3">
                            *
                            {{ $message }}
                        </span>
                    @enderror
                    @if (!count($addresses))
                        <div class="w-full">
                            <h2 class="font-shabnam font-[500] text-sm">افزودن آدرس</h2>
                        </div>
                        <input type="text" placeholder="نام آدرس"
                            class="p-3 bg-gray-200 outline-none border-2 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                            wire:model="addressForm.name">
                        @error('addressForm.name')
                            <span class="error-message -mt-3!">
                                *
                                {{ $message }}
                            </span>
                        @enderror
                        <div class="flex gap-5">
                            <div class="w-full flex flex-col">
                                <select wire:model="addressForm.provinceId"
                                    class="w-full p-3 bg-gray-200 rounded-lg font-shabnam text-sm"
                                    wire:change="loadCities">
                                    <option x-bind:value="null" selected hidden>استان</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('addressForm.provinceId')
                                    <span class="error-message">
                                        *
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="w-full flex flex-col">
                                <select wire:model="addressForm.cityId"
                                    class="w-full p-3 bg-gray-200 rounded-lg font-shabnam text-sm disabled:text-neutral"
                                    @disabled($addressForm->provinceId === null)>
                                    <option x-bind:value="null" selected hidden>شهر</option>
                                    @if ($cities)
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('addressForm.cityId')
                                    <span class="error-message">
                                        *
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <textarea type="text" placeholder="آدرس کامل"
                            class="p-3 bg-gray-200 outline-none border-2 max-h-80 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                            wire:model="addressForm.fullAddress"></textarea>
                        @error('addressForm.fullAddress')
                            <span class="error-message -mt-3!">
                                *
                                {{ $message }}
                            </span>
                        @enderror
                        <input type="text" placeholder="کدپستی "
                            class="p-3 bg-gray-200 outline-none border-2 border-transparent focus:border-primary rounded-lg font-shabnam text-sm"
                            wire:model="addressForm.zipcode">
                        @error('addressForm.zipcode')
                            <span class="error-message -mt-3!">
                                *
                                {{ $message }}
                            </span>
                        @enderror
                    @endif
                    <div class="mt-auto flex flex-nowrap gap-4">
                        <button type="submit"
                            class="w-2/3 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-shabnam cursor-pointer disabled:cursor-default font-[500]"
                            wire:loading.attr="disabled"
                            wire:target="{{ count($addresses) ? 'submit' : 'submitWithAddress' }}">
                            <span wire:loading.remove
                                wire:target="{{ count($addresses) ? 'submit' : 'submitWithAddress' }}">تایید</span>
                            <i class="fa-solid fa-spinner animate-spin" wire:loading
                                wire:target="{{ count($addresses) ? 'submit' : 'submitWithAddress' }}"></i>
                        </button>
                        <button type="button"
                            class="w-1/3 py-3 bg-gray-200 hover:bg-gray-300 transition rounded-lg font-shabnam cursor-pointer text-gray-500"
                            x-on:click="showProfileModal = false">انصراف</button>
                    </div>
                </form>
            </div>
        </div>

        <livewire:components.customer.profile.update-address />
    @else
        <div class="w-full flex items-center justify-center flex-col mt-10 gap-5">
            <span class="font-shabnam">
                هنوز به حساب کاربری خود وارد نشده‌اید.
            </span>
            <a href="{{ route('login') }}"
                class="bg-primary p-3 rounded-lg font-shabnam font-[500] text-white w-fit cursor-pointer hover:bg-primary-dark transition-colors">
                ورود | ثبت نام
            </a>
        </div>
    @endif
    <div class="w-full h-15"></div>
</div>
