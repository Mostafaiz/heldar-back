<?php

namespace App\Livewire\Components\Customer\Orders;

use App\Services\TransactionService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderDetailsModal extends Component
{
    use WithFileUploads;

    public $transaction;
    public $image = null;


    #[On('get-order-data')]
    public function loadTransaction($id)
    {
        $this->transaction = app(TransactionService::class)->getTransactionDetails($id);
    }

    public function resetData()
    {
        $this->reset('transaction');
    }

    public function render()
    {
        return view('components.customer.orders.order-details-modal');
    }
}
