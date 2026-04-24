<?php

namespace App\Http\Dto\Response\Customer;

use App\Http\Dto\Response\Customer\Image as ImageDto;
use App\Models\Slide as SlideModel;
use App\Utils\BaseWireableDto;

readonly class Slide extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public ?string $link,
        public bool $status,
        public ?ImageDto $desktopImage,
        public ?ImageDto $mobileImage,
    ) {}

    public static function from(SlideModel $slide)
    {
        return new self(
            id: $slide->id,
            link: $slide->link,
            status: $slide->status,
            desktopImage: $slide->desktopImageId,
            mobileImage: $slide->mobileImageId,
        );
    }
}
