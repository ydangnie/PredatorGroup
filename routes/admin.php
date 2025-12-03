<?php

use App\Http\Controllers\AdminController;

use App\Http\Controllers\bannerController;
use App\Http\Controllers\VoucherController; // Nhớ import
use App\Http\Controllers\SanPhamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


Route::redirect('/admin', '/admin/dashboard', 301);
Route::get('/admin/banner', [bannerController::class, 'index'])->name('admin.banner');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function(){
    // ... các route khác ...
    Route::get('dashboard', [AdminController::class, 'index'])
    ->name('dashboard');
    Route::get('/admin/banner', [bannerController::class, 'index'])->name('admin.banner');

    Route::resource('voucher', VoucherController::class);
    Route::resource('category', CategoryController::class);
    // product
    // author: vunamphi
    Route::resource('san-pham', SanPhamController::class);
   Route::resource('product', ProductController::class);
});

