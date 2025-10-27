<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*GUEST SIDE */
Route::get('/', [AppController::class, 'welcome'])->name('welcome');
Route::get('/product/{id}', [ProductController::class, 'view'])->name('product.view');
Route::get('/cart/', [CartController::class, 'view'])->name('cart');

/* ADMIN SIDE */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/admin', function () {
        $categories = Category::all();
        return view('dashboard', ["categories" => $categories]);
    })->name('dashboard');

    Route::get('/categories', function () {
        $categories = Category::all();
        return view('categories', ["categories" => $categories]);
    })->name('category.view');
    Route::post('/category', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category-edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::get('/category-delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');

    Route::get('/products', function () {
        $products = Product::all();
        $categories = Category::all();
        return view('products', ["products" => $products, "categories" => $categories]);
    })->name('products.view');
    Route::post('/product', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product-edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::get('/product-delete/{id}', [ProductController::class, 'delete'])->name('product.delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
