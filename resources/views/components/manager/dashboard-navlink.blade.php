<a href="{{ route($route) }}" wire:navigate @class([
    'menu-link hover:bg-primary-light/30! font-normal!',
    'clicked text-primary-dark! after:bg-primary-dark! font-[500]!' => request()->routeIs(
        $route),
])>
    <i class="svg-con {{ $icon }}"></i>
    <span>{{ $text }}</span>
</a>
