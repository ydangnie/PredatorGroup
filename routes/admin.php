<?php

use App\Http\Controllers\AdminController;

use App\Http\Controllers\bannerController;
use App\Http\Controllers\VoucherController; // Nhớ import
use App\Http\Controllers\SanPhamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
Route::middleware('auth', 'admin')->prefix('admin')->name('admin.')->group(function(){
Route::get('dasboard',[AdminController::class, 'index'])
->name('dasboard');

Route::redirect('/admin', '/admin/dashboard', 301);

Route::get('/admin/banner', [bannerController::class, 'index'])->name('admin.banner');
Route::resource('voucher', VoucherController::class);
Route::resource('category', CategoryController::class);
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function(){
    // ... các route khác ...
   Route::resource('product', ProductController::class);
});
Route::middleware('auth', 'admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])
        ->name('dashboard');
    Route::get('/admin/banner', [bannerController::class, 'index'])->name('admin.banner');

    // product
    // author: vunamphi
    Route::resource('san-pham', SanPhamController::class);
});
