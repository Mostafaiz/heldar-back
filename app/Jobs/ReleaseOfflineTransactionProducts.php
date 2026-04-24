<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ReleaseOfflineTransactionProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $transactionId;

    public function __construct(int $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function handle()
    {
        $tx = Transaction::find($this->transactionId);

        if (!$tx) {
            return;
        }

        if ($tx->status === 'uploading') {
            foreach ($tx->cart->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $tx->update(['status' => 'failed']);
        }
    }
}
