<?php

use App\Http\Controllers\AdminController;

use App\Http\Controllers\bannerController;
use App\Http\Controllers\SanPhamController;
use Illuminate\Support\Facades\Route;

Route::redirect('/admin', '/admin/dashboard', 301);

Route::middleware('auth', 'admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])
        ->name('dashboard');
    Route::get('/admin/banner', [bannerController::class, 'index'])->name('admin.banner');

    // product
    // author: vunamphi
    Route::resource('san-pham', SanPhamController::class);
});
