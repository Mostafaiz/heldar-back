<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#d67782">
    <link rel="icon" href="{{ asset('images/dhbag-logo-before.ico') }}" type="image/x-icon">
    <title>{{ $title ?? 'کیف داریوش' }}</title>
    @livewireStyles
    @vite(['resources/js/main.ts', 'resources/css/app.css'])
    @stack('styles')
</head>

<body class="flex flex-col items-center min-h-screen gap-5" x-data="{ showCategoryMenu: false }">
    <x-blade.customer.notification />
    <livewire:components.customer.categories.category-right-menu />
    @unless (Route::is(['customer.checkout', 'customer.payment.success', 'customer.payment.failed', 'customer.invoice']))
        <livewire:components.customer.desktop-header />
    @endunless
    @unless (Route::is(['customer.checkout', 'customer.payment.success', 'customer.payment.failed']))
        <livewire:components.customer.mobile-header />
    @endunless
    <main class="w-full max-w-[1676px] h-full flex items-center flex-col">
        {{ $slot }}
    </main>
    @unless (Route::is(['customer.checkout', 'customer.payment.success', 'customer.payment.failed']))
        <livewire:components.customer.bottom-navigation />
    @endunless
    @if (Route::is(['customer.index', 'customer.product', 'customer.products']))
        <livewire:components.customer.footer />
    @endif
    @livewireScriptConfig
</body>

</html>
