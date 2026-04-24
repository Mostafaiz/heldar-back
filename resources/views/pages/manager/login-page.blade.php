<div class="container login">
    <img src="{{ asset('images/dhbag-logo.png') }}" class="w-20 my-4! object-cover" alt="">
    <h1 class="title">ورود به مدیریت</h1>
    <h3 class="description">رمز عبور خود را وارد نمایید.</h3>
    <form wire:submit="login" class="inner-container">
        <x-blade.customer.input-text type="password" name="form.password" title="رمز عبور" />
        <button
            class="w-full h-10 bg-primary flex items-center justify-center rounded-md font-shabnam text-white font-[500] hover:not-disabled:bg-primary-dark not-disabled:cursor-pointer transition disabled:bg-primary/50"
            wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">ورود</span>
            <i class="fa-solid fa-spinner animate-spin size-fit" wire:loading wire:target="login"></i>
        </button>
    </form>
</div>

@assets
    @vite(['resources/css/auth/login.scss'])
@endassets
