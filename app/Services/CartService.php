<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private string $sessionKey = 'cart';

    public function raw(): array
    {
        return session()->get($this->sessionKey, []);
    }

    public function put(array $cart): void
    {
        session()->put($this->sessionKey, $cart);
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    /**
     * @return Collection<int, Product>
     *
     * Devuelve los productos del carrito, listos para vistas HTML
     */
    public function products(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        $products = Product::with(['category', 'offer'])
            ->whereIn('id', array_keys($cart))
            ->get();

        return $products->map(function (Product $product) use ($cart) {
            $product->quantity = $cart[$product->id]['quantity'];
            return $product;
        });
    }

    /**
     * Estado del carrito (para AJAX / header)
     */
    public function state(): array
    {
        $products = $this->products();

        $totalQuantity = 0;
        $total = 0.0;

        $items = $products->map(function (Product $product) use (&$totalQuantity, &$total) {
            $qty = (int) $product->quantity;
            $price = (float) $product->final_price;

            $subtotal = $price * $qty;

            $totalQuantity += $qty;
            $total += $subtotal;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => round($subtotal, 2),
                'image' => $product->image_url,
            ];
        });

        return [
            'totalQuantity' => $totalQuantity,
            'total' => round($total, 2),
            'items' => $items,
        ];
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->raw();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = ['quantity' => $quantity];
        }

        $this->put($cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->raw();

        if (!isset($cart[$productId])) {
            return;
        }

        $cart[$productId]['quantity'] = max(1, $quantity);
        $this->put($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();

        if (!isset($cart[$productId])) {
            return;
        }

        unset($cart[$productId]);
        $this->put($cart);
    }
}
