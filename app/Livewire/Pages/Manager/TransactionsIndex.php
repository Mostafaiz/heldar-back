<?php

namespace App\Livewire\Pages\Manager;

use App\Services\TransactionService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.manager')]
class TransactionsIndex extends Component
{
    public string $pageTitle = 'لیست تراکنش‌ها';
    public string $routeName = 'manager.transactions.index';
    public $transactions;
    public $paginator;
    #[Url]
    public int $page = 1;

    public function mount()
    {
        $this->loadTransactions();
    }

    #[On('load-transactions')]
    public function loadTransactions()
    {
        try {
            $serviceData = app(TransactionService::class)->getPaginatedTransactions(20, $this->page);
            $this->transactions = $serviceData['data'];
            $this->paginator = $serviceData['pagination'];
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در بارگذاری اطلاعات!');
        }
    }

    public function sendTransactionData(int $index)
    {
        $this->dispatch('get-transaction-data', $this->transactions[$index]);
    }

    public function acceptTransaction(int $id)
    {
        try {
            app(TransactionService::class)->approveTransaction($id);
            $this->dispatch('success', message: 'تراکنش با موفقیت تایید شد!');
            $this->dispatch('get-transaction-data', $id);
            $this->loadTransactions();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }
    }

    public function rejectTransaction(int $id)
    {
        try {
            app(TransactionService::class)->rejectTransaction($id);
            $this->dispatch('success', message: 'تراکنش با موفقیت رد شد!');
            $this->dispatch('get-transaction-data', $id);
            $this->loadTransactions();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }
    }

    public function nextPage()
    {
        $this->page++;
        $this->loadTransactions();
    }

    public function previousPage()
    {
        $this->page--;
        $this->loadTransactions();
    }

    public function goToPage(int $page)
    {
        $this->page = $page;
        $this->loadTransactions();
    }

    public function render()
    {
        return view('pages.manager.transactions-index');
    }
}
