<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

//Kategori
Route::prefix('categories')->group(function() {
    Route::get('/', [CategoryController::class, 'index']);
});

//Unit
Route::prefix('unit')->group(function() {
    Route::get('/', [UnitController::class, 'index']);
});

// Produk
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);      // GET semua produk
    Route::post('/', [ProductController::class, 'store']);     // POST tambah produk
    Route::put('/{id}', [ProductController::class, 'update']); // PUT update produk
    Route::delete('/{id}', [ProductController::class, 'destroy']); // DELETE produk
});

// Cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);     // GET semua cart
    Route::post('/', [CartController::class, 'store']);    // POST tambah ke cart
    Route::post('/add-quantity', [CartController::class, 'addQty']);
    Route::post('/reduce-quantity', [CartController::class, 'reduceQty']);
    Route::delete('/{productId}', [CartController::class, 'destroy']); // DELETE item cart
    Route::delete('/', [CartController::class, 'clear']);  // DELETE semua cart
});

// Transaksi
Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);   // GET semua transaksi
    Route::post('/', [TransactionController::class, 'store']);  // POST buat transaksi baru
});
