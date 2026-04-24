<?php

namespace App\Http\Dto\Request\Product;

class UpdatePattern
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?array $colorIds,
        public ?array $guaranteeIds,
        public ?array $insuranceIds,
        public ?array $imageIds,
        public ?array $sizes,
        public bool $local,
    ) {}

    public static function makeDto(array|self $data): self
    {
        if ($data instanceof self) {
            return $data;
        }

        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            colorIds: array_column($data['colors'] ?? [], 'id'),
            guaranteeIds: array_column($data['guarantees'] ?? [], 'id'),
            insuranceIds: array_column($data['insurances'] ?? [], 'id'),
            imageIds: array_column($data['images'] ?? [], 'id'),
            sizes: array_map(
                fn($size) => CreatePatternSize::makeDto($size),
                $data['sizes'] ?? []
            ),
            local: $data['local'],
        );
    }
}
