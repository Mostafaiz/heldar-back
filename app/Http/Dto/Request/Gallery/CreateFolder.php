<?php

namespace App\Http\Dto\Request\Gallery;

class CreateFolder
{
    public function __construct(
        public string $name,
        public ?int $parentId,
    ) {
    }
    public static function makeDto(array $array)
    {
        return new self(
            name: $array['name'],
            parentId: $array['parentId'],
        );
    }
}