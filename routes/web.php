<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

Route::get('/', [ProductController::class, 'index']);

Route::get('/products/{product_id}', [ProductController::class, 'show'])->name('products.show');

Route::prefix('login')->name('login')->group(function () {

    Route::get('/', [AdminController::class, 'loginPage'])->name('');
    Route::post('/', [AdminController::class, 'login'])->name('.submit');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::prefix('products')->name('product')->group(function () {

            Route::get('/', [AdminController::class, 'products'])->name('');
            Route::get('/create', [AdminController::class, 'createProduct'])->name('.create');
            Route::post('/', [AdminController::class, 'storeProduct'])->name('.store');
            Route::get('/{id}/edit', [AdminController::class, 'editProduct'])->name('.edit');
            Route::put('/{id}', [AdminController::class, 'updateProduct'])->name('.update');
            Route::delete('/{id}', [AdminController::class, 'deleteProduct'])->name('.delete');
        });
    });
});
