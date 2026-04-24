<?php

use App\Enums\PermissionEnum;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\IsManagerMiddleware;
use App\Http\Middleware\ManagerAuthenticatedMiddleware;
use App\Livewire\Pages\Customer\Cart;
use App\Livewire\Pages\Customer\CheckoutPage;
use App\Livewire\Pages\Customer\Products\ProductList;
use App\Livewire\Pages\Customer\Products\CategoryView;
use App\Livewire\Pages\Customer\ProfilePage;
use App\Livewire\Pages\Manager\AttributesIndex;
use App\Livewire\Pages\Manager\ColorsIndex;
use App\Livewire\Pages\Manager\DashboardIndex;
use App\Livewire\Pages\Manager\ManagerLoginPage;
use App\Livewire\Pages\Manager\ProductsEdit;
use App\Livewire\Pages\Manager\ProductsIndex;
use App\Livewire\Pages\Manager\CategoriesIndex;
use App\Livewire\Pages\Manager\CategoriesCreate;
use App\Livewire\Pages\Manager\GalleryIndex;
use App\Livewire\Pages\Auth\LoginPage;
use App\Livewire\Pages\Customer\AboutPage;
use App\Livewire\Pages\Customer\PaymentSuccessPage;
use App\Livewire\Pages\Customer\DemandsPage;
use App\Livewire\Pages\Customer\HomePage;
use App\Livewire\Pages\Customer\InvoicePage;
use App\Livewire\Pages\Customer\OrdersPage;
use App\Livewire\Pages\Customer\PaymentFailedPage;
use App\Livewire\Pages\Customer\Products\ProductSinglePage;
use App\Livewire\Pages\Manager\HomeSlider;
use App\Livewire\Pages\Manager\CategoriesEdit;
use App\Livewire\Pages\Manager\DebitCardsIndex;
use App\Livewire\Pages\Manager\DemandsIndex;
use App\Livewire\Pages\Manager\InsurancesIndex;
use App\Livewire\Pages\Manager\ManagersIndex;
use App\Livewire\Pages\Manager\ProductsCreate;
use App\Livewire\Pages\Manager\SettingsIndex;
use App\Livewire\Pages\Manager\ShippingIndex;
use App\Livewire\Pages\Manager\SizesIndex;
use App\Livewire\Pages\Manager\TransactionsIndex;
use App\Livewire\Pages\Manager\UsersIndex;
use App\Models\Cart as ModelsCart;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Reliese\Database\Eloquent\Model;

// ------------------------- Customer Routes -------------------------

Route::prefix('/')->group(function () {
    Route::get('/', HomePage::class)->name('customer.index');
    Route::get('product/{id}', ProductSinglePage::class)->name('customer.product');
    Route::get('products/{id?}', ProductList::class)->name('customer.products');
    Route::get('categories/{id}', CategoryView::class)->name('customer.categories');
    Route::get('cart', Cart::class)->name('customer.cart');
    Route::get('demands', DemandsPage::class)->name('customer.demands');
    Route::get('orders', OrdersPage::class)->name('customer.orders');
    Route::get('checkout', CheckoutPage::class)->name('customer.checkout');
    Route::get('payment/success', PaymentSuccessPage::class)->name('customer.payment.success');
    Route::get('payment/failed', PaymentFailedPage::class)->name('customer.payment.failed');
    Route::get('invoice/{id}', InvoicePage::class)->name('customer.invoice');
    Route::get('about', AboutPage::class)->name('customer.about');
    Route::get('/payment/callback', function (Request $request, TransactionService $service) {
        $res = $service->handleCallback($request);
        if ($res['success']) {
            return redirect(route('customer.payment.success'));
        }
        return redirect(route('customer.payment.failed'));
    })->name('payment.callback');
});


Route::get('/profile', ProfilePage::class)->name('customer.profile');


Route::delete('/', function () {
    Auth::logout();
    return redirect()->route('login');
});

