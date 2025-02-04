<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/make_order',  [OrderController::class, 'createOrder']);
Route::get('/orders',  [OrderController::class, 'showOrders']);
Route::get('/order/{id}',  [OrderController::class, 'showOrderRations']);
