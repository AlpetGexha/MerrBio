<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/app');
})->name('home');
//
//Route::get('/login/app', function () {
//return redirect('/app');
//})->name('login.app.app');

 Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
 });

 require __DIR__ . '/settings.php';
 require __DIR__ . '/auth.php';
