<div class="w-full h-dvh bg-gradient-to-b to-success/40 flex flex-col items-center justify-center gap-6"
    x-data="{ timer: {{ $timer ?? 3 }} }" x-init="setInterval(() => {
        if (timer > 0) timer--;
        else $wire.redirect('/orders')
    }, 1000)">
    <div class="size-55 rounded-full bg-green-200 flex items-center justify-center">
        <div class="size-45 rounded-full bg-green-300 flex items-center justify-center">
            <i class="fa-solid fa-check-circle text-9xl text-green-500"></i>
        </div>
    </div>
    <h1 class="font-shabnam text-3xl font-[700] max-md:text-2xl text-green-600">سفارش شما ثبت شد!</h1>
    @if ($message)
        <span class="font-shabnam font-[500] text-lg">{{ $message }}</span>
    @endif
    <span class="font-shabnam -mt-3">
        <span x-text="timer"></span>
        ثانیه تا انتقال به صفحه سفارش‌ها
    </span>
</div>
