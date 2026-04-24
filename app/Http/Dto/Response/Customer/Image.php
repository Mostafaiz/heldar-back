<?php

namespace App\Http\Dto\Response\Customer;

use App\Models\File as FileModel;
use App\Utils\BaseWireableDto;

readonly class Image extends BaseWireableDto
{
    public function __construct(
        public string $name,
        public string $alt,
        public string $mimeType,
        public string $path,
        public string $fullname,
    ) {}

    public static function from(?FileModel $image)
    {
        if (isset($image))
            return new self(
                name: $image->name,
                alt: $image->alt,
                mimeType: $image->mime_type,
                path: $image->path,
                fullname: $image->fullname,
            );
        else return null;
    }
}
