<?php

namespace App\Http\Dto\Response;

use App\Utils\BaseWireableDto;
use \App\Models\Folder as FolderModel;
use Carbon\Carbon;

readonly class Folder extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public Carbon $createdAt,
        public Carbon $updatedAt,
        public ?Folder $parent = null,
    ) {
    }

    public static function from(FolderModel $folder)
    {
        return new self(
            id: $folder->id,
            name: $folder->name,
            createdAt: $folder->created_at,
            updatedAt: $folder->updated_at,
            parent: $folder->parent ? Folder::from($folder->parent) : null,
        );
    }
}