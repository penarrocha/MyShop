<?php

namespace App\View\Composers;

use App\Services\CartService;
use Illuminate\View\View;

class CartViewComposer
{
    public function __construct(private CartService $cart)
    {
    }

    public function compose(View $view): void
    {
        $state = $this->cart->state();

        $view->with([
            'cartQuantity' => $state['totalQuantity'],
            'cartTotal' => $state['total'],
        ]);
    }
}
