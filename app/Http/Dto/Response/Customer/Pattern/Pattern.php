<?php

namespace App\Http\Dto\Response\Customer\Pattern;

use App\Http\Dto\Response\Customer\Color\Color as ColorDto;
use App\Http\Dto\Response\Customer\Image as ImageDto;
use App\Models\Pattern as PatternModel;
use App\Utils\BaseWireableDto;

readonly class Pattern extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public ?string $name,
        public array $colors,
        public ?ImageDto $firstImage,
    ) {}

    public static function from(PatternModel $pattern): self
    {
        return new self(
            id: $pattern->id,
            name: $pattern->name,
            colors: $pattern->colors->map(fn($color) => ColorDto::from($color))->all(),
            firstImage: $pattern->files()->first()
                ? ImageDto::from($pattern->files()->first())
                : null,
        );
    }
}
