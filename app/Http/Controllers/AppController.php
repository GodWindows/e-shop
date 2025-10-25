<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function welcome(Request $request) {
        $categories = Category::all();
        $lastFourProducts = Product::latest()->take(4)->get();
        return view('welcome', [
            "categories" => $categories,
            "lastFourProducts" => $lastFourProducts,
        ]);
    }
}
