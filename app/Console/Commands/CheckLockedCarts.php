<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;

class CheckLockedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:check-locked-carts';

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
        Cart::where('locked', '=', 1)
            ->where('updated_at', '<=', now()->subMinutes(20))
            ->orderByDesc('updated_at')
            ->limit(1000)
            ->update([
                'locked' => false,
            ]);
    }
}
