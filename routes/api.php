<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| NOTE ABOUT API ROUTE URL PREFIX
|--------------------------------------------------------------------------
| All routes in this file are automatically prefixed with "/api" by
| Laravel's RouteServiceProvider. 
| 
| Example:
| Route::post('/cart', ...) will be available at:
|   http://localhost:8000/api/cart
| 
| If you want a route to be available without the "/api" prefix:
|   1. Move it to routes/web.php
|   2. Or change/remove the prefix in app/Providers/RouteServiceProvider.php
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/payment_callback', [App\Http\Controllers\PaymentController::class, 'handleCallback']);
