<?php

namespace App\Livewire\Components\Manager\Gallery;

use App\Exceptions\Gallery\FolderException;
use App\Exceptions\Gallery\ImageException;
use App\Http\Dto\Request\Gallery\DeleteFolder as DeleteFolderDto;
use App\Http\Dto\Request\Gallery\DeleteImage;
use App\Http\Dto\Request\Gallery\GetFolder as GetFolderDto;
use App\Http\Dto\Request\Gallery\GetGalleryItems;
use App\Http\Dto\Request\Gallery\GetImage as GetImageDto;
use App\Http\Dto\Request\Gallery\MoveImage as MoveImageDto;
use App\Http\Dto\Response\Folder as FolderDto;
use App\Http\Dto\Response\FolderWithContentCount as FolderWithContentCountDto;
use App\Http\Dto\Response\Image as ImageDto;
use App\Models\Folder;
use App\Services\GalleryService;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    public string $pageTitle = 'گالری';
    public string $routeName = 'manager.gallery.index';
    #[Url]
    public int $page = 1;
    public ?FolderDto $currentFolder = null;
    public bool $selectable = false;
    public bool $multiselect = false;
    public array $folderHistory = [];
    public ?int $lastPage = null;
    public ?ImageDto $selectedImage = null;
    public ?FolderWithContentCountDto $selectedFolderForDelete;
    protected $paginator;
    public string $search = '';

    #[On('load-gallery-items')]
    private function loadAllItems()
    {
        $galleryService = app('GalleryService');
        $dto = GetGalleryItems::makeDto(['page' => $this->page], $this->currentFolder->id ?? null);
        $this->paginator = $galleryService->getAllItems($dto);
    }

    public function updatedSearch()
    {
        $this->page = 1;

        $this->searchImages();
    }

    public function searchImages()
    {
        if (trim($this->search) !== '') {
            $this->paginator = app(GalleryService::class)->searchGalleryItems(
                $this->search,
                $this->currentFolder->id ?? null,
                $this->page
            );
        } else {
            $this->loadAllItems();
        }
    }

    #[On('delete-image')]
    public function deleteImage(int $id): void
    {
        $galleryService = app('GalleryService');
        $dto = DeleteImage::makeDto(['id' => $id]);
        $galleryService->deleteImage($dto);
        $this->loadAllItems();
        $this->dispatch('success', message: 'تصویر با موفقیت حذف شد.');
    }

    public function setSelectedImageIdForMove(int $id): void
    {
        $galleryService = app('GalleryService');
        $dto = GetImageDto::makeDto(['id' => $id]);

        try {
            $this->selectedImage = $galleryService->getImage($dto);
        } catch (ImageException $exception) {
            $this->dispatch('exception', message: "خطا در دریافت تصویر!");
        }
    }

    public function removeSelectedImage(): void
    {
        $this->selectedImage = null;
    }

    public function moveImage(): void
    {
        $galleryService = app('GalleryService');
        $dto = MoveImageDto::makeDto($this->selectedImage->id, $this->currentFolder->id ?? null);

        try {
            $galleryService->moveImage($dto);
            $this->dispatch('gallery-go-to-page', $this->page);
            $this->dispatch('success', message: "تصویر با موفقیت انتقال یافت.");
        } catch (ImageException $exception) {
            $this->dispatch('exception', message: "خطا در انتقال تصویر!");
        }
    }

    public function setSelectedFolderForDelete(int $id): void
    {
        $galleryService = app(GalleryService::class);
        $dto = GetFolderDto::makeDto(['id' => $id]);

        try {
            $this->selectedFolderForDelete = $galleryService->getFolderWithContentCount($dto);
        } catch (FolderException $exception) {
            $this->dispatch('exception', message: 'دریافت پوشه با خطا مواجه شد!');
        }
    }

    public function removeSelectedFolderForDelete(): void
    {
        $this->reset('selectedFolderForDelete');
    }

    public function deleteFolder(): void
    {
        $galleryService = app(GalleryService::class);
        $dto = DeleteFolderDto::makeDto(['id' => $this->selectedFolderForDelete->folder->id]);

        try {
            $galleryService->deleteFolder($dto);
            $this->loadAllItems();
            $this->dispatch('success', message: 'پوشه با موفقیت حذف شد.');
            $this->removeSelectedFolderForDelete();
        } catch (FolderException $exception) {
            $this->dispatch('exception', message: "خطا در حذف پوشه!");
        }
    }

    public function goToFolder(?int $id = null): void
    {
        $this->search = "";
        $this->currentFolder = $id !== null ? FolderDto::from(Folder::findOrFail($id)) : null;
        $this->folderHistory[] = [
            'id' => $this->currentFolder->parent->id ?? null,
            'name' => $this->currentFolder->name ?? null,
            'page' => $this->page,
        ];
        $this->page = 1;
        $this->loadAllItems();
        $this->dispatch('set-current-folder', $this->currentFolder->id ?? null);
    }

    public function backToPreviousFolder(): void
    {
        $previous = array_pop($this->folderHistory);

        if ($previous) {
            $previousId = $previous['id'];
            $this->page = $previous['page'];
            $this->currentFolder = $previous['id'] !== null
                ? FolderDto::from(Folder::findOrFail($previousId))
                : null;
        } else {
            $this->page = 1;
            $this->currentFolder = null;
        }

        $this->loadAllItems();
        $this->dispatch('set-current-folder', $this->currentFolder->id ?? null);
    }

    public function nextPage(): void
    {
        $this->page++;
        $this->searchImages();
    }

    public function previousPage(): void
    {
        $this->page--;
        $this->searchImages();
    }

    #[On('gallery-go-to-page')]
    public function goToPage(?int $page = null): void
    {
        $this->page = $page ?? $this->page;
        $this->searchImages();
    }

    #[On('gallery-go-to-last-page')]
    public function goToLastPage(): void
    {
        $this->page = $this->lastPage;
    }

    public function mount(): void
    {
        $this->loadAllItems();
    }

    public function render()
    {
        if (!$this->paginator) {
            $this->loadAllItems();
        }

        $this->lastPage = $this->paginator->lastPage();

        return view('components.manager.gallery.index', [
            'paginator' => $this->paginator
        ]);
    }
}
