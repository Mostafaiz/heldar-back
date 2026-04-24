<?php

namespace App\Http\Dto\Request\Gallery;

class MoveImage
{
    public function __construct(
        public int $id,
        public ?int $newFolderId,
    ) {}

    public static function makeDto(int $id, ?int $newFolderId): self
    {
        return new self(
            id: $id,
            newFolderId: $newFolderId,
        );
    }
}
