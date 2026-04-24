<div class="opened-panel attributes-index-panel">
    <div class="inner-container">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>
        <x-blade.manager.section class="added-attributes-con">
            <x-blade.manager.section-title title="افزوده شده" />
            <x-blade.manager.flex-column>
                @foreach ($attributeGroups as $attributeGroup)
                    <livewire:components.manager.attributes.attribute-group-row :$attributeGroup
                        :key="$attributeGroup->id" />
                @endforeach
            </x-blade.manager.flex-column>
        </x-blade.manager.section>
        <x-blade.manager.section class="add-attribute-con">
            <x-blade.manager.section-title title="افزودن" />
            <livewire:components.manager.attributes.add-attribute />

        </x-blade.manager.section>
    </div>
</div>

@assets
@vite(['resources/css/manager/attributes-index.scss'])
@endassets