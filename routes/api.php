<?php

use App\Http\Controllers\CategoryList;
use App\Http\Controllers\CategoryView;
use App\Http\Controllers\ProductsList;
use App\Http\Controllers\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', ProductsList::class)->name('products.list');
Route::get('/product/{product_id}', ProductView::class)->name('product.view');

Route::get('/categorys', CategoryList::class)->name('category.view');
Route::get('/category/{category_id}', CategoryView::class)->name('categories.list');
