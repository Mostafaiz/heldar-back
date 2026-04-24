<?php

namespace App\Http\Dto\Response;

use App\Utils\BaseWireableDto;
use App\Http\Dto\Response\Folder as FolderDto;

readonly class FolderWithContentCount extends BaseWireableDto
{
    public function __construct(
        public FolderDto $folder,
        public int $foldersCount,
        public int $imagesCount,
    ) {
    }

    public static function from(FolderDto $folder, int $foldersCount, int $imagesCount): FolderWithContentCount
    {
        return new self(
            folder: $folder,
            foldersCount: $foldersCount,
            imagesCount: $imagesCount,
        );
    }
}