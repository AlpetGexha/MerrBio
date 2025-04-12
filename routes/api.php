<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ProductView;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryList;
use App\Http\Controllers\CategoryView;
use App\Http\Controllers\ProductsList;
use App\Http\Controllers\CartController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', ProductsList::class)->name('products.list');
Route::get('/product/{product_id}', ProductView::class)->name('product.view');

Route::get('/categorys', CategoryList::class)->name('category.view');
Route::get('/category/{category_id}', CategoryView::class)->name('categories.list');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/store', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});
