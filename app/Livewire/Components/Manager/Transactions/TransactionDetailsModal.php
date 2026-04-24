<?php

namespace App\Livewire\Components\Manager\Transactions;

use App\Services\TransactionService;
use Livewire\Attributes\On;
use Livewire\Component;

class TransactionDetailsModal extends Component
{
    public $transaction;
    public bool $shippingStatus;
    public $chequeStatus;

    #[On('get-transaction-data')]
    public function loadTransactionData(int $transactionId)
    {
        try {
            $this->transaction = app(TransactionService::class)->getTransactionDetails($transactionId);
            $this->shippingStatus = $this->transaction->shipping_status;
            $this->chequeStatus = $this->transaction->status;
            $this->dispatch('stop-loading');
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در بارگذاری اطلاعات!');
            $this->dispatch('stop-loading');
        }
    }

    public function updatedChequeStatus()
    {
        switch ($this->chequeStatus) {
            case 'accepted':
                try {
                    app(TransactionService::class)->approveTransaction($this->transaction->id);
                    $this->dispatch('success', message: 'با موفقیت ثبت شد!');
                    $this->transaction->status = 'accepted';
                } catch (\Throwable $th) {
                    $this->dispatch('exception', message: 'خطا در انجام عملیات!');
                }
                break;

            case 'rejected':
                try {
                    app(TransactionService::class)->rejectTransaction($this->transaction->id);
                    $this->dispatch('success', message: 'با موفقیت ثبت شد!');
                    $this->transaction->status = 'rejected';
                } catch (\Throwable $th) {
                    $this->dispatch('exception', message: 'خطا در انجام عملیات!');
                }
                break;
        }

        $this->dispatch('load-transactions');
    }

    public function updatedShippingStatus()
    {
        try {
            app(TransactionService::class)->updateShippingStatus($this->transaction->id, $this->shippingStatus);
            $this->dispatch('success', message: 'با موفقیت ثبت شد!');
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }

        $this->dispatch('load-transactions');
    }

    public function resetData()
    {
        $this->reset('transaction', 'shippingStatus', 'chequeStatus');
    }

    public function render()
    {
        return view('components.manager.transactions.transaction-details-modal');
    }
}
