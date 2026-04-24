<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReleaseOfflineTransactionProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:release-offline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release reserved products of expired offline transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('method', 'cheque')
            ->where('status', 'uploading')
            ->where('created_at', '<=', now()->subDay())
            ->orderByDesc('created_at')
            ->limit(1000)
            ->with('cart.items.variant')
            ->get();

        foreach ($transactions as $tx) {
            DB::transaction(function () use ($tx) {

                foreach ($tx->cart->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('quantity', $item->quantity);
                    }
                }

                $tx->update([
                    'status' => 'failed',
                ]);
            });
        }
        return Command::SUCCESS;
    }
}
