<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/dhbag-logo-before.ico') }}" type="image/x-icon">
    <title>{{ $title ?? 'داشبورد' }}</title>
    @livewireStyles
    @vite(['resources/css/manager/style.scss'])
    @vite(['resources/js/main.ts', 'resources/js/manager/menu.ts'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-blade.manager.success-message />
    <x-blade.manager.error-message />
    <livewire:components.manager.right-menu />
    {{ $slot }}
    @livewireScriptConfig
    @stack('scripts')
</body>

</html>
