<?php

namespace App\Http\Dto\Gallery;
use Illuminate\Http\UploadedFile;

class UploadImageDto
{
    public function __construct(
        public UploadedFile $image,
        public string $alt,
        public ?int $folderId = null
    ) {
    }
    public static function makeDto(array $array)
    {
        return new self(
            image: $array['image'],
            alt: $array['alt'],
            folderId: $array['folderId'],
        );
    }
}