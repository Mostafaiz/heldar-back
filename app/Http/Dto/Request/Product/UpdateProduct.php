<?php

namespace App\Http\Dto\Request\Product;

use App\Http\Dto\Request\Product\Attribute as AttributeDto;

class UpdateProduct
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $englishName,
        public string $slug,
        public ?string $brand,
        public ?string $description,
        public string $price,
        public string $level,
        public bool $status = true,
        public ?array $categoryIds,
        /** @var UpdatePattern[] */
        public array $patterns,
        public ?AttributeDto $attributes = null,
    ) {}

    public static function makeDto(array $data): self
    {
        $patterns = array_map(
            fn($pattern) => $pattern instanceof UpdatePattern ? $pattern : UpdatePattern::makeDto($pattern),
            $data['patterns'] ?? []
        );

        return new self(
            id: $data['id'],
            name: $data['name'],
            englishName: $data['englishName'] ?? null,
            slug: $data['slug'],
            brand: $data['brand'] ?? null,
            description: $data['description'] ?? null,
            price: $data['price'],
            level: $data['level'],
            status: $data['status'] ?? true,
            categoryIds: array_column($data['categories'], 'id') ?? null,
            patterns: $patterns,
            attributes: isset($data['attributes']) && !($data['attributes'] instanceof Attribute)
                ? Attribute::makeDto($data['attributes'])
                : $data['attributes'] ?? null,
        );
    }
}
