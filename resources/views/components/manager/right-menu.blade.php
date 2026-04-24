@use('\App\Enums\PermissionEnum')

<div class="right-menu bg-primary-light/20! pb-0!" id="right-menu">
    {{-- title --}}
    <h1 class="menu-title text-primary-dark!">
        <i class="fa-solid fa-bars" id="right-menu-btn"></i>
        پنل مدیریت
    </h1>

    {{-- links --}}
    <div class="menu-links-con">
        <livewire:components.manager.dashboard-navlink route="manager.dashboard.index" icon="fa-solid fa-home"
            text="داشبورد" />
        @if ($this->hasPermission(PermissionEnum::MANAGE_USERS))
            <livewire:components.manager.dashboard-navlink route="manager.users.managers.index"
                icon="fa-solid fa-user-tie" text="مدیران" />
        @endif
        @if ($this->hasPermission(PermissionEnum::MANAGE_USERS))
            <livewire:components.manager.dashboard-navlink route="manager.users.index" icon="fa-solid fa-users"
                text="کاربران" />
        @endif
        @if ($this->hasPermission(PermissionEnum::MANAGE_PRODUCTS))
            <div @class([
                'w-full flex flex-col h-13 gap-1 overflow-hidden shrink-0',
                'h-fit' => request()->routeIs([
                    'manager.attributes.index',
                    'manager.colors.index',
                    'manager.categories.index',
                    'manager.categories.create',
                    'manager.categories.edit',
                    'manager.products.create',
                    'manager.products.edit',
                    'manager.products.index',
                ]),
            ]) x-data="{ opened: false }">
                <a href="{{ route('manager.products.index') }}" wire:navigate @class([
                    'menu-link hover:bg-primary-light/30! font-normal!',
                    'clicked text-primary-dark! after:bg-primary-dark! font-[500]!' => request()->routeIs(
                        'manager.products.index'),
                ])>
                    <i class="svg-con fa-solid fa-box-archive"></i>
                    <span>محصولات</span>
                    <button
                        class="mr-auto ml-3 size-8 rounded-full hover:bg-primary/10 flex items-center justify-center cursor-pointer transition"
                        x-on:click="opened = true; console.log('hi')">
                        <i @class([
                            'fa-solid fa-angle-down transition',
                            'rotate-180' => request()->routeIs([
                                'manager.attributes.index',
                                'manager.colors.index',
                                'manager.categories.index',
                                'manager.categories.create',
                                'manager.categories.edit',
                                'manager.products.create',
                                'manager.products.edit',
                                'manager.products.index',
                            ]),
                        ])></i>
                    </button>
                </a>
                <div class="w-full relative flex bg-primary-light/20 flex-col gap-1 ">
                    @if ($this->hasPermission(PermissionEnum::MANAGE_PRODUCTS))
                        <livewire:components.manager.dashboard-navlink route="manager.products.create"
                            icon="fa-solid fa-plus-square" text="افزودن محصول" />
                    @endif
                    @if ($this->hasPermission(PermissionEnum::MANAGE_CATEGORIES))
                        <livewire:components.manager.dashboard-navlink route="manager.categories.index"
                            icon="fa-solid fa-box" text="دسته‌بندی‌ها" />
                    @endif
                    @if ($this->hasPermission(PermissionEnum::MANAGE_COLORS))
                        <livewire:components.manager.dashboard-navlink route="manager.colors.index"
                            icon="fa-solid fa-palette" text="رنگ‌ها" />
                    @endif
                    @if ($this->hasPermission(PermissionEnum::MANAGE_ATTRIBUTES))
                        <livewire:components.manager.dashboard-navlink route="manager.attributes.index"
                            icon="fa-solid fa-paperclip" text="ویژگی‌ها" />
                    @endif
                </div>
            </div>
        @endif
        <livewire:components.manager.dashboard-navlink route="manager.transactions.index"
            icon="fa-solid fa-money-bill-transfer" text="تراکنش‌ها" />
        <livewire:components.manager.dashboard-navlink route="manager.demands.index" icon="fa-brands fa-get-pocket"
            text="درخواست‌ها" />
        <livewire:components.manager.dashboard-navlink route="manager.debit-cards.index" icon="fa-solid fa-credit-card"
            text="کارت‌های اعتباری" />
        {{-- @if ($this->hasPermission(PermissionEnum::MANAGE_SIZES))
        <livewire:components.manager.dashboard-navlink route="manager.sizes.index" icon="fa-solid fa-ruler"
            text="سایزها" />
        @endif --}}
        {{-- @if ($this->hasPermission(PermissionEnum::MANAGE_GUARANTEES))
        <livewire:components.manager.dashboard-navlink route="manager.guarantees.index" icon="fa-solid fa-award"
            text="گارانتی‌ها" />
        @endif --}}
        {{-- @if ($this->hasPermission(PermissionEnum::MANAGE_INSURANCES))
        <livewire:components.manager.dashboard-navlink route="manager.insurances.index" icon="fa-solid fa-shield-halved"
            text="بیمه‌ها" />
        @endif --}}
        @if ($this->hasPermission(PermissionEnum::MANAGE_INSURANCES))
            <livewire:components.manager.dashboard-navlink route="manager.shipping.index" icon="fa-solid fa-truck"
                text="پست ارسال" />
        @endif
        @if ($this->hasPermission(PermissionEnum::MANAGE_GALLERY))
            <livewire:components.manager.dashboard-navlink route="manager.gallery.index" icon="fa-solid fa-images"
                text="گالری" />
        @endif
        <livewire:components.manager.dashboard-navlink route="manager.slider" icon="fa-solid fa-panorama"
            text="اسلایدر" />
        <livewire:components.manager.dashboard-navlink route="manager.settings" icon="fa-solid fa-cog" text="تنظیمات" />
        <button wire:click="logout" class="logout sticky! bottom-2 bg-red-200! mt-auto! mb-2">
            <div class=" svg-con"><i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <span>خروج</span>
        </button>
    </div>
</div>
