<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Console\Command;

class CheckGatewayTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:release-online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('method', operator: 'online')
            ->where('gateway', 'zarinpal')
            ->where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes(10))
            ->orderByDesc('created_at')
            ->limit(1000)
            ->get();
        $transactionService = app(TransactionService::class);

        foreach ($transactions as $tx) {
            $authority = $tx->authority ?? null;

            if (!$authority) {
                return [
                    'success' => false,
                    'message' => 'authority_missing',
                ];
            }

            $transactionService->verifyByAuthority($authority);
        }
    }
}
