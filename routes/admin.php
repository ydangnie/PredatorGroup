<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\bannerController;
use App\Http\Controllers\VoucherController; // Nhớ import
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::middleware('auth', 'admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dasboard', [AdminController::class, 'index'])
        ->name('dasboard');

    Route::get('/admin/banner', [bannerController::class, 'index'])->name('admin.banner');
    Route::resource('voucher', VoucherController::class);
    Route::resource('category', CategoryController::class);
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        // ... các route khác ...
        Route::resource('product', ProductController::class);
    });
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    Route::get('/orders/{id}/print', [AdminOrderController::class, 'printInvoice'])->name('admin.orders.print');
});
