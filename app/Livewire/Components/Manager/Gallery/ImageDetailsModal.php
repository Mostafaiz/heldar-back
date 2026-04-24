<?php

namespace App\Livewire\Components\Manager\Gallery;

use App\Http\Dto\Request\Gallery\GetImage as GetImageDto;
use App\Http\Dto\Response\Image as ImageDto;
use App\Models\File;
use Livewire\Attributes\On;
use Livewire\Component;

class ImageDetailsModal extends Component
{
    public bool $visible = false;
    public ImageDto $image;

    public function getImage(int $id): void
    {
        $galleryService = app('GalleryService');
        $this->image = $galleryService->getImage(GetImageDto::makeDto(['id' => $id]));
    }

    #[On('show-image-details')]
    public function showModal(int $id): void
    {
        $this->getImage($id);
        $this->visible = true;
    }

    #[On('hide-image-details')]
    public function hideModal(): void
    {
        $this->visible = false;
        $this->reset();
    }

    public function render()
    {
        return view('components.manager.gallery.image-details-modal');
    }
}
