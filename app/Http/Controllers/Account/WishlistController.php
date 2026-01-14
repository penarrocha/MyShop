<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $products = $request->user()
            ->wishlist()
            ->with(['category', 'offer'])
            ->get();

        return view('account.wishlist.index', compact('products'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->user()->wishlist()->syncWithoutDetaching([$product->id]);

        return back()->with('success', 'Añadido a favoritos.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->wishlist()->detach($product->id);

        return back()->with('success', 'Eliminado de favoritos.');
    }
}
