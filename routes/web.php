<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserConTroller;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthDangKy;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\LienHeController;
use App\Http\Controllers\ChiTietSanPhamCtr;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminOrderController; // Đảm bảo dòng này có

/*
|--------------------------------------------------------------------------
| Web Routes (Dành cho Khách & Người dùng thường)
|--------------------------------------------------------------------------
*/

// Trang chủ & Thông tin
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/about', 'about');
    Route::get('/users', [UserConTroller::class, 'index'])->middleware('access.time');
});

// Xác thực (Đăng ký/Đăng nhập)
Route::get('dangky', [AuthDangKy::class, 'dangky']);
Route::post('dangky', [AuthDangKy::class, 'postdangky'])->name('postdangky');
Route::get('dangnhap', [AuthDangKy::class, 'dangnhap'])->name('login');
Route::post('dangnhap', [AuthDangKy::class, 'postdangnhap'])->name('postdangnhap');
Route::get('dangxuat', [AuthDangKy::class, 'dangxuat'])->name('dangxuat');

// Google Login
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Sản phẩm & Hiển thị
Route::get('sanpham', [SanPhamController::class, 'sanpham'])->name('sanpham');
Route::get('lienhe', [LienHeController::class, 'lienhe'])->name('lienhe');
Route::get('chi-tiet-san-pham/{id}', [ChiTietSanPhamCtr::class, 'chitietsanpham'])->name('chitietsanpham');
Route::get('giohang', [GioHangController::class, 'giohang'])->name('giohang');

// Posts (Tin tức)
Route::prefix('posts')->controller(PostController::class)->name('posts.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{id}', 'edit')->name('edit');
    route::put('/{id}', 'update')->name('update');
    Route::get('/{id}/delete', 'delete')->name('delete');
});

// Giỏ hàng & Thanh toán
Route::post('/add-to-cart', [GioHangController::class, 'addToCart'])->name('cart.add');
Route::patch('/update-cart', [GioHangController::class, 'updateCart'])->name('cart.update');
Route::delete('/remove-from-cart', [GioHangController::class, 'removeCart'])->name('cart.remove');
Route::get('/cart-count', [GioHangController::class, 'getCartCount'])->name('cart.count');
Route::post('/apply-coupon', [GioHangController::class, 'applyCoupon'])->name('cart.coupon');

Route::middleware(['auth'])->group(function () {
    Route::get('/thanh-toan', [GioHangController::class, 'checkout'])->name('checkout');
    Route::post('/thanh-toan', [GioHangController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/thanh-toan/vnpay-return', [GioHangController::class, 'vnpayReturn'])->name('vnpay.return');
});

// Hồ sơ người dùng
Route::middleware(['auth'])->prefix('ho-so')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
    Route::post('/address', [ProfileController::class, 'addAddress'])->name('address.add');
    Route::delete('/address/{id}', [ProfileController::class, 'deleteAddress'])->name('address.delete');
    Route::get('/don-hang/{id}', [ProfileController::class, 'showOrder'])->name('order.show');
});

// Chat AI
Route::post('/chat-ai', [ChatbotController::class, 'chat'])->name('chat.ai');


/*
|--------------------------------------------------------------------------
| Admin Routes (Dành cho Quản trị viên)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Banner
    Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');
    Route::post('/banner/store', [BannerController::class, 'store'])->name('banner.store');
    Route::get('/banner/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('/banner/update/{id}', [BannerController::class, 'update'])->name('banner.update');
    Route::delete('/banner/{id}', [BannerController::class, 'destroy'])->name('banner.destroy');

    // 2. Brand (Thương hiệu)
    Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
    Route::post('/brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brand/destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');

    // 3. Tài nguyên (Product, Users)
    Route::resource('product', ProductController::class);
    Route::delete('/product-image/{id}', [ProductController::class, 'deleteImage'])->name('product.image.delete');
    Route::resource('users', UsersController::class);

    // 4. Kho hàng (Inventory)
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/update', [InventoryController::class, 'update'])->name('inventory.update');

    // 5. QUẢN LÝ HÓA ĐƠN (Phần bạn đang bị lỗi)
    Route::controller(AdminOrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders.index'); // -> admin.orders.index
        Route::get('/orders/{id}', 'show')->name('orders.show');
        Route::post('/orders/{id}/status', 'updateStatus')->name('orders.update_status');
        Route::get('/orders/{id}/print', 'printInvoice')->name('orders.print');
    });

});