<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function welcome(Request $request) {
        $categories = Category::all();
        return view('welcome', [
            "categories" => $categories,
        ]);
    }
}
