<div class="container login shadow-md border-gray-200!">
    <img src="{{ asset('images/dhbag-logo.png') }}" class="w-20 my-4! object-cover" alt="">
    <h1 class="title">ورود | ثبت‌نام</h1>

    @if ($loginStep === 'SEND_OTP')
        <h3 class="description">
            خوش آمدید.
        </h3>
        <form wire:submit="sendOtp" class="inner-container">
            <x-blade.customer.input-text name="form.phone" title="شماره تلفن" />
            <button type="submit"
                class="w-full h-10 bg-primary flex items-center justify-center rounded-md font-shabnam text-white font-[500] hover:not-disabled:bg-primary-dark not-disabled:cursor-pointer transition disabled:bg-primary/50"
                wire:loading.attr="disabled" wire:target="sendOtp">
                <span wire:loading.remove wire:target="sendOtp">ارسال کد ورود</span>
                <i class="fa-solid fa-spinner animate-spin size-fit" wire:loading wire:target="sendOtp"></i>
            </button>
        </form>
    @elseif ($loginStep === 'VERIFY_OTP')
        <h3 class="description">
            <p>کد تایید به شماره {{ $form->phone }} ارسال شد.</p>
            <p>لطفا کد را وارد کنید.</p>
        </h3>

        <form wire:submit="verifyOtp" class="inner-container">
            <button type="button" class="back-button" wire:click="backToStep('SEND_OTP')">
                <i class="fa-solid fa-arrow-right"></i>
            </button>

            <livewire:components.customer.otp-input wire:model="form.code" />

            @error('form.code')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <button type="submit"
                class="w-full h-10 bg-primary flex items-center justify-center rounded-md font-shabnam text-white font-[500] hover:not-disabled:bg-primary-dark not-disabled:cursor-pointer transition disabled:bg-primary/50"
                wire:loading.attr="disabled" wire:target="verifyOtp">
                <span wire:loading.remove wire:target="verifyOtp">ورود</span>
                <i class="fa-solid fa-spinner animate-spin size-fit" wire:loading wire:target="verifyOtp"></i>
            </button>

            <button type="button" class="resend-button text-primary!" x-show="$wire.timer <= 0" wire:click="resendOtp">
                <span>ارسال مجدد کد</span>
            </button>

            <template x-if="$wire.timer > 0">
                <span class="timer text-neutral!" x-show="$wire.timer > 0" x-init="interval = setInterval(() => { if ($wire.timer > 0) $wire.timer--; if ($wire.timer <= 0) clearInterval(interval); }, 1000);">
                    <span x-text="Math.floor($wire.timer / 60)"></span>:<span
                        x-text="String($wire.timer % 60).padStart(2, '0')"></span>
                </span>
            </template>
        </form>
    @endif
</div>

@assets
    @vite(['resources/css/auth/login.scss'])
@endassets
