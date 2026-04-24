<?php

namespace App\Livewire\Pages\Customer;

use App\Models\SiteConfig;
use App\Services\SiteConfigService;
use App\Services\TransactionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.print')]
class InvoicePage extends Component
{
    public $transaction;
    public ?array $siteConfig;
    public string $priceInWordsFa = '';

    public function mount(int $id)
    {
        $this->transaction = app(TransactionService::class)->getTransactionDetails($id);
        $this->siteConfig = app(SiteConfigService::class)->getData();
        $this->priceInWordsFa = priceToWordsFa($this->transaction->amount);
    }
    public function render()
    {
        return view('pages.customer.invoice-page');
    }
}
