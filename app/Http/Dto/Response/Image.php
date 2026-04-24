<?php

namespace App\Http\Dto\Response;

use App\Utils\BaseWireableDto;
use \App\Models\File as FileModel;
use Carbon\Carbon;

readonly class Image extends BaseWireableDto
{

    public function __construct(
        public int $id,
        public string $name,
        public string $alt,
        public string $mimeType,
        public string $path,
        public string $fullname,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {
    }

    public static function from(FileModel $image)
    {
        return new self(
            id: $image->id,
            name: $image->name,
            alt: $image->alt,
            mimeType: $image->mime_type,
            path: $image->path,
            fullname: $image->fullname,
            createdAt: $image->created_at,
            updatedAt: $image->updated_at,
        );
    }
}