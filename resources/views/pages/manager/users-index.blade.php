@use('\App\Enums\PermissionEnum')
@use('\App\Enums\UserRoleEnum')
@use('\App\Enums\ProductLevelsEnum')

<div class="opened-panel flex flex-col justify-start items-start" x-data="{ showUserLevelModal: false, showUserTransactionsModal: false, showTransactionDetailsModal: false, showAcceptTransactionWarning: false, showRejectTransactionWarning: false, selectedTransactionForAorR: null }">
    <div class="inner-container flex! flex-col!">
        <x-blade.manager.section class="title-con h-25">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>

        <x-blade.manager.section class="h-fit">
            <x-blade.manager.section-title title="جستجو و فیلتر" />
            <div class="flex flex-nowrap gap-1 w-100 items-center">
                <x-blade.manager.input-text title="جستجو" class="max-w-92!" wire:model.live.debounce.500ms="searchText" />
                <i class="fa-solid fa-spinner animate-spin size-fit" wire:loading></i>
            </div>
            <div class="font-[shabnam] text-gray-500 text-sm flex items-center flex-nowrap gap-2">
                <i class="fa-solid fa-info-circle"></i>
                <span>
                    جستجو در کاربران بر اساس نام یا شماره تلفن
                </span>
            </div>
        </x-blade.manager.section>

        <x-blade.manager.section class="shrink-1! h-full overflow-auto!">
            <div class="w-full h-fit max-h-full grid grid-cols-[repeat(auto-fill,_minmax(300px,1fr))] gap-5">
                @foreach ($users as $user)
                    <div class="w-full h-fit border-1 border-gray-200 rounded-xl flex flex-col justify-start items-start p-4 gap-3 font-[shabnam] box-border shadow-md relative shrink-1"
                        x-data="{
                            showOptionsMenu: false,
                            showPermissions() {
                                showUserPermissionsModal = true;
                                $dispatch('get-manager-permissions', [{{ $user->id }}]);
                                $dispatch('start-load')
                            }
                        }">
                        <div class="w-full flex justify-between items-center">
                            <div class="flex gap-4 items-center">
                                <div
                                    class="flex items-center justify-center bg-gray-300 size-12 rounded-full text-xl text-gray-600">
                                    <i class="fa-solid fa-user text-gray-500"></i>
                                </div>
                                <div class="text-xl font-[500] flex flex-col ga-2">
                                    <div>
                                        @if ($user->name != '' && $user->family != '')
                                            <span class="line-clamp-1 w-fit"
                                                title="{{ $user->name . ' ' . $user->family }}">
                                                <b class="text-blue-600">#{{ $user->id }}</b>
                                                {!! highlight($user->name . ' ' . $user->family, $searchText) !!}
                                            </span>
                                        @else
                                            <span class="text-gray-500">بدون نام</span>
                                        @endif
                                    </div>
                                    <span class="font-normal text-sm text-gray-600">
                                        {!! highlight($user->username, $searchText) !!}
                                    </span>
                                </div>
                            </div>
                            <button
                                class="aspect-square hover:bg-gray-200 rounded-full cursor-pointer transition shrink-0 size-8 flex items-center justify-center">
                                <i class="fa-solid fa-ellipsis-vertical text-lg text-gray-600 text-center transition"
                                    x-on:click="showOptionsMenu = true"></i>
                            </button>
                        </div>
                        <div class="w-full flex gap-2 flex-wrap items-center">
                            @if ($user->role === UserRoleEnum::MANAGER)
                                <span
                                    class="text-sm font-[500] w-fit h-fit px-3 py-1 flex gap-2 box-border bg-blue-500 rounded-full text-white">
                                    <i class="fa-solid fa-star text-[12px] mt-1 -mb-1"></i>
                                    <span>ادمین</span>
                                </span>
                            @endif
                            @if ($user->level === ProductLevelsEnum::GOLD->value)
                                <span
                                    class="text-sm font-[500] w-fit h-fit px-3 py-1 flex gap-2 box-border bg-yellow-500 rounded-full text-white cursor-pointer"
                                    x-on:click="showUserLevelModal = true; $dispatch('load-user-level', [{{ $user->id }}])">
                                    <i class="fa-solid fa-layer-group text-[12px] mt-1 -mb-1"></i>
                                    <span>طلایی</span>
                                </span>
                            @elseif ($user->level === ProductLevelsEnum::SILVER->value)
                                <span
                                    class="text-sm font-[500] w-fit h-fit px-3 py-1 flex gap-2 box-border bg-green-600 rounded-full text-white cursor-pointer"
                                    x-on:click="showUserLevelModal = true; $dispatch('load-user-level', [{{ $user->id }}])">
                                    <i class="fa-solid fa-layer-group text-[12px] mt-1 -mb-1"></i>
                                    <span>نقره‌ای</span>
                                </span>
                            @elseif ($user->level === ProductLevelsEnum::BORONZE->value)
                                <span
                                    class="text-sm font-[500] w-fit h-fit px-3 py-1 flex gap-2 box-border bg-red-800 rounded-full text-white cursor-pointer"
                                    x-on:click="showUserLevelModal = true; $dispatch('load-user-level', [{{ $user->id }}])">
                                    <i class="fa-solid fa-layer-group text-[12px] mt-1 -mb-1"></i>
                                    <span>برنزی</span>
                                </span>
                            @endif
                        </div>

                        <div class="absolute bg-white w-40 h-fit flex flex-col gap-1 overflow-hidden rounded-xl shadow-2xl top-14 left-4 border-1 border-gray-200 box-border p-1.5"
                            x-show="showOptionsMenu" x-on:click="showOptionsMenu = false"
                            x-on:click.outside="showOptionsMenu = false" x-transition x-cloak>
                            <button type="button"
                                class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                x-on:click="showUserLevelModal = true; $dispatch('load-user-level', [{{ $user->id }}])">
                                <i class="fa-solid fa-sliders text-sm text-gray-400 ml-2"></i>
                                تغییر سطح
                            </button>
                            <button type="button"
                                class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                x-on:click="showUserTransactionsModal = true; $dispatch('load-user-transactions', [{{ $user->id }}])">
                                <i class="fa-solid fa-money-bill-transfer text-sm text-gray-400 ml-2"></i>
                                <span>تراکنش‌ها</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-blade.manager.section>

        <livewire:components.manager.users.user-level-modal />
    </div>

    <livewire:components.manager.transactions.user-transactions-modal />
    <livewire:components.manager.transactions.transaction-details-modal />
    <x-manager.transactions.accept-transaction-warning />
    <x-manager.transactions.reject-transaction-warning />
</div>
