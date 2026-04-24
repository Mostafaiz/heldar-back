<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/dhbag-logo.ico') }}" type="image/x-icon">
    <meta name="theme-color" content="#d67782">
    <title>پرداخت</title>
    @livewireStyles
    @vite(['resources/js/main.ts', 'resources/css/app.css'])
</head>

<body>
    {{ $slot }}
</body>

</html>