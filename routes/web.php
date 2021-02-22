<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\ProductsController::class, 'index'])->name('products');
Route::post('/carrito', [\App\Http\Controllers\CarritoController::class, 'store'])->name('carrito');
Route::get('/carrito', [\App\Http\Controllers\CarritoController::class, 'index'])->name('carritoListar');
Route::put('/carrito', [\App\Http\Controllers\CarritoController::class, 'update'])->name('carritoUpdate');
Route::delete('/carrito', [\App\Http\Controllers\CarritoController::class, 'destroy'])->name('carritoDelete');
Route::post('/orders', [\App\Http\Controllers\OrdersController::class, 'store'])->name('ordersSend');
Route::get('/response/{reference}', [\App\Http\Controllers\OrdersController::class, 'responseGet'])->name('responseget');
Route::get('/myOrders', [\App\Http\Controllers\OrdersController::class, 'myOrders'])->name('myOrders');

Auth::routes();
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

