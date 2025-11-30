<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/checkout', [CheckoutController::class, 'placeOrder']);
Route::get('/orders/{id}', [CheckoutController::class, 'getOrder']);
