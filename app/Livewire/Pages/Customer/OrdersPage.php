<?php

namespace App\Livewire\Pages\Customer;

use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer')]
class OrdersPage extends Component
{
    use WithFileUploads;

    public Collection $transactions;
    public $image = null;
    public bool $isLoggedIn = false;

    public function mount()
    {
        $this->isLoggedIn = app(UserService::class)->isLoggedIn();
        $this->loadTransactions();
    }

    #[On('load-orders')]
    public function loadTransactions()
    {
        try {
            $this->transactions = app(TransactionService::class)->getCurrentCustomerTransactions();
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در بارگذاری اطلاعات!');
        }
    }

    public function uploadImage(int $id)
    {
        try {
            app(TransactionService::class)->updateOfflineTransactionImage($id, $this->image);
            $this->dispatch('notify', type: 'success', message: 'تصویر با موفقیت ارسال شد!');
            $this->loadTransactions();
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function deleteImage(int $transactionId)
    {
        try {
            app(TransactionService::class)->deleteOfflineTransactionImage($transactionId);
            $this->dispatch('notify', type: 'success', message: 'تصویر با موفقیت حذف شد!');
            $this->loadTransactions();
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function cancelOrder(int $transactionId)
    {
        try {
            app(TransactionService::class)->cancelOfflineTransaction($transactionId);
            $this->dispatch('notify', type: 'success', message: 'سفارش با موفقیت لغو شد!');
            $this->dispatch('load-orders');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function sendTransactionData(int $id)
    {
        $this->dispatch('get-order-data', $id);
    }

    public function render()
    {
        return view('pages.customer.orders-page');
    }
}
