<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Services\CartService;

class PersistCartOnLogout
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Logout $event): void
    {
        $this->cartService->persistUserCartToSession();
    }
}
