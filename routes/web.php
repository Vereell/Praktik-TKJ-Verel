<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/pesan', [OrderController::class, 'store'])->name('order.store');

// Login admin
Route::get('/admin', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/stock', [ProductController::class, 'stock'])->name('stock');
    Route::patch('/stock/{product}', [ProductController::class, 'updateStock'])->name('stock.update');

    Route::get('/discounts', [ProductController::class, 'discounts'])->name('discounts');
    Route::patch('/discounts/{product}', [ProductController::class, 'updateDiscount'])->name('discounts.update');

    Route::get('/variants', [VariantController::class, 'index'])->name('variants');
    Route::post('/variants', [VariantController::class, 'store'])->name('variants.store');
    Route::delete('/variants/{variant}', [VariantController::class, 'destroy'])->name('variants.destroy');

    Route::get('/sizes', [SizeController::class, 'index'])->name('sizes');
    Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store');
    Route::patch('/sizes/{size}', [SizeController::class, 'update'])->name('sizes.update');
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});
