<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class CartService
{
    private string $sessionKey = 'cart';

    private function isPersistent(): bool
    {
        return Auth::check();
    }

    /**
     * Obtiene, o crea, carrito activo del usuario autenticado
     */
    private function userCart(): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'active']
        );
    }

    /*
    * Session helpers (invitado)
    */
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
        if (!$this->isPersistent()) {
            session()->forget($this->sessionKey);
            return;
        }

        $cart = $this->userCart();
        $cart->items()->delete();
    }

    /**
     * @return Collection<int, Product>
     *
     * Devuelve los productos del carrito, listos para vistas HTML
     */
    public function products(): Collection
    {
        // INVITADO -> session (tal cual lo tenías)
        if (!$this->isPersistent()) {
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

        // LOGUEADO -> BD (persistente)
        $cart = $this->userCart();

        $items = CartItem::where('cart_id', $cart->id)->get();
        if ($items->isEmpty()) {
            return collect();
        }

        $qtyByProductId = $items->pluck('quantity', 'product_id')->all();

        $products = Product::with(['category', 'offer'])
            ->whereIn('id', array_keys($qtyByProductId))
            ->get();

        return $products->map(function (Product $product) use ($qtyByProductId) {
            $product->quantity = (int) ($qtyByProductId[$product->id] ?? 1);
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
        $quantity = max(1, $quantity);

        // INVITADO -> session
        if (!$this->isPersistent()) {
            $cart = $this->raw();

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = ['quantity' => $quantity];
            }

            $this->put($cart);
            return;
        }

        // LOGUEADO -> BD
        $cart = $this->userCart();

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $productId,
        ]);

        $item->quantity = ($item->exists ? (int) $item->quantity : 0) + $quantity;

        // Guardar el precio unitario del momento (opcional pero “pro”)
        if (empty($item->unit_price)) {
            $product = Product::findOrFail($productId);
            $item->unit_price = (float) $product->final_price;
        }

        $item->save();
    }


    public function update(int $productId, int $quantity): void
    {
        $quantity = max(1, $quantity);

        // INVITADO -> session
        if (!$this->isPersistent()) {
            $cart = $this->raw();

            if (!isset($cart[$productId])) {
                return;
            }

            $cart[$productId]['quantity'] = $quantity;
            $this->put($cart);
            return;
        }

        // LOGUEADO -> BD
        $cart = $this->userCart();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (!$item) {
            return;
        }

        $item->quantity = $quantity;
        $item->save();
    }

    public function remove(int $productId): void
    {
        // INVITADO -> session
        if (!$this->isPersistent()) {
            $cart = $this->raw();

            if (!isset($cart[$productId])) {
                return;
            }

            unset($cart[$productId]);
            $this->put($cart);
            return;
        }

        // LOGUEADO -> BD
        $cart = $this->userCart();

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * Merge: pasa el carrito de session a BD (para usar en Login listener)
     */
    public function mergeSessionIntoUserCart(): void
    {
        if (!$this->isPersistent()) {
            return;
        }

        $sessionCart = $this->raw();
        if (empty($sessionCart)) {
            return;
        }

        $cart = $this->userCart();

        foreach ($sessionCart as $productId => $row) {
            $qty = max(1, (int) ($row['quantity'] ?? 1));
            $this->add((int) $productId, $qty); // add() ya escribe en BD si está logueado
        }

        // Limpiar session después de merge
        session()->forget($this->sessionKey);
    }
}
