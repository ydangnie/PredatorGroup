<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Bổ sung import Http
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
use App\Http\Controllers\AdminOrderController; // Quan trọng
use App\Http\Controllers\AdminController;      // Quan trọng
use App\Http\Controllers\VoucherController;    // Quan trọng
use App\Http\Controllers\CategoryController;   // Quan trọng

/*
|--------------------------------------------------------------------------
| Web Routes (USER & GUEST)
|--------------------------------------------------------------------------
*/

// Trang chủ & User thường
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/about', 'about');
    Route::get('/users', [UserConTroller::class, 'index'])->middleware('access.time');
});

// Auth (Đăng nhập/Đăng ký)
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

// Giỏ hàng & Thanh toán
Route::get('giohang', [GioHangController::class, 'giohang'])->name('giohang');
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

// Profile User
Route::middleware(['auth'])->prefix('ho-so')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
    Route::post('/address', [ProfileController::class, 'addAddress'])->name('address.add');
    Route::delete('/address/{id}', [ProfileController::class, 'deleteAddress'])->name('address.delete');
    Route::get('/don-hang/{id}', [ProfileController::class, 'showOrder'])->name('order.show');
});

// Blog Posts
Route::prefix('posts')->controller(PostController::class)->name('posts.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{id}', 'edit')->name('edit');
    route::put('/{id}', 'update')->name('update');
    Route::get('/{id}/delete', 'delete')->name('delete');
});

// Chat AI
Route::post('/chat-ai', [ChatbotController::class, 'chat'])->name('chat.ai');
Route::get('/check-models', function () {
    $apiKey = env('GOOGLE_API_KEY');
    $response = Http::withOptions(['verify' => false])
        ->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    return $response->json();
});

/*
|--------------------------------------------------------------------------
| Admin Routes (QUẢN TRỊ VIÊN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dasboard', [AdminController::class, 'index'])->name('dasboard'); // Lưu ý: Bạn đang viết sai chính tả 'dashboard' thành 'dasboard'

    // 1. Banner
    Route::get('/banner', [BannerController::class, 'index'])->name('banner.index'); // Sửa tên route cho khớp dashboard: admin.banner.index
    Route::get('/admin/banner', [BannerController::class, 'index'])->name('admin.banner'); // Giữ lại route cũ nếu cần
    Route::post('/banner/store', [BannerController::class, 'store'])->name('banner.store');
    Route::get('/banner/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('/banner/update/{id}', [BannerController::class, 'update'])->name('banner.update');
    Route::delete('/banner/{id}', [BannerController::class, 'destroy'])->name('banner.destroy');

    // 2. Brand
    Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
    Route::post('/brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brand/destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');

    // 3. Resources (Product, Users, Voucher, Category)
    Route::resource('product', ProductController::class);
    Route::delete('/product-image/{id}', [ProductController::class, 'deleteImage'])->name('product.image.delete');
    
    Route::resource('users', UsersController::class);
    Route::resource('voucher', VoucherController::class);
    Route::resource('category', CategoryController::class);

    // 4. Inventory (Kho hàng)
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/update', [InventoryController::class, 'update'])->name('inventory.update');

    // 5. QUẢN LÝ HÓA ĐƠN (ORDERS) - ĐÃ SỬA CHUẨN
    Route::controller(AdminOrderController::class)->group(function () {
        // Route này sẽ có tên: admin.orders.index
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        
        // Route này sẽ có tên: admin.orders.show
        Route::get('/orders/{id}', 'show')->name('orders.show');
        
        // Route này sẽ có tên: admin.orders.update_status
        Route::post('/orders/{id}/status', 'updateStatus')->name('orders.update_status');
        
        // Route này sẽ có tên: admin.orders.print
        Route::get('/orders/{id}/print', 'printInvoice')->name('orders.print');
        
    });
  Route::get('/chart-data', [AdminController::class, 'getChartData'])->name('chart.data');

});