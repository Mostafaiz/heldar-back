<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/dhbag-logo.ico') }}" type="image/x-icon">
    <title>فاکتور فروش</title>
    @livewireStyles
    @vite(['resources/css/print.css'])
</head>

<body class="flex flex-wrap justify-center pt-10">
<div class="no-print" style="text-align: center; margin: 20px;">
    <button onclick="window.print()" class="font-shabnam cursor-pointer"
            style="padding: 8px 20px; background: #2563eb; color: white; border-radius: 6px;">
        چاپ
    </button>
</div>

<div class="sheet-preview">
    <div class="print-wrapper p-5 flex items-center justify-center">
        {{ $slot }}
    </div>
</div>
@livewireScriptConfig
</body>

</html>