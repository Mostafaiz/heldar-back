<?php

namespace App\Livewire\Components\Customer\Cart;

use App\Http\Dto\Request\Customer\Cart\GetCartItem as GetCartItemDto;
use App\Http\Dto\Request\Customer\Cart\UpdateCartItem as UpdateCartItemDto;
use App\Http\Dto\Response\Customer\Cart\Cart as CartDto;
use App\Livewire\Pages\Customer\Cart;
use App\Services\CartService;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CartItemRow extends Component
{
    #[Reactive]
    public CartDto $product;

    public function updateCartItem(bool $increase = true): void
    {
        $array = [
            'productId' => $this->product->id,
            'patternId' => $this->product->pattern->id,
            'sizeId' => $this->product->size?->id ?? null,
            'guaranteeId' => $this->product->guarantee?->id ?? null,
            'insuranceId' => $this->product->insurance?->id ?? null,
            'quantity' => 1,
        ];

        $dto = UpdateCartItemDto::makeDto($array);
        app(CartService::class)->updateCartItem($dto, $increase);
        $this->dispatch('load-user-cart');
    }

    public function increaseItem(int $id)
    {
        try {
            app(CartService::class)->increaseItem($id);
            $this->dispatch('load-user-cart');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function decreaseItem(int $id)
    {
        try {
            app(CartService::class)->decreaseItem($id);
            $this->dispatch('load-user-cart');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function removeItem(int $id): void
    {
        try {
            app(CartService::class)->removeItemById($id);
            $this->dispatch('load-user-cart');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function render()
    {
        return view('components.customer.cart.cart-item-row');
    }
}
