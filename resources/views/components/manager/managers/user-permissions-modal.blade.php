<div class="back-cover" x-show="showUserPermissionsModal" x-transition.opacity x-cloak>
    <div
        class="w-[calc(100%-100px)] max-w-200 h-fit bg-white rounded-2xl flex flex-col p-5 gap-5 shadow-xl overflow-auto">
        <div class="flex items-center w-full justify-between">
            <h1 class="font-[shabnam] font-[500] text-2xl m-0 p-0 flex items-center gap-3">
                دسترسی‌های مدیر
                <i class="fa-solid fa-spinner loading-icon text-[16px]" x-data="{ loading: false }"
                    x-on:get-manager-permissions.window="loading = true" x-on:stop-load.window="loading = false"
                    x-show="loading"></i>
            </h1>
            <div class="group size-10 flex items-center justify-center cursor-pointer hover:bg-gray-100 rounded-full transition"
                x-on:click="showUserPermissionsModal = false; $dispatch('reset-permissions-data')">
                <i class=" fa-solid fa-xmark text-gray-400 text-2xl"></i>
            </div>
        </div>
        <div class="grid grid-cols-[repeat(auto-fill,_minmax(250px,1fr))] gap-5">
            @foreach ($permissions as $permission)
                <div
                    class="h-10 border-1 border-gray-100 rounded-lg flex items-center justify-between py-7 px-4 shadow">
                    <span class="font-[shabnam] text-lg">{{ __("permissions.$permission->name") }}</span>
                    <label class="relative inline-flex items-center cursor-pointer" x-data="{ permissions: @entangle('currentPermissions') }">
                        <input type="checkbox" class="sr-only peer"
                            x-bind:checked="permissions.includes('{{ $permission->name }}')"
                            x-on:change="permissions.includes('{{ $permission->name }}') ? permissions.splice(permissions.indexOf('{{ $permission->name }}'), 1) : permissions.push('{{ $permission->name }}')">
                        <div
                            class="w-10 h-6 bg-gray-200 peer-checked:bg-primary rounded-full transition-colors duration-200">
                        </div>
                        <div
                            class="absolute left-1 top-1 size-4 bg-white rounded-full shadow transition-transform duration-200 transform peer-checked:translate-x-4">
                        </div>
                    </label>
                </div>
            @endforeach
        </div>
        <div class="flex justify-end gap-2">
            <x-blade.manager.text-button value="انصراف"
                x-on:click="showUserPermissionsModal = false; $dispatch('reset-permissions-data')" />
            <x-blade.manager.filled-button value="تایید" wire:click="update" target="update"
                x-on:success.window="showUserPermissionsModal = false;" />
        </div>
    </div>
</div>
