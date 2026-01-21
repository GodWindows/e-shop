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

    public function thankyou(Request $request) {
        $categories = Category::all();
        return view('thankyou', [
            "categories" => $categories,
        ]);
    }
}
