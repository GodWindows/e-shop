<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function welcome(Request $request) {
        $categories = Category::all();
        $allProducts = Product::all();
        return view('welcome', [
            "categories" => $categories,
            "products" => $allProducts,
        ]);
    }

    /**
     * Liste des produits, filtrée sur une catégorie si un id est fourni.
     */
    public function shop(Request $request, $category = null) {
        $categories = Category::all();
        $currentCategory = $category ? Category::find($category) : null;

        if ($category && !$currentCategory) {
            abort(404);
        }

        $products = $currentCategory
            ? Product::where('category_id', $currentCategory->id)->orderByDesc('created_at')->get()
            : Product::orderByDesc('created_at')->get();

        return view('shop', [
            "categories" => $categories,
            "currentCategory" => $currentCategory,
            "products" => $products,
        ]);
    }

    public function thankyou(Request $request) {
        $categories = Category::all();
        return view('thankyou', [
            "categories" => $categories,
        ]);
    }
}
