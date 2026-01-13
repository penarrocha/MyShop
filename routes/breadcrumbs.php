<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

use App\Models\Product;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Address;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Frontend
|--------------------------------------------------------------------------
*/

// Home
Breadcrumbs::for('welcome', function (Trail $trail) {
    $trail->push('Inicio', route('welcome'));
});

// Contacto
Breadcrumbs::for('contact', function (Trail $trail) {
    $trail->parent('welcome');
    $trail->push('Contacto', route('contact'));
});

/*
|--------------------------------------------------------------------------
| Categorías
|--------------------------------------------------------------------------
*/

// Categorías - listado
Breadcrumbs::for('categories.index', function (Trail $trail) {
    $trail->parent('welcome');
    $trail->push('Categorías', route('categories.index'));
});

// Categoría
Breadcrumbs::for('categories.show', function (Trail $trail, Category $category) {
    $trail->parent('categories.index');
    $trail->push($category->name, route('categories.show', $category));
});

/*
|--------------------------------------------------------------------------
| Productos
|--------------------------------------------------------------------------
*/

// Productos - listado
Breadcrumbs::for('products.index', function (Trail $trail) {
    $trail->parent('welcome');
    $trail->push('Productos', route('products.index'));
});

// Productos - en oferta
Breadcrumbs::for('products.on-sale', function (Trail $trail) {
    $trail->parent('products.index');
    $trail->push('En oferta', route('products.on-sale'));
});

// Producto
Breadcrumbs::for('products.show', function (Trail $trail, Product $product) {
    $trail->parent('products.index');
    $trail->push(
        $product->category->name,
        route('categories.show', $product->category)
    );
    $trail->push($product->name, route('products.show', $product));
});

/*
|--------------------------------------------------------------------------
| Ofertas
|--------------------------------------------------------------------------
*/

// Ofertas - listado
Breadcrumbs::for('offers.index', function (Trail $trail) {
    $trail->parent('welcome');
    $trail->push('Ofertas', route('offers.index'));
});

// Oferta
Breadcrumbs::for('offers.show', function (Trail $trail, Offer $offer) {
    $trail->parent('offers.index');
    $trail->push($offer->name, route('offers.show', $offer));
});

/*
|--------------------------------------------------------------------------
| Carrito
|--------------------------------------------------------------------------
*/

Breadcrumbs::for('cart.index', function (Trail $trail) {
    $trail->parent('welcome');
    $trail->push('Carrito', route('cart.index'));
});

/*
|--------------------------------------------------------------------------
| Mi cuenta (Auth / Frontend)
|--------------------------------------------------------------------------
*/

// Mi cuenta (dashboard lógico)
Breadcrumbs::for('dashboard', function (Trail $trail) {
    $trail->parent('welcome');
    $trail->push('Mi cuenta', route('account.index'));
});

// Mi cuenta - resumen
Breadcrumbs::for('account.index', function (Trail $trail) {
    $trail->parent('welcome');
    $trail->push('Mi cuenta', route('account.index'));
});

// Mi cuenta - datos personales
Breadcrumbs::for('profile.edit', function (Trail $trail) {
    $trail->parent('account.index');
    $trail->push('Mis datos', route('profile.edit'));
});

// Mi cuenta - favoritos
Breadcrumbs::for('account.wishlist.index', function (Trail $trail) {
    $trail->parent('account.index');
    $trail->push('Mis favoritos', route('account.wishlist.index'));
});

/*
|--------------------------------------------------------------------------
| Direcciones
|--------------------------------------------------------------------------
*/

// Direcciones - listado
Breadcrumbs::for('account.addresses.index', function (Trail $trail) {
    $trail->parent('account.index');
    $trail->push('Direcciones', route('account.addresses.index'));
});

// Direcciones - crear
Breadcrumbs::for('account.addresses.create', function (Trail $trail) {
    $trail->parent('account.addresses.index');
    $trail->push('Nueva dirección', route('account.addresses.create'));
});

// Direcciones - editar
Breadcrumbs::for('account.addresses.edit', function (Trail $trail, Address $address) {
    $trail->parent('account.addresses.index');
    $trail->push($address->label ?: 'Editar dirección', route('account.addresses.edit', $address));
});

/*
|--------------------------------------------------------------------------
| Pedidos
|--------------------------------------------------------------------------
*/

// Pedidos - listado
Breadcrumbs::for('account.orders.index', function (Trail $trail) {
    $trail->parent('account.index');
    $trail->push('Mis pedidos', route('account.orders.index'));
});

// Pedidos - detalle
Breadcrumbs::for('account.orders.show', function (Trail $trail, Order $order) {
    $trail->parent('account.orders.index');
    $trail->push('Pedido #' . $order->id, route('account.orders.show', $order));
});
