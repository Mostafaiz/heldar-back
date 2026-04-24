<?php

namespace App\Services;

use App\Enums\Gallery\ImageTypeEnum;
use App\Enums\ProductLevelsEnum;
use App\Enums\Slide\SlideExceptionsEnum;
use App\Exceptions\Gallery\ImageException;
use App\Exceptions\Slide\SlideException;
use App\Http\Dto\Request\Slider\UpdateSlide as updateSlideDto;
use App\Http\Dto\Response\Customer\Image as ImageDto;
use App\Http\Dto\Response\Slide as SlideManagerDto;
use App\Http\Dto\Response\Customer\Slide as SlideCustomerDto;
use App\Models\File;
use App\Models\Slide;

class SliderService
{
    public function updateSlide(UpdateSlideDto $dto)
    {
        $slide = Slide::find($dto->id);
        if (!$slide) {
            SlideException::NotFound();
        }

        $slide->update([
            'link' => $dto->link,
            'status' => $dto->status,
            'level' => $dto->level,
        ]);

        if ($dto->desktopImageId !== null) {
            $desktopImage = File::find($dto->desktopImageId);
            if ($desktopImage === null) {
                ImageException::desktopImageNotFound();
            }


            $slide->images()->wherePivot('type', ImageTypeEnum::DESKTOP_SLIDE->value)->detach();
            $slide->images()->attach($dto->desktopImageId, ['type' => ImageTypeEnum::DESKTOP_SLIDE->value]);

            if ($dto->mobileImageId !== null) {
                $mobileImage = File::find($dto->mobileImageId);
                if ($mobileImage === null) {
                    ImageException::mobileImageNotFound();
                }

                $slide->images()->wherePivot('type', ImageTypeEnum::MOBILE_SLIDE->value)->detach();
                $slide->images()->attach($dto->mobileImageId, ['type' => ImageTypeEnum::MOBILE_SLIDE->value]);
            } else {
                $slide->images()->wherePivot('type', ImageTypeEnum::MOBILE_SLIDE->value)->detach();
                $slide->images()->attach($dto->desktopImageId, ['type' => ImageTypeEnum::MOBILE_SLIDE->value]);
            }
        } else {
            $slide->images()->wherePivot('type', ImageTypeEnum::DESKTOP_SLIDE->value)->detach();
            $slide->images()->wherePivot('type', ImageTypeEnum::MOBILE_SLIDE->value)->detach();
        }

        return Slide::with('images')
            ->get()
            ->map(fn($slide) => SlideManagerDto::from($slide));
    }


    public function getSlides()
    {
        $slides = Slide::with('desktopImage', 'mobileImage')->get();
        return $slides->map(fn($slide) => SlideManagerDto::from($slide));
    }


    public function getActiveSlides()
    {
        $userLevel = auth()->user()?->level() ?? ProductLevelsEnum::BORONZE->value;

        $levels = match ($userLevel) {
            ProductLevelsEnum::BORONZE->value => [ProductLevelsEnum::BORONZE],
            ProductLevelsEnum::SILVER->value => [ProductLevelsEnum::BORONZE, ProductLevelsEnum::SILVER],
            ProductLevelsEnum::GOLD->value => [ProductLevelsEnum::BORONZE, ProductLevelsEnum::SILVER, ProductLevelsEnum::GOLD],
            default => [ProductLevelsEnum::BORONZE],
        };

        $slides = Slide::whereIn('level', $levels)
            ->where('status', '=', true)
            ->with('desktopImage', 'mobileImage')
            ->get();

        return $slides->map(function ($slide) {

            $desktopImage = $slide->desktopImage->first();
            $mobileImage = $slide->mobileImage->first();

            return new SlideCustomerDto(
                id: $slide->id,
                link: $slide->link,
                status: $slide->status,
                desktopImage: ImageDto::from($desktopImage),
                mobileImage: ImageDto::from($mobileImage),
            );
        })->toArray();
    }
}
