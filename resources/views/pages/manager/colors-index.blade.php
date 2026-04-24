<div class="opened-panel colors-index-panel" x-data="{ showDeleteColorModal: false, selectedColorForDelete: null }">
    <div class="inner-container">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>

        <x-blade.manager.section class="added-colors-con">
            <x-blade.manager.section-title title="افزوده شده">
                مشخصه دیگه
            </x-blade.manager.section-title>

            <div
                class="w-full h-fit max-h-full grid grid-cols-[repeat(auto-fill,_minmax(250px,1fr))] gap-5 overflow-auto pb-4">
                @foreach ($colors as $key => $color)
                    <livewire:components.manager.colors.color-row :key="$color->id" :color="$color" />
                @endforeach
            </div>

        </x-blade.manager.section>

        <x-blade.manager.section class="add-color-con">
            <x-blade.manager.section-title title="افزودن">
                نام و کد رنگ را وارد کنید
            </x-blade.manager.section-title>

            <livewire:components.manager.colors.add-color />

        </x-blade.manager.section>

        <x-manager.colors.delete-message-modal />
    </div>
</div>


@assets
@vite(['resources/css/manager/colors-index.scss'])
@endassets