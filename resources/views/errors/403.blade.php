<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>دسترسی غیر مجاز!</title>
    @vite(['resources/css/customer/errors.scss'])
</head>

<body>
    <h1>403</h1>
    <h3>شما به این صفحه دسترسی ندارید!</h3>

    <button type="button" class="back-button">
        <i class="fa-solid fa-flesh-right"></i>
        <a href="{{ route('customer.index') }}">بازگشت به صفحه اصلی</a>
    </button>
</body>

</html>