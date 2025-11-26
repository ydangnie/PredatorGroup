<?php

use App\Http\Controllers\AuthDangKy;
use App\Http\Controllers\AuthDangNhap;


use App\Http\Controllers\bannerController;
use App\Http\Controllers\BannerController as ControllersBannerController;
use App\Http\Controllers\ChiTietSanPhamCtr;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LienHeController;
use App\Http\Controllers\LoginGG;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\UserConTroller;
use App\Http\Controllers\UsersController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/users', [UserConTroller::class, 'index'])->middleware('access.time');



Route::controller(HomeController::class)-> group(function(){
    Route::get('/', 'index');
    Route::get('/about', 'about');

});
Route::prefix('users')->controller(UsersController::class)->group(function () {
    Route::get('/', 'index')->name('users.index');
    Route::get('/create', 'create')->name('users.create'); // Tên đầy đủ là 'users.create'
    Route::post('/store', 'store')->name('users.store');
});

Route::prefix('posts')->controller(PostController::class)
->name('posts.')
->group(function(){
Route::get('/', 'index')->name('index');
Route::get('/create', 'create')->name('create');
Route::post('/', 'store')->name('store');
Route::get('/{id}', 'edit')->name('edit');
route::put('/{id}', 'update')->name('update');
Route::get('/{id}/delete', 'delete')->name('delete');
});
Route::get('dangky', [AuthDangKy::class, 'dangky']);
Route::post('dangky', [AuthDangKy::class, 'postdangky'])->name('postdangky');

Route::get('dangnhap', [AuthDangKy::class, 'dangnhap'])->name('login');
Route::post('dangnhap', [AuthDangKy::class, 'postdangnhap'])->name('postdangnhap');
Route::get('dangxuat', [AuthDangKy::class, 'dangxuat'])->name('dangxuat');


Route::get('sanpham', [SanPhamController::class, 'sanpham'])->name('sanpham');
Route::get('lienhe', [LienHeController::class, 'lienhe'])->name('lienhe');
Route::get('chitietsanpham', [ChiTietSanPhamCtr::class, 'chitietsanpham'])->name('chitietsanpham');

Route::get('giohang', [GioHangController::class, 'giohang'])->name('giohang');

// ... (giữ nguyên đoạn trên)

Route::prefix('admin')->middleware(['auth', 'PhanQuyenAdmin'])->group(function () {
    // Danh sách
    Route::get('/banner', [BannerController::class, 'index'])->name('admin.banner.index');
    
    // Thêm mới
    Route::post('/banner/store', [BannerController::class, 'store'])->name('admin.banner.store');
    
    // Form sửa (lấy thông tin banner theo id)
    Route::get('/banner/edit/{id}', [bannerController::class, 'edit'])->name('admin.banner.edit');
    
    // Thực hiện cập nhật (dùng method POST hoặc PUT đều được, ở đây mình dùng POST cho đơn giản với form HTML)
    Route::post('/banner/update/{id}', [bannerController::class, 'update'])->name('admin.banner.update');
    
    // Xóa
    Route::delete('/banner/{id}', [bannerController::class, 'destroy'])->name('admin.banner.destroy');
});