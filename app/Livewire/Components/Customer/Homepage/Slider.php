<?php

namespace App\Livewire\Components\Customer\Homepage;

use App\Services\SliderService;
use Livewire\Component;

class Slider extends Component
{
    public array $slides;

    public function mount()
    {
        $this->getSlides();
    }

    public function getSlides()
    {
        $this->slides = app(SliderService::class)->getActiveSlides();
    }

    public function render()
    {
        return view('components.customer.homepage.slider');
    }
}
