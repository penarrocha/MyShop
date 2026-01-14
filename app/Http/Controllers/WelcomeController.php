<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    /**
     * Show the welcome page with featured content
     */
    public function index(): View
    {
        $discountProducts = Product::with(['category', 'offer'])
            ->whereNotNull('offer_id')
            ->take(3)
            ->get();

        $featuredCategories = Category::all();

        return view(
            'welcome',
            [
                'discountProducts' => $discountProducts,
                'featuredCategories' => $featuredCategories,
                'breadcrumb' => [
                    'name' => null
                ]
            ]
        );
    }
}
