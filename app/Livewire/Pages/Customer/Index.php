<?php

namespace App\Livewire\Pages\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class Index extends Component
{
	public function render()
	{
		return view('pages.customer.index');
	}
}