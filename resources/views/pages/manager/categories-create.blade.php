<form class="opened-panel create-category-panel" wire:submit.prevent="store" x-on:click="selectedImage = null"
    x-data="{ showGallery: false, selectedImage: null }">
    <div class="inner-container">
        <div class="items title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
            <div class="buttons-section">
                <x-blade.manager.filled-button type="submit" value="انتشار دسته‌بندی" target="store" />
            </div>
        </div>
        <div class="items general-information-con">
            <h1 class="title">مشخصات اصلی</h1>
            <i class="guid-icon"></i>
            <div class="guid-pannel">
                مشخصاته دیگه
            </div>
            <div class="input-con">
                <x-blade.manager.input-text title="نام دسته‌بندی" name="form.name" containerclass="column" />
                <x-blade.manager.dropdown-menu title="دسته‌بندی والد" class="select-category-button"
                    wire:model="form.parentId" :options="$categories" />
            </div>
            <x-blade.manager.textarea title="توضیحات دسته‌بندی" name="form.descriptionCategory" />
            <x-blade.manager.textarea title="توضیحات صفحه دسته‌بندی" name="form.descriptionPage" />
        </div>
        <div class="items image-con">
            <h1 class="title">تصویر</h1>
            <i class="guid-icon"></i>
            <div class="guid-pannel">
                عکسه دیگه
            </div>
            <div class="add-image-con">
                @if($image)
                    <div class="selected-image-container">
                        <img src="{{ asset('storage/' . $image->path) }}" class="selected-image">
                        <i class="fa-solid fa-circle-xmark" wire:click="removeImage" x-on:click="selectedImage = null"></i>
                    </div>
                @else
                    <div class="add-image-label" x-on:click="showGallery = true">
                        <i class="fa-solid fa-circle-plus"></i>
                        <span class="info-title">انتخاب تصویر</span>
                    </div>
                @endif
            </div>
        </div>


        <div class="back-cover" x-show="showGallery" x-on:click.target="showGallery = false" x-cloak
            x-transition.opacity>
            <livewire:components.manager.gallery.index :selectable="true" />
        </div>
    </div>
</form>

@assets
@vite(['resources/css/manager/categories-create.scss'])
@endassets