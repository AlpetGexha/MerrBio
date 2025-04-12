<?php

use App\Http\Controllers\CategoryList;
use App\Http\Controllers\CategoryView;
use App\Http\Controllers\ProductsList;
use App\Http\Controllers\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SendMailController;

Route::get('/users', function (Request $request) {
    return response()->json([
        'users' => \App\Models\User::all(),
    ]);
});

Route::get('/products', ProductsList::class)->name('products.list');
Route::get('/product/{product_id}', ProductView::class)->name('product.view');

Route::get('/categorys', CategoryList::class)->name('category.view');
Route::get('/category/{category_id}', CategoryView::class)->name('categories.list');


Route::post('/sendEmail', SendMailController::class)->name('sendEmail');
