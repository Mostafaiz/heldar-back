<?php

namespace App\Livewire\Pages\Customer\Products;

use App\Exceptions\Product\ProductException;
use App\Exceptions\User\UserException;
use App\Http\Dto\Request\Customer\Cart\GetCartItem;
use App\Http\Dto\Request\Customer\Cart\UpdateCartItem as UpdateCartItemDto;
use App\Http\Dto\Request\Product\GetProduct;
use App\Services\CartService;
use App\Services\DemandService;
use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use stdClass;

#[Layout('layouts.customer')]
class ProductSinglePage extends Component
{
    public $product;
    public $slides;
    public $orders;
    public stdClass $selectedPattern;
    public stdClass $selectedSize;
    public int $quantity;
    public ?int $cartItemIndex;
    public string $name = 'hi';

    public function mount(int $id): void
    {
        try {
            $productService = app(ProductService::class);
            [$this->product, $this->slides, $this->orders] = $productService->getSinglePageProductVariants($id);
        } catch (ProductException $e) {
            abort(404);
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در بارگذاری اطلاعات!');
        }
    }

    public function changePattern(int $index)
    {
        $this->selectedPattern = $this->product->patterns[$index];
        $this->selectedSize = $this->selectedPattern->sizes[0];
        $this->setQuantity();
    }

    public function changeSize(int $index)
    {
        $this->selectedSize = $this->selectedPattern->sizes[$index];
        $this->setQuantity();
    }

    #[On('update-product-quantity')]
    public function setQuantity()
    {
        $dto = GetCartItem::makeDto(
            [
                'productId' => $this->product->id,
                'patternId' => $this->selectedPattern->id,
                'sizeId' => $this->selectedSize->id,
            ]
        );
        $cartItem = app(CartService::class)->getCartItemById($dto);
        $this->quantity = $cartItem->quantity ?? 0;
        $this->cartItemIndex = $cartItem->index ?? null;
    }

    public function updateCartItem(bool $increase = true): void
    {
        $dto = UpdateCartItemDto::makeDto(
            [
                'productId' => $this->product->id,
                'patternId' => $this->selectedPattern->id,
                'sizeId' => $this->selectedSize->id,
                'quantity' => 1
            ]
        );

        app(CartService::class)->updateCartItem($dto, $increase);
        $this->setQuantity();
        $this->dispatch('update-mini-cart');
    }

    public function removeItem(): void
    {
        $dto = UpdateCartItemDto::makeDto(
            [
                'productId' => $this->product->id,
                'patternId' => $this->selectedPattern->id,
                'quantity' => 1
            ]
        );

        app(CartService::class)->removeItem($dto);
        $this->setQuantity();
    }

    public function updateCartItems(array $items)
    {
        try {
            app(CartService::class)->updateCartItems($items);
            $this->dispatch('update-mini-cart');
            $this->dispatch('success-card', timer: 2);
        } catch (\Throwable $th) {
            if ($th->getCode() == 555)
                $this->dispatch('notify', type: 'error', message: 'سبد شما موقتا غیرفعال می‌باشد!');
            else
                $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function addRequests(array $requests)
    {
        try {
            app(DemandService::class)->addDemands($requests, $this->product->id);
            $this->dispatch('notify', type: 'success', message: 'درخواست‌ها با موفقیت ثبت شدند!');
        } catch (UserException $e) {
            $this->dispatch('notify', type: 'error', message: 'ابتدا باید لاگین کنید!');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'خطا در انجام عملیات!');
        }
    }

    public function render()
    {
        return view('pages.customer.products.product-single-page')->title($this->product?->name ?? '');
    }
}
