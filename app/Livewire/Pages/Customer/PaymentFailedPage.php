<?php

namespace App\Livewire\Pages\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.checkout-result')]
class PaymentFailedPage extends Component
{
    public function redirect($url, $navigate = false)
    {
        return parent::redirect($url, $navigate);
    }

    public function render()
    {
        return view('pages.customer.payment-failed-page');
    }
}
