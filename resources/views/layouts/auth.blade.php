<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/dhbag-logo.ico') }}" type="image/x-icon">
    <title>کیف داریوش</title>
    @livewireStyles
    @vite(['resources/css/auth/style.scss', 'resources/css/app.css'])
    @vite(['resources/js/main.ts'])
</head>

<body>
    {{ $slot }}
    @livewireScriptConfig
</body>

</html>