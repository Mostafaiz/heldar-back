<?php

namespace App\Http\Dto\Request\Slider;

class UpdateSlide
{
    public function __construct(
        public int $id,
        public ?string $link,
        public bool $status,
        public string $level,
        public ?int $desktopImageId,
        public ?int $mobileImageId,
    ) {}

    public static function makeDto(array $data)
    {
        return new self(
            id: $data['id'],
            link: $data['link'] ?? null,
            status: $data['status'],
            level: $data['level'],
            desktopImageId: $data['desktopImage']['id'] ?? null,
            mobileImageId: $data['mobileImage']['id'] ?? null,
        );
    }
}
