<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    /**
     * Show all categories
     */
    public function index(): View
    {
        $categories = Category::whereHas('products')->orderBy('name')->get();

        return view('categories.index', [
            'categories' => $categories,
            'breadcrumb' => [
                'name' => 'categories.index'
            ]
        ]);
    }

    /**
     * Show products from a specific category
     */
    public function show(Category $category): View
    {
        $categoryProducts = $category->products()->with(['offer'])->get();

        return view('categories.show', [
            'category' => $category,
            'categoryProducts' => $categoryProducts,
            'breadcrumb' => [
                'name' => 'categories.show',
                'params' => [$category]
            ]
        ]);
    }
}
