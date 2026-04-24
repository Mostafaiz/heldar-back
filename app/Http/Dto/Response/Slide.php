<?php

namespace App\Http\Dto\Response;

use App\Models\File;
use App\Models\Slide as SlideModel;
use App\Utils\BaseWireableDto;
use Illuminate\Database\Eloquent\Collection;

readonly class Slide extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public ?string $link,
        public bool $status,
        public string $level,
        public ?File $desktopImage,
        public ?File $mobileImage,
    ) {}

    public static function from(SlideModel $slide)
    {
        return new self(
            id: $slide->id,
            link: $slide->link,
            status: $slide->status,
            level: $slide->level,
            desktopImage: !$slide->desktopImage->isEmpty() ? $slide->desktopImage->first() : null,
            mobileImage: !$slide->mobileImage->isEmpty() ? $slide->mobileImage->first() : null,
        );
    }
}
