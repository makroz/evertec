<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\ProductsController::class, 'index'])->name('products');
Route::post('/carrito', [\App\Http\Controllers\CarritoController::class, 'store'])->name('carrito');
Route::get('/carrito', [\App\Http\Controllers\CarritoController::class, 'index'])->name('carritoListar');
Route::put('/carrito', [\App\Http\Controllers\CarritoController::class, 'update'])->name('carritoUpdate');
Route::delete('/carrito', [\App\Http\Controllers\CarritoController::class, 'destroy'])->name('carritoDelete');
Route::post('/orders', [\App\Http\Controllers\OrdersController::class, 'store'])->name('ordersSend');
Route::post('/response/{reference}', [\App\Http\Controllers\OrdersController::class, 'response'])->name('response');
Route::get('/response/{reference}', [\App\Http\Controllers\OrdersController::class, 'responseGet'])->name('responseget');

Auth::routes();
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

