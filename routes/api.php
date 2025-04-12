<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryList;
use App\Http\Controllers\CategoryView;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductsList;
use App\Http\Controllers\ProductView;
use App\Http\Controllers\SendMailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('users', function (Request $request) {
    return response()->json([
        'users' => \App\Models\User::all(),
    ]);
});
Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('products', ProductsList::class)->name('products.list');
Route::get('product/{product_id}', ProductView::class)->name('product.view');

Route::get('categorys', CategoryList::class)->name('category.view');
Route::get('category/{category_id}', CategoryView::class)->name('categories.list');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/store', [CartController::class, 'store']);
    Route::put('cart/{id}', [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);

    Route::get('locations', [LocationController::class, 'index']);
    Route::post('locations/store', [LocationController::class, 'store']);
    Route::get('locations/{id}', [LocationController::class, 'show']);
    Route::put('locations/{id}', [LocationController::class, 'update']);
    Route::delete('locations/{id}', [LocationController::class, 'destroy']);
});

Route::post('sendEmail', SendMailController::class)->name('sendEmail');
