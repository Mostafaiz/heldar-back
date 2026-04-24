<?php


namespace App\Livewire\Pages\Manager;

use App\Http\Dto\Auth\LoginManagerDto;
use App\Livewire\Forms\ManagerLoginForm;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class ManagerLoginPage extends Component
{
    public ManagerLoginForm $form;

    public function login()
    {
        $data = $this->form->validate();

        try {
            app(AuthenticationService::class)
                ->verifyManagerPassword(
                    LoginManagerDto::makeDto([
                        'phone' => Auth::user()->username,
                        'password' => $data['password'],
                    ])
                );

            return redirect()->intended(route('manager.dashboard.index'));
        } catch (\Throwable) {
            $this->addError('form.password', 'رمز عبور اشتباه است.');
        }
    }

    public function render()
    {
        return view('pages.manager.login-page');
    }
}
