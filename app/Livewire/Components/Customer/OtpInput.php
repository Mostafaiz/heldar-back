<?php

namespace App\Livewire\Components\Customer;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class OtpInput extends Component
{
	#[Modelable]
	public string $value;
	public function render()
	{
		return view('components.customer.otp-input');
	}
}
