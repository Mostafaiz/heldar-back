@unless ($breadcrumbs->isEmpty())
    <nav class="container mx-auto">
        <ol class="font-[shabnam] flex flex-wrap items-center font-normal text-black">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    <li>
                        <a href="{{ $breadcrumb->url }}" class="text-primary-dark hover:underline px-0" wire:navigate>
                            {{ $breadcrumb->title }}
                        </a>
                    </li>
                @else
                    <li>
                        {{ $breadcrumb->title }}
                    </li>
                @endif

                @unless ($loop->last)
                    <div class="flex items-center justify-center size-3 px-3">
                        <i
                            class="fa-solid fa-angle-left size-fit text-center flex items-center justify-center text-gray-700 text-xs"></i>
                    </div>
                @endif
                @endforeach
            </ol>
        </nav>
    @endunless
