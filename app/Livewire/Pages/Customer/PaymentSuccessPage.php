<?php

namespace App\Livewire\Pages\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.checkout-result')]
class PaymentSuccessPage extends Component
{
    public ?string $message;
    public ?int $timer;

    public function mount()
    {
        $this->message = request()->get('message');
        $this->timer = request()->get('timer');
    }

    public function redirect($url, $navigate = false)
    {
        return parent::redirect($url, $navigate);
    }

    public function render()
    {
        return view('pages.customer.payment-success-page');
    }
}
