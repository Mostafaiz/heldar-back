<div class="inner-gallery-container inner-container" x-data="{ closeGallery() { showGallery = false }, showNewButtonsPanel: false, showConfirmMessage: true, moving: false, showDeleteFolderMessage: false }" x-on:click.outside="closeGallery()"
    x-on:keydown.escape.window="closeGallery()">
    <x-blade.manager.section class="title-con">
        @if ($selectable)
            <div class="title-section">
                <h1 class="large-title">انتخاب تصویر</h1>
            </div>
        @else
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        @endif
        <div class="buttons-container">
            @if ($selectable && !$multiselect)
                <x-blade.manager.outlined-button type="button" value="انصراف" x-on:click="closeGallery()" />
                <x-blade.manager.filled-button type="button" value="تایید"
                    x-on:click="$dispatch('set-selected-image', [selectedImage]); closeGallery()"
                    x-bind:disabled="selectedImage == null" />
            @elseif ($selectable && $multiselect)
                <x-blade.manager.outlined-button type="button" value="انصراف" x-on:click="closeGallery()" />
                <x-blade.manager.filled-button type="button" value="تایید"
                    x-on:click="$dispatch('set-selected-images', [selectedImages]); closeGallery()"
                    x-bind:disabled="!selectedImages.length" />
            @endif
            <button type="button" class="button filled add-new-button bg-primary!"
                x-on:click="showNewButtonsPanel = true"><i class="fa-solid fa-plus"></i>جدید</button>
        </div>
        <div class="new-buttons-container" x-cloak x-transition.opacity.scale x-show="showNewButtonsPanel"
            x-on:click.outside="showNewButtonsPanel = false">
            <button type="button" x-on:click="$dispatch('show-upload-modal'); showNewButtonsPanel = false"><i
                    class="fa-solid fa-circle-plus"></i>
                آپلود تصویر
            </button>
            <hr>
            <button type="button" x-on:click="$dispatch('show-create-folder-modal'); showNewButtonsPanel = false"><i
                    class="fa-solid fa-folder-plus"></i>
                ساخت پوشه
            </button>
        </div>
    </x-blade.manager.section>

    <x-blade.manager.section class="gallery-images-section">
        <div class="toolbar">
            <button type="button" class="button outlined size-10!" value="بازگشت" wire:click="backToPreviousFolder"
                x-bind:disabled="$wire.currentFolder === null" wire:loading.attr="disabled">
                <i class="fa-solid fa-angle-right" wire:loading.remove></i>
                <i class="fa-solid fa-spinner animate-spin" wire:loading></i>
            </button>
            <div class="bread-crumb-container truncate">
                گالری
                @foreach ($folderHistory as $folder)
                    <i class="fa-solid fa-angle-left"></i>
                    {{ $folder['name'] }}
                @endforeach
            </div>
            <div class="button outlined cursor-default! gap-1! flex justify-right items-center transition px-0!"
                x-data="{ showSearch: false }" x-bind:class="{ 'hover:bg-white!': showSearch }" value="جستجو"
                x-on:click.outside="if ($refs.searchInput.value == '') showSearch = false">
                <button type="submit" class="h-full aspect-square flex items-center justify-center cursor-pointer"
                    x-on:click="showSearch = true">
                    <i class="fa-solid fa-spinner animate-spin size-fit" wire:loading wire:target="search"></i>
                    <i class="fa-solid fa-search size-fit" wire:loading.remove wire:target="search"></i>
                </button>
                <input type="text" class="font-shabnam h-full pl-2.5 outline-none" x-show="showSearch"
                    wire:model.live.debounce.500ms="search" x-ref="searchInput" x-cloak placeholder="جستجو">
            </div>
        </div>
        @if ($paginator->isEmpty())
            <span class="no-images">تصویر یا پوشه‌ای برای نمایش وجود ندارد.</span>
        @else
            <div class="inner-gallery-images-container" x-data="{ selected: null }">
                @if ($selectable && !$multiselect)
                    @foreach ($paginator->items() as $item)
                        @if ($item->folderable_type == 'App\\Models\\File')
                            <input type="checkbox" class="image-checkbox" id="image{{ $item->folderable->id }}"
                                value="{{ $item->folderable->id }}"
                                :checked="selectedImage == '{{ $item->folderable->id }}'"
                                x-on:change="selectedImage = $event.target.checked ? '{{ $item->folderable->id }}' : null">
                            <div class="single-image-container"
                                x-on:contextmenu="$event.preventDefault(); showMenu = true" x-data="{ showMenu: false }"
                                x-bind:class="selectedImage == '{{ $item->folderable->id }}' ? 'selected' : ''">
                                <div class="inner-single-image-container">
                                    <label for="image{{ $item->folderable->id }}">
                                        <img src="{{ asset('storage/' . $item->folderable->path) }}"
                                            alt="{{ $item->folderable->alt }}" class="image-view">
                                    </label>
                                    <div class="image-name" x-middle-ellipsis>{{ $item->folderable->fullname }}</div>
                                    <i class="fa-solid fa-ellipsis options-button" x-on:click="showMenu = true"
                                        x-bind:class="selectedImage == '{{ $item->folderable->id }}' ? 'hide' : ''"></i>

                                    <div class="options-menu" x-show="showMenu" x-on:click.outside="showMenu = false"
                                        x-on:click="showMenu = false" x-transition.opacity.scale x-cloak>
                                        <button type="button"
                                            wire:click="setSelectedImageIdForMove({{ $item->folderable->id }})"
                                            x-on:click="moving = true">
                                            <i class="fa-solid fa-pen icon"></i>
                                            انتقال
                                        </button>
                                        <hr>
                                        <button type="button"
                                            x-on:click="$dispatch('show-confirm-delete-message', [{{ $item->folderable->id }}, 'image', 'تصویر'])">
                                            <i class="fa-solid fa-trash icon"></i>
                                            حذف
                                        </button>
                                    </div>
                                </div>
                                <i class="fa-solid fa-check checked-icon"></i>
                            </div>
                        @else
                            <div class="single-folder-container"
                                x-on:contextmenu="$event.preventDefault(); showMenu = true" x-data="{ showMenu: false }">
                                <i class="fa-solid fa-ellipsis options-button" x-on:click="showMenu = true"></i>
                                <button type="button" wire:click="goToFolder({{ $item->folderable->id }})"
                                    class="not-disabled:cursor-pointer disabled:sepia-50" wire:loading.attr="disabled">
                                    <img src="{{ asset('images/gallery/folder-icon.png') }}">
                                </button>
                                <span class="folder-name">{{ $item->folderable->name }}</span>

                                <div class="options-menu" x-show="showMenu" x-on:click.outside="showMenu = false"
                                    x-on:click="showMenu = false" x-transition.opacity.scale x-cloak>
                                    <button type="button"
                                        x-on:click="$dispatch('show-update-folder-modal', [{{ $item->folderable->id }}])">
                                        <i class="fa-solid fa-pen-to-square icon"></i>
                                        ویرایش نام
                                    </button>
                                    <hr>
                                    <button type="button" x-on:click="deleteFolder(1)">
                                        <i class="fa-solid fa-pen-to-square icon"></i>
                                        حذف
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @elseif ($selectable && $multiselect)
                    @foreach ($paginator->items() as $item)
                        @if ($item->folderable_type == 'App\\Models\\File')
                            <input type="checkbox" class="image-checkbox" id="image{{ $item->folderable->id }}"
                                value="{{ $item->folderable->id }}"
                                :checked="selectedImages.includes({{ $item->folderable->id }})"
                                x-on:change="$event.target.checked ? selectedImages.push({{ $item->folderable->id }}) : selectedImages.splice(selectedImages.findIndex(index => index == {{ $item->folderable->id }}), 1)">
                            <div class="single-image-container"
                                x-on:contextmenu="$event.preventDefault(); showMenu = true" x-data="{ showMenu: false }"
                                x-bind:class="selectedImages.includes({{ $item->folderable->id }}) ? 'selected' : ''">
                                <div class="inner-single-image-container">
                                    <label for="image{{ $item->folderable->id }}">
                                        <img src="{{ asset('storage/' . $item->folderable->path) }}"
                                            alt="{{ $item->folderable->alt }}" class="image-view">
                                    </label>
                                    <div class="image-name" x-middle-ellipsis>{{ $item->folderable->fullname }}</div>
                                    <i class="fa-solid fa-ellipsis options-button" x-on:click="showMenu = true"
                                        x-bind:class="selectedImages.includes({{ $item->folderable->id }}) ? 'hide' : ''"></i>

                                    <div class="options-menu" x-show="showMenu" x-on:click.outside="showMenu = false"
                                        x-on:click="showMenu = false" x-transition.opacity.scale x-cloak>
                                        <button type="button"
                                            wire:click="setSelectedImageIdForMove({{ $item->folderable->id }})"
                                            x-on:click="moving = true">
                                            <i class="fa-solid fa-pen icon"></i>
                                            انتقال
                                        </button>
                                        <hr>
                                        <button type="button"
                                            x-on:click="$dispatch('show-confirm-delete-message', [{{ $item->folderable->id }}, 'image', 'تصویر'])">
                                            <i class="fa-solid fa-trash icon"></i>
                                            حذف
                                        </button>
                                    </div>
                                </div>
                                <i class="fa-solid fa-check checked-icon"></i>
                            </div>
                        @else
                            <div class="single-folder-container"
                                x-on:contextmenu="$event.preventDefault(); showMenu = true" x-data="{ showMenu: false }">
                                <i class="fa-solid fa-ellipsis options-button" x-on:click="showMenu = true"></i>
                                <button type="button" wire:loading.attr="disabled"
                                    class="not-disabled:cursor-pointer disabled:sepia-50"
                                    wire:click="goToFolder({{ $item->folderable->id }})">
                                    <img src="{{ asset('images/gallery/folder-icon.png') }}">
                                </button>
                                <span class="folder-name">{{ $item->folderable->name }}</span>

                                <div class="options-menu" x-show="showMenu" x-on:click.outside="showMenu = false"
                                    x-on:click="showMenu = false" x-transition.opacity.scale x-cloak>
                                    <button type="button"
                                        x-on:click="$dispatch('show-update-folder-modal', [{{ $item->folderable->id }}])">
                                        <i class="fa-solid fa-pen-to-square icon"></i>
                                        ویرایش نام
                                    </button>
                                    <hr>
                                    <button type="button" x-on:click="deleteFolder(1)">
                                        <i class="fa-solid fa-pen-to-square icon"></i>
                                        حذف
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    @foreach ($paginator->items() as $item)
                        @if ($item->folderable_type == 'App\\Models\\File')
                            <div class="single-image-container"
                                x-on:contextmenu="$event.preventDefault(); showMenu = true" x-data="{ showMenu: false }">
                                <img src="{{ asset('storage/' . $item->folderable->path) }}"
                                    alt="{{ $item->folderable->alt }}"
                                    wire:click="$dispatch('show-image-details', [{{ $item->folderable->id }}])"
                                    class="image-view">
                                <span class="image-name" x-middle-ellipsis>{{ $item->folderable->fullname }}</span>
                                <i class="fa-solid fa-ellipsis options-button" x-on:click="showMenu = true"></i>

                                <div class="options-menu" x-show="showMenu" x-on:click.outside="showMenu = false"
                                    x-on:click="showMenu = false" x-transition.opacity.scale x-cloak>
                                    <button type="button"
                                        wire:click="setSelectedImageIdForMove({{ $item->folderable->id }})"
                                        x-on:click="moving = true">
                                        <i class="fa-solid fa-up-down-left-right icon"></i>
                                        انتقال
                                    </button>
                                    <hr>
                                    <button type="button"
                                        x-on:click="$dispatch('show-confirm-delete-message', [{{ $item->folderable->id }}, 'image', 'تصویر'])">
                                        <i class="fa-solid fa-trash icon"></i>
                                        حذف
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="single-folder-container"
                                x-on:contextmenu="$event.preventDefault(); showMenu = true" x-data="{ showMenu: false }">
                                <i class="fa-solid fa-ellipsis options-button" x-on:click="showMenu = true"></i>
                                <button type="button" wire:loading.attr="disabled"
                                    class="not-disabled:cursor-pointer disabled:sepia-50"
                                    wire:click="goToFolder({{ $item->folderable->id }})">
                                    <img src="{{ asset('images/gallery/folder-icon.png') }}">
                                </button>
                                <span class="folder-name">{{ $item->folderable->name }}</span>

                                <div class="options-menu" x-show="showMenu" x-on:click.outside="showMenu = false"
                                    x-on:click="showMenu = false" x-transition.opacity.scale x-cloak>
                                    <button type="button"
                                        x-on:click="$dispatch('show-update-folder-modal', [{{ $item->folderable->id }}])">
                                        <i class="fa-solid fa-pen-to-square icon"></i>
                                        ویرایش نام
                                    </button>
                                    <hr>
                                    <button type="button"
                                        wire:click="setSelectedFolderForDelete({{ $item->folderable->id }})"
                                        x-on:click="showDeleteFolderMessage = true">
                                        <i class="fa-solid fa-trash icon"></i>
                                        حذف
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="pagination-buttons-container">
                <button type="button" class="previews-button" wire:click="previousPage"
                    {{ $paginator->currentPage() < 2 ? 'disabled' : '' }}><i
                        class="fa-solid fa-angle-right"></i>قبلی</button>
                @if ($paginator->currentPage() > 1)
                    <button type="button" class="page-number" wire:click="goToPage(1)">1</button>
                @endif
                @if ($paginator->currentPage() > 3)
                    ...
                @endif
                @if ($paginator->currentPage() > 2)
                    <button type="button" class="page-number"
                        wire:click="goToPage({{ $paginator->currentPage() - 1 }})">{{ $paginator->currentPage() - 1 }}</button>
                @endif
                <button type="button" class="page-number active">{{ $paginator->currentPage() }}</button>
                @if ($paginator->currentPage() < $paginator->lastPage() - 1)
                    <button type="button" class="page-number"
                        wire:click="goToPage({{ $paginator->currentPage() + 1 }})">{{ $paginator->currentPage() + 1 }}</button>
                @endif
                @if ($paginator->currentPage() <= $paginator->lastPage() - 3)
                    ...
                @endif
                @if ($paginator->currentPage() < $paginator->lastPage())
                    <button type="button" class="page-number"
                        wire:click="goToPage({{ $paginator->lastPage() }})">{{ $paginator->lastPage() }}</button>
                @endif
                <button type="button" class="next-button" wire:click="nextPage"
                    {{ $paginator->currentPage() >= $paginator->lastPage() ? 'disabled' : '' }}>بعدی<i
                        class="fa-solid fa-angle-left"></i></button>
            </div>
        @endif
        <div class="move-buttons-container" x-show="moving" x-transition.opacity.scale x-cloak>
            <h3>انتقال تصویر
                <span class="icon" wire:loading wire:target="setSelectedImageIdForMove">
                    <i class="fa-solid fa-spinner"></i>
                </span>
                <span x-middle-ellipsis style="font-weight: 500;" wire:loading.class="hide"
                    wire:target="setSelectedImageIdForMove">
                    {{ $selectedImage->fullname ?? '' }}
                </span>
            </h3>
            <div>
                <x-blade.manager.text-button value="انصراف" wire:click="removeSelectedImage"
                    x-on:click="moving = false" />
                <x-blade.manager.filled-button value="جایگذاری" wire:click="moveImage" target="moveImage"
                    x-on:click="moving = false" />
            </div>
        </div>
    </x-blade.manager.section>

    <div class="back-cover expand" x-show="showDeleteFolderMessage" x-transition.opacity x-cloak>
        <div class="delete-folder-message" x-data="{
            hideModal() {
                showDeleteFolderMessage = false;
                $wire.removeSelectedFolderForDelete
            }
        }" x-on:click.outside="hideModal()"
            x-on:keydown.escape="hideModal">
            <h2 class="title text-2xl!">
                <i class="fa-solid fa-trash-can text-[22px]!"></i>
                حذف پوشه
            </h2>
            <p class="text">
                آیا از حذف پوشه اطمینان دارید؟
            </p>
            <div class="warning-content">
                <h2 class="warning-title">
                    با انجام این کار، محتویات پوشه نیز حذف خواهند شد.
                </h2>
                <i class="fa-solid fa-spinner loading-icon" wire:loading wire:target="setSelectedFolderForDelete"></i>
                <p wire:loading.class="hide" wire:target="setSelectedFolderForDelete">
                    این پوشه دارای {{ $selectedFolderForDelete->foldersCount ?? 0 }} پوشه و
                    {{ $selectedFolderForDelete->imagesCount ?? 0 }} تصویر است.
                </p>
            </div>
            <div class="buttons-container">
                <x-blade.manager.text-button value="خیر" x-on:click="hideModal" />
                <x-blade.manager.text-button value="بله" target="deleteFolder"
                    x-on:click="showDeleteFolderMessage = false" wire:click="deleteFolder" />
            </div>
        </div>
    </div>

    <livewire:components.manager.gallery.upload-modal />
    <livewire:components.manager.gallery.image-details-modal />
    <livewire:components.manager.gallery.create-folder-modal />
    <livewire:components.manager.gallery.update-folder-modal />
    <livewire:components.manager.confirm-delete-message />
</div>

@assets
    @vite(['resources/css/manager/gallery-index.scss'])
@endassets
