<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;   // ← WAJIB ADA INI

// Redirect ke login
Route::get('/', function () {
    return redirect('/login');
});

// Route bawaan Breeze (login, register, verify, logout, dll)
require __DIR__.'/auth.php';

// Semua route yang hanya bisa diakses user login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile (opsional)
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // CRUD Category
    Route::resource('categories', CategoryController::class);

    // CRUD Product
    Route::resource('products', ProductController::class);   // ← INI SEKARANG BENAR
});
