@if (count($slides))
    <div class="w-full h-55 md:h-auto border-red-500 md:aspect-[683/200] rounded-2xl flex overflow-hidden" wire:ignore>
        <div class="swiper homePageSwipper">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    @if ($slide->link != '')
                        <a href="{{ $slide->link }}" class="size-full shrink-0 swiper-slide" target="_blank">
                            <picture class="size-full object-cover swiper-slide">
                                <source srcset="{{ asset('storage/' . $slide->mobileImage?->path) }}"
                                    media="(max-width: 767px)">
                                <source srcset="{{ asset('storage/' . $slide->desktopImage?->path) }}"
                                    media="(min-width: 768px)">
                                <img src="{{ asset('storage/' . $slide->desktopImage?->path) }}" alt="Slide image"
                                    class="size-full object-cover shrink-0">
                            </picture>
                        </a>
                    @else
                        <picture class="size-full object-cover shrink-0 swiper-slide">
                            <source srcset="{{ asset('storage/' . $slide->mobileImage?->path) }}"
                                media="(max-width: 767px)">
                            <source srcset="{{ asset('storage/' . $slide->desktopImage?->path) }}"
                                media="(min-width: 768px)">
                            <img src="{{ asset('storage/' . $slide->desktopImage?->path) }}" alt="Slide image"
                                class="size-full object-cover shrink-0">
                        </picture>
                    @endif
                @endforeach
            </div>

            <div
                class="pagination flex items-center justify-center gap-1 md:gap-1.5 z-2 w-fit! rounded-full px-1 py-1 md:px-1.5 md:py-1.5 bg-black/10 absolute bottom-3 right-1/2 translate-x-1/2">
            </div>
        </div>
    </div>
@else
    <div>
        <div>
        </div>
    </div>
@endif

@assets
    @vite(['resources/js/customer/slider.js'])
@endassets

@script
    <script>
        document.addEventListener("livewire:navigated", () => {
            new Swiper(".homePageSwipper", {
                loop: true,
                autoplay: {
                    running: true,
                    delay: 4000,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: ".pagination",
                    clickable: true,
                    bulletClass: "size-1.5 md:size-2.5 bg-gray-100/60 rounded-full transition-all cursor-pointer",
                    bulletActiveClass: "w-3 md:w-5 bg-white",
                },
                navigation: {
                    nextEl: ".next",
                    prevEl: ".prev",
                    disabledClass: "opacity-30 cursor-not-allowed text-gray-400 bg-gray-50!",
                },
                watchOverflow: true,
            });
        });
    </script>
@endscript
