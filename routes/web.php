<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// Redirect berdasarkan role
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect('/dashboard') 
            : redirect('/shop');
    }
    return redirect('/login');
});

// Route bawaan Breeze (login, register, verify, logout, dll)
require __DIR__.'/auth.php';

// CUSTOMER ROUTES (login required)
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
});

// ADMIN ROUTES (hanya admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    
    // Order Management
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('admin.orders.approve');
    Route::patch('/admin/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('admin.orders.reject');
});
