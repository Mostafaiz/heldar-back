<?php

namespace App\Livewire\Pages\Manager;

use App\Http\Dto\Request\Gallery\GetImage;
use App\Http\Dto\Request\Slider\UpdateSlide as UpdateSlideDto;
use App\Services\GalleryService;
use App\Services\SliderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.manager')]
class HomeSlider extends Component
{
    public string $pageTitle = 'اسلایدر صفحه اصلی';
    public string $routeName = 'manager.slider';
    public int $currentSlide = 1;

    public function getImage(int $id)
    {
        return app(GalleryService::class)->getImage(GetImage::makeDto(['id' => $id]));
    }

    public function updateSlide(array $data)
    {
        $dto = UpdateSlideDto::makeDto($data);
        $sliderService = app(SliderService::class);

        try {
            $sliderService->updateSlide($dto);
            $this->dispatch('success', message: 'اسلاید با موفقیت آپدیت شد.');
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در ذخیره تغییرات!');
        }
    }

    public function getSlides()
    {
        return app(SliderService::class)->getSlides();
    }

    public function render()
    {
        return view('pages.manager.home-slider');
    }
}
