<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;


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
    /*
    public function showOld(string $id): View
    {

        // Validate ID format
        if (!is_numeric($id) || $id < 1) {
            abort(404, 'ID de categoría inválido');
        }

        $category = Category::find($id);
        if (!$category) {
            abort(404, 'Categoría no encontrada');
        }

        $categoryProducts = $category->products()->with(['offer'])->get();

        //return view('categories.show', compact('category', 'categoryProducts'));
        return view('categories.show', [
            'category' => $category,
            'categoryProducts' => $categoryProducts,
            'breadcrumb' => [
                'name' => 'categories.show',
                'params' => [$category]
            ]
        ]);
    }*/
}
