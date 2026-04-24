@use('\App\Enums\PermissionEnum')

<div class="opened-panel flex flex-col justify-start items-start" x-data="{ showUserPermissionsModal: false, showNewAdminModal: false, showCurrentManagerPermissionsModal: false, showDeleteMessage: false, selectedManagerForDelete: null }">
    <div class="inner-container">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
            <x-blade.manager.filled-button value="افزودن مدیر" icon="plus" x-on:click="showNewAdminModal = true" />
        </x-blade.manager.section>

        <x-blade.manager.section class="shrink-1! w-full overflow-auto!">
            <div class="w-full h-fit max-h-full grid grid-cols-[repeat(auto-fill,_minmax(300px,1fr))] gap-5">
                <div class="w-full h-fit border-1 border-gray-200 rounded-xl flex flex-col justify-start items-start p-4 gap-3 font-[shabnam] box-border shadow-md relative shrink-1"
                    x-data="{ showOptionsMenu: false, showPermissions() { showCurrentManagerPermissionsModal = true;
                            $dispatch('get-manager-permissions', [{{ $currentManager->id }}]);
                            $dispatch('start-load') } }">
                    <div class="w-full flex justify-between items-center">
                        <div class="flex gap-4 items-center">
                            <div
                                class="flex items-center justify-center bg-gray-300 size-12 rounded-full text-xl text-gray-600">
                                <i class="fa-solid fa-user text-gray-500"></i>
                            </div>
                            <div class="text-xl font-[500] flex flex-col ga-2">
                                <span>
                                    @if ($currentManager->name != '' && $currentManager->family != '')
                                        <span class="line-clamp-1 w-fit"
                                            title="{{ $currentManager->name . ' ' . $currentManager->family }}">
                                            <i class="fa-solid fa-star text-yellow-500 text-[16px]"></i>
                                            {{ $currentManager->name }}
                                            {{ $currentManager->family }}
                                        </span>
                                    @else
                                        <span>کاربر ادمین</span>
                                    @endif
                                </span>
                                <span class="font-normal text-sm text-gray-600">{{ $currentManager->username }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex gap-2 flex-wrap items-center">
                        @if (count($currentManager->permissions) == 0)
                            <span
                                class="text-sm font-normal w-fit h-fit px-3 py-1 flex gap-2 box-border bg-yellow-100 border-1 border-yellow-700 rounded-full text-yellow-800 cursor-pointer"
                                x-on:click="showPermissions">
                                <i class="fa-solid fa-sliders text-[12px] mt-1 -mb-1"></i>
                                <span>بدون دسترسی</span>
                            </span>
                        @elseif (count($currentManager->permissions) == count(PermissionEnum::cases()))
                            <span
                                class="text-sm font-normal w-fit h-fit px-3 py-1 flex gap-2 box-border bg-green-100 border-1 border-green-800 rounded-full text-green-800 cursor-pointer"
                                x-on:click="showPermissions">
                                <i class="fa-solid fa-sliders text-[12px] mt-1 -mb-1"></i>
                                <span>{{ count($currentManager->permissions) - 3 }} دسترسی</span>
                            </span>
                        @else
                            <span
                                class="text-sm font-normal w-fit h-fit px-3 py-1 flex gap-2 box-border bg-blue-100 border-1 border-blue-800 rounded-full text-blue-800 cursor-pointer"
                                x-on:click="showPermissions">
                                <i class="fa-solid fa-sliders text-[12px] mt-1 -mb-1"></i>
                                <span>{{ count($currentManager->permissions) }} دسترسی</span>
                            </span>
                        @endif
                    </div>
                </div>
                @foreach ($managers as $manager)
                    <div class="w-full h-fit border-1 border-gray-200 rounded-xl flex flex-col justify-start items-start p-4 gap-3 font-[shabnam] box-border shadow-md relative shrink-1"
                        x-data="{ showOptionsMenu: false, showPermissions() { showUserPermissionsModal = true;
                                $dispatch('get-manager-permissions', [{{ $manager->id }}]);
                                $dispatch('start-load') } }">
                        <div class="w-full flex justify-between items-center">
                            <div class="flex gap-4 items-center">
                                <div
                                    class="flex items-center justify-center bg-gray-300 size-12 rounded-full text-xl text-gray-600">
                                    <i class="fa-solid fa-user text-gray-500"></i>
                                </div>
                                <div class="text-xl font-[500] flex flex-col ga-2">
                                    <span>
                                        @if ($manager->name != '' && $manager->family != '')
                                            <span class="line-clamp-1"
                                                title="{{ $manager->name . ' ' . $manager->family }}">
                                                <b class="text-blue-600">#{{ $manager->id }}</b>
                                                {{ $manager->name }}
                                                {{ $manager->family }}
                                            </span>
                                        @else
                                            <span>کاربر ادمین</span>
                                        @endif
                                    </span>
                                    <span class="font-normal text-sm text-gray-600">{{ $manager->username }}</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-ellipsis-vertical text-lg text-gray-600 text-md hover:bg-gray-200 rounded-full cursor-pointer size-8 leading-8! text-center transition"
                                x-on:click="showOptionsMenu = true"></i>
                        </div>
                        <div class="w-full flex gap-2 flex-wrap items-center">
                            @if (count($manager->permissions) == 0)
                                <span
                                    class="text-sm font-normal w-fit h-fit px-3 py-1 flex gap-2 box-border bg-yellow-100 border-1 border-yellow-700 rounded-full text-yellow-800 cursor-pointer"
                                    x-on:click="showPermissions">
                                    <i class="fa-solid fa-sliders text-[12px] mt-1 -mb-1"></i>
                                    <span>بدون دسترسی</span>
                                </span>
                            @elseif (count($manager->permissions) == count(PermissionEnum::cases()))
                                <span
                                    class="text-sm font-normal w-fit h-fit px-3 py-1 flex gap-2 box-border bg-green-100 border-1 border-green-800 rounded-full text-green-800 cursor-pointer"
                                    x-on:click="showPermissions">
                                    <i class="fa-solid fa-sliders text-[12px] mt-1 -mb-1"></i>
                                    <span>{{ count($manager->permissions) - 3 }} دسترسی</span>
                                </span>
                            @else
                                <span
                                    class="text-sm font-normal w-fit h-fit px-3 py-1 flex gap-2 box-border bg-blue-100 border-1 border-blue-800 rounded-full text-blue-800 cursor-pointer"
                                    x-on:click="showPermissions">
                                    <i class="fa-solid fa-sliders text-[12px] mt-1 -mb-1"></i>
                                    <span>{{ count($manager->permissions) - 3 }} دسترسی</span>
                                </span>
                            @endif
                        </div>

                        <div class="absolute bg-white w-fit h-fit flex flex-col gap-1 overflow-hidden rounded-xl shadow-2xl top-14 left-4 border-1 border-gray-200 box-border p-1.5 z-2 bg-white"
                            x-show="showOptionsMenu" x-on:click="showOptionsMenu = false"
                            x-on:click.outside="showOptionsMenu = false" x-transition x-cloak>
                            <button type="button"
                                class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                x-on:click="showPermissions">
                                <i class="fa-solid fa-sliders text-sm text-gray-400 ml-2"></i>
                                تغییر دسترسی‌ها
                            </button>
                            <button type="button"
                                class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-red-50 transition"
                                x-on:click="showDeleteMessage = true; selectedManagerForDelete = {{ $manager->id }}">
                                <i
                                    class="fa-solid fa-trash-can text-sm text-gray-400 ml-2 group-hover:text-red-500 transition"></i>
                                برکناری از مدیریت
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-blade.manager.section>

        <div class="back-cover" x-show="showDeleteMessage" x-cloak>
            <div class="w-100 h-fit flex flex-col bg-white p-5 gap-5 rounded-2xl z-20" x-data="{ hideModal() { showDeleteMessage = false;
                    setTimeOut(() => selectedManagerForDelete = null, 1500) } }"
                x-on:click.outside="hideModal()" x-on:success.window="hideModal()">
                <h2 class="w-full h-auto m-0 p-0 font-[500] font-[shabnam] text-red-700 text-[22px]">
                    <i class="fa-solid fa-trash-can ml-3"></i>
                    حذف مدیر
                </h2>
                <p class="w-full h-auto m-0 p-0 font-[shabnam] text-[17px]">
                    آیا از برکناری این مدیر اطمینان دارید؟
                </p>
                <div class="flex flex-nowrap gap-[10px] justify-end items-center w-full">
                    <x-blade.manager.text-button value="خیر" x-on:click="hideModal()" />
                    <x-blade.manager.text-button value="بله" target="delete"
                        wire:click="delete(selectedManagerForDelete)" />
                </div>
            </div>
        </div>

        <x-manager.managers.add-manager-modal />
        <livewire:components.manager.managers.user-permissions-modal />
        <livewire:components.manager.managers.current-manager-permissions-modal />
    </div>
</div>
