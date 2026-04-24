<div class="w-full h-dvh bg-gradient-to-b to-error/40 flex flex-col items-center justify-center gap-6"
    x-data="{ timer: 3 }" x-init="setInterval(() => { if (timer > 0) timer--; else $wire.redirect('/orders') }, 1000)">
    <div class="size-55 rounded-full bg-red-200 flex items-center justify-center">
        <div class="size-45 rounded-full bg-red-300 flex items-center justify-center">
            <i class="fa-solid fa-xmark-circle text-9xl text-red-500"></i>
        </div>
    </div>
    <h1 class="font-shabnam text-3xl font-[700] max-md:text-2xl text-red-600">خطا در انجام تراکنش!</h1>
    <span class="font-shabnam -mt-3">
        <span x-text="timer"></span>
        ثانیه تا انتقال به صفحه سفارش‌ها
    </span>
</div>