// ------------------------- Manager Routes -------------------------
Route::prefix('manager')->middleware([
    IsManagerMiddleware::class,
    ManagerAuthenticatedMiddleware::class
])->group(function () {

    Route::get('/login', ManagerLoginPage::class)->withoutMiddleware(ManagerAuthenticatedMiddleware::class)->name('manager.login');

    // ------------------------- Dashboard Routes -------------------------
    Route::get('/', DashboardIndex::class)->name('manager.dashboard.index');

    // ------------------------- Users Routes -------------------------
    Route::prefix('users')->group(function (): void {
        Route::get('/', UsersIndex::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_USERS->value)
            ->name('manager.users.index');
        Route::get('managers', ManagersIndex::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_USERS->value)
            ->name('manager.users.managers.index');
    });

    // ------------------------- Products Routes -------------------------
    Route::prefix('products')->group(function (): void {
        Route::get('/', ProductsIndex::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_PRODUCTS->value)
            ->name("manager.products.index");
        Route::get('/create', ProductsCreate::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_PRODUCTS->value)
            ->name("manager.products.create");
        Route::get('/{id}/edit', ProductsEdit::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_PRODUCTS->value)
            ->name('manager.products.edit');
    });

    // ------------------------- Categories Routes -------------------------
    Route::prefix('categories')->group(function (): void {
        Route::get('/', CategoriesIndex::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_CATEGORIES->value)
            ->name("manager.categories.index");
        Route::get('create', CategoriesCreate::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_CATEGORIES->value)
            ->name("manager.categories.create");
        Route::get('{id}/edit', CategoriesEdit::class)
            ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_CATEGORIES->value)
            ->name("manager.categories.edit");
    });

    // ------------------------- Colors Routes -------------------------
    Route::get('/colors', ColorsIndex::class)
        ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_COLORS->value)
        ->name('manager.colors.index');

    // ------------------------- Attributes Routes -------------------------
    Route::get('/attributes', AttributesIndex::class)
        ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_ATTRIBUTES->value)
        ->name('manager.attributes.index');

    // ------------------------- Sizes Routes -------------------------
    // Route::get('/sizes', SizesIndex::class)
    //     ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_SIZES->value)
    //     ->name('manager.sizes.index');

    // ------------------------- Guarantees Routes -------------------------
    // Route::get('/guarantees', GuaranteesIndex::class)
    //     ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_GUARANTEES->value)
    //     ->name('manager.guarantees.index');

    // ------------------------- Insurances Routes -------------------------
    // Route::get('/insurances', InsurancesIndex::class)
    //     ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_INSURANCES->value)
    //     ->name('manager.insurances.index');

    // ------------------------- Shipping Routes -------------------------
    Route::get('/shipping', ShippingIndex::class)
        ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_INSURANCES->value)
        ->name('manager.shipping.index');

    // ------------------------- Gallery Routes -------------------------
    Route::get('/gallery', GalleryIndex::class)
        ->middleware(CheckPermission::class . ':' . PermissionEnum::MANAGE_GALLERY->value)
        ->name("manager.gallery.index");

    // ------------------------- Demands Routes -------------------------
    Route::get('/demands', DemandsIndex::class)
        ->name('manager.demands.index');

    // ------------------------- Transactions Routes -------------------------
    Route::get('/transactions', TransactionsIndex::class)
        ->name('manager.transactions.index');

    // ------------------------- Slider Routes -------------------------
    Route::get('/slider', HomeSlider::class)
        ->name('manager.slider');

    // ------------------------- Debit Cards Routes -------------------------
    Route::get('debit-cards', DebitCardsIndex::class)
        ->name('manager.debit-cards.index');

    // ------------------------- Site Configs Routes -------------------------
    Route::get('/settings', SettingsIndex::class)
        ->name('manager.settings');
});

Route::get('login', LoginPage::class)->name('login');
