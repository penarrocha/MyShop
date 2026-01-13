<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): View
    {
        $cartProducts = $this->cart->products();

        if ($cartProducts->isEmpty()) {
            return view('cart.empty');
        }

        $cartState = $this->cart->state(); // totalQuantity, total, items...

        return view('cart.index', compact('cartProducts', 'cartState'));
    }

    /**
     * obtener ítems del carrito ajax
     */

    public function state(): JsonResponse
    {
        return response()->json($this->cart->state());
    }

    /**
     * añadir artículo al carrito ajax
     */
    public function ajaxStore(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $this->cart->add((int) $request->product_id);

        return response()->json($this->cart->state());
    }

    /**
     * eliminar ítems del carrito ajax
     */
    public function ajaxDestroy(int $id): JsonResponse
    {
        $this->cart->remove($id);

        return response()->json($this->cart->state());
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $this->cart->add((int) $request->product_id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($this->cart->state());
        }

        return redirect()
            ->route('cart.index')
            ->with('success', '¡Producto añadido al carrito!');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cart->update($id, (int) $request->quantity);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cantidad actualizada correctamente');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->cart->remove($id);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Producto eliminado del carrito');
    }

    public function checkout(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()
            ->route('welcome')
            ->with('success', '¡Pedido realizado con éxito! Gracias por tu compra');
    }
}
