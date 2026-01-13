<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::with(['category', 'offer'])->get();

        return view(
            'products.index',
            [
                'products' => $products,
                'breadcrumb' => [
                    'name' => 'products.index'
                ]
            ]
        );
    }

    /**
     * Display only products that have an active offer
     */
    public function onSale(): View
    {
        $products = Product::with(['category', 'offer'])
            ->whereHas('offer', function ($query) {
                $query->where('active', 1);
            })
            ->get();

        return view('products.on-sale', compact('products'));
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product): View
    {
        return view('products.show', [
            'product' => $product,
            'category' => $product->category,
            'inWishlist' => ($request->user() ? $request->user()->wishlist()->whereKey($product->id)->exists() : false),
            'breadcrumb' => [
                'name' => 'products.show',
                'params' => [$product]
            ]
        ]);
    }
}
