<?php

namespace App\Livewire\Components\Manager\Transactions;

use App\Models\User;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class UserTransactionsModal extends Component
{
    public $userId;
    public ?User $user;
    public array|Collection $transactions;

    public function mount()
    {
        $this->transactions = Collection::make();
    }

    #[On('load-user-transactions')]
    public function loadUserTransactions(int $userId)
    {
        $this->userId = $userId;
        try {
            $this->transactions = app(TransactionService::class)->getUserTransactions($this->userId);
            $this->user = app(UserService::class)->getUserById($userId);
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در دریافت اطلاعات!');
        }
    }

    public function resetData()
    {
        $this->transactions = [];
        $this->user = null;
    }

    public function render()
    {
        return view('components.manager.transactions.user-transactions-modal');
    }
}
