<?php
namespace App\Livewire\Pages\Auth;

use App\Enums\Auth\AuthExceptionsEnum;
use App\Exceptions\Auth\AuthException;
use App\Exceptions\Auth\SendOtp\OtpAlreadySentException;
use App\Exceptions\Auth\VerifyOtp\OtpExpiredException;
use App\Http\Dto\Auth\LoginDto;
use App\Http\Dto\Auth\SendOtpDto;
use App\Http\Dto\Auth\UserFirstOrCreateDto;
use App\Http\Dto\Auth\VerifyOtpDto;
use App\Livewire\Forms\LoginForm;
use App\Models\User;
use App\Services\OtpService;
use App\Services\AuthenticationService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.auth')]
class LoginPage extends Component
{
    public LoginForm $form;
    public string $loginStep = 'SEND_OTP';
    public int $timer;

    public function mount(): void
    {
        if (Auth::check())
            $this->redirectAuthenticated();
    }

    private function setTimer(): void
    {
        $this->timer = config('otp.expire_time', 300);
    }

    // public function sendOtp(): void
    // {
    //     if ($this->loginStep !== 'SEND_OTP')
    //         return;

    //     $this->form->phone = convert_numbers_to_english($this->form->phone);
    //     $data = $this->form->validateOnly('phone');

    //     try {
    //         app(OtpService::class)->sendOtp(SendOtpDto::makeDto($data));
    //     } catch (AuthException $e) {
    //         if ($e->getCode() == AuthExceptionsEnum::OTP_ALREADY_SENT_CODE->value) {
    //             $this->addError('form.phone', 'کد تایید قبلا ارسال شده است.');
    //             return;
    //         }
    //     }

    //     $this->setTimer();
    //     $this->loginStep = 'VERIFY_OTP';
    // }


    public function sendOtp(): void
    {
        if ($this->loginStep !== 'SEND_OTP')
            return;

        $this->form->phone = convert_numbers_to_english($this->form->phone);
        $data = $this->form->validateOnly('phone');

        try {
            app(OtpService::class)->sendOtp(SendOtpDto::makeDto($data));

            $this->timer = config('otp.expire_time', 120);
        } catch (AuthException $e) {

            if ($e->getCode() == AuthExceptionsEnum::OTP_ALREADY_SENT_CODE->value) {

                $otp = app(OtpService::class)->getActiveOtp($data['phone']);
                $remaining = $otp->expires_at->diffInSeconds(now(), true);
                $this->timer = max(0, $remaining);
                $this->loginStep = 'VERIFY_OTP';
                return;
            }

            throw $e;
        }

        $this->loginStep = 'VERIFY_OTP';
    }

    public function resendOtp(): void
    {
        if ($this->loginStep !== 'VERIFY_OTP' || $this->timer > 0)
            return;

        $data = $this->form->validateOnly('phone');
        app(OtpService::class)->sendOtp(SendOtpDto::makeDto($data));
        $this->setTimer();
    }

    public function verifyOtp(): void
    {
        if ($this->loginStep !== 'VERIFY_OTP') {
            return;
        }

        $this->form->code = convert_numbers_to_english($this->form->code);
        $validated = $this->form->validate();

        try {
            $isValid = app(OtpService::class)->verifyOtp(
                VerifyOtpDto::makeDto($validated)
            );

            if (!$isValid) {
                $this->addError('form.code', 'کد تایید اشتباه است.');
                return;
            }
        } catch (AuthException $e) {

            if ($e->getCode() == AuthExceptionsEnum::OTP_MISMATCH_CODE->value) {
                $this->addError('form.code', 'کد تایید اشتباه است.');
                return;
            }

            if ($e->getCode() == AuthExceptionsEnum::OTP_EXPIRED_CODE->value) {
                $this->addError('form.code', 'کد تایید منقضی شده است.');
                return;
            }
        }

        $phone = $validated['phone'];
        app(AuthenticationService::class)
            ->loginWithOtp(
                UserFirstOrCreateDto::makeDto(['phone' => $phone]),
                new LoginDto(
                    user: User::firstOrCreate(['username' => $phone]),
                    remember: true
                )
            );

        $this->redirectAuthenticated();
    }

    private function redirectAuthenticated()
    {
        return redirect()->intended('/');
    }

    public function backToStep(string $step): void
    {
        $this->loginStep = $step;
    }

    public function render()
    {
        return view('pages.auth.login-page');
    }
}

