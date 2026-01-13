<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\CartViewComposer;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        Order::class => \App\Policies\OrderPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            /*
            // Nº artículos en el carrito (siempre)
            $cartItems = include database_path('data/mock-cart.php');
            $cartCount = array_sum(array_column($cartItems, 'quantity'));
            $view->with('cartCount', $cartCount);
            */

            // Breadcrumbs SOLO si la ruta NO empieza por "admin"
            $routeName = request()->route()?->getName(); // puede ser null
            $isAdminRoute = $routeName && str_starts_with($routeName, 'admin.');

            if (! $isAdminRoute) {
                $data = $view->getData();
                $view->with('breadcrumbName', $data['breadcrumb']['name'] ?? null);
                $view->with('breadcrumbParams', $data['breadcrumb']['params'] ?? []);
                $view->with('cartUrls', [
                    'index'       => route('cart.index'),
                    'state'       => route('cart.state'),
                    'ajaxStore'   => route('cart.items.store'),
                    'ajaxDestroy' => route('cart.items.destroy', ['id' => '__ID__'])
                ]);
            } else {
                // opcional: asegurar que existan para evitar "undefined variable" en blades compartidos
                $view->with('breadcrumbName', null);
                $view->with('breadcrumbParams', []);
                // Por si en algún momento el tpl de admin comparte el mismo layout que el public
                $view->with('cartJs', null);
            }
        });

        View::composer(['partials.public.header', 'partials.public.navigation'], CartViewComposer::class);
    }
}
