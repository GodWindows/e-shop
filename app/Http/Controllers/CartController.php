<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CartController extends Controller
{
    public function view(Request $request)
    {
        $categories = Category::all();


        return view('cart', [
            "categories" => $categories,
        ]);
    }
}
