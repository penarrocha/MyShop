<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\CartController;

// Account (frontend autenticado)
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\WishlistController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\OrderController;

// Admin
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOfferController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Contacto
Route::view('/contacto', 'contact')->name('contact');

// Categorías
Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categoria/{category:slug}.html', [CategoryController::class, 'show'])->name('categories.show');

// Productos
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/producto/{product:slug}.html', [ProductController::class, 'show'])->name('products.show');
Route::get('/productos-en-oferta', [ProductController::class, 'onSale'])->name('products.on-sale');

// Ofertas
Route::get('/ofertas', [OfferController::class, 'index'])->name('offers.index');
Route::get('/oferta/{offer:slug}.html', [OfferController::class, 'show'])->name('offers.show');

// Carrito
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Carrito AJAX / header
Route::get('/cart/state', [CartController::class, 'state'])->name('cart.state');
Route::post('/cart/items', [CartController::class, 'ajaxStore'])->name('cart.items.store');
Route::delete('/cart/items/{id}', [CartController::class, 'ajaxDestroy'])->name('cart.items.destroy');



/*
|--------------------------------------------------------------------------
| MI CUENTA (FRONTEND AUTENTICADO)
|--------------------------------------------------------------------------
| Protegido por auth + role:customer
*/

Route::middleware(['auth', 'role:customer'])->group(function () {

    // Breeze -> redirige a Mi cuenta
    Route::redirect('/dashboard', '/mi-cuenta')->name('dashboard');

    // Alias legacy Breeze (sin lógica propia)
    Route::redirect('/profile', '/mi-cuenta/datos');

    Route::prefix('mi-cuenta')->name('account.')->group(function () {

        // Hub
        Route::view('/', 'account.index')->name('index');

        // ----------------------------
        // Datos personales
        // ----------------------------
        Route::get('/datos', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/datos', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        // ----------------------------
        // Direcciones
        // ----------------------------
        Route::resource('direcciones', AddressController::class)
            ->except(['show'])
            ->names('addresses')
            ->parameters(['direcciones' => 'address']);

        // ----------------------------
        // Pedidos
        // ----------------------------
        Route::get('/pedidos', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/pedidos/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        // ----------------------------
        // Favoritos (Wishlist)
        // ----------------------------
        Route::get('/favoritos', [WishlistController::class, 'index'])
            ->name('wishlist.index');

        Route::post('/favoritos/{product:id}', [WishlistController::class, 'store'])
            ->name('wishlist.store');

        Route::delete('/favoritos/{product:id}', [WishlistController::class, 'destroy'])
            ->name('wishlist.destroy');
    });
});



/*
|--------------------------------------------------------------------------
| ADMINISTRACIÓN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin', 'log.activity'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('categories', AdminCategoryController::class)
            ->except(['show'])
            ->parameters(['categories' => 'category:id']);

        Route::resource('offers', AdminOfferController::class)
            ->except(['show'])
            ->parameters(['offers' => 'offer:id']);

        Route::resource('orders', AdminOrderController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->parameters(['orders' => 'order:id']);

        Route::resource('products', AdminProductController::class)
            ->except(['show'])
            ->parameters(['products' => 'product:id']);

        Route::resource('users', AdminUserController::class)
            ->except(['show'])
            ->parameters(['users' => 'user:id']);
    });



// Auth (login, register, etc. - Breeze)
require __DIR__ . '/auth.php';
