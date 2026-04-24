<?php

namespace App\Http\Dto\Request\Gallery;

class GetGalleryItems
{
    public function __construct(
        public int $page,
        public ?int $folderId = null,
    ) {
    }
    static function makeDto(array $array, ?int $folderId = null): getGalleryItems
    {
        return new self(
            page: $array['page'],
            folderId: $folderId ?? null,
        );
    }
}

