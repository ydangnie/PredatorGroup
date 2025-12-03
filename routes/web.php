<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthDangKy;
use App\Http\Controllers\AuthDangNhap;
use App\Http\Controllers\ChatbotController;

use App\Http\Controllers\BannerController;

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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CheckoutController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
    Route::get('/about', 'about');

    Route::get('/users', [UserConTroller::class, 'index'])->middleware('access.time');
});
Route::prefix('users')->controller(UsersController::class)->group(function () {
    Route::get('/', 'index')->name('users.index');
    Route::get('/create', 'create')->name('users.create'); // Tên đầy đủ là 'users.create'
    Route::post('/store', 'store')->name('users.store');
});

Route::prefix('posts')->controller(PostController::class)
    ->name('posts.')
    ->group(function () {
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
// Tìm dòng route chitietsanpham cũ và sửa thành:
Route::get('chi-tiet-san-pham/{id}', [ChiTietSanPhamCtr::class, 'chitietsanpham'])->name('chitietsanpham');

Route::get('giohang', [GioHangController::class, 'giohang'])->middleware('auth')->name('giohang');

// ... (giữ nguyên đoạn trên)

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Danh sách
    Route::get('/banner', [BannerController::class, 'index'])->name('admin.banner.index');

    // Thêm mới
    Route::post('/banner/store', [BannerController::class, 'store'])->name('admin.banner.store');

    // Form sửa (lấy thông tin banner theo id)
    Route::get('/banner/edit/{id}', [BannerController::class, 'edit'])->name('admin.banner.edit');

    // Thực hiện cập nhật (dùng method POST hoặc PUT đều được, ở đây mình dùng POST cho đơn giản với form HTML)
    Route::post('/banner/update/{id}', [BannerController::class, 'update'])->name('admin.banner.update');

    // Xóa
    Route::delete('/banner/{id}', [BannerController::class, 'destroy'])->name('admin.banner.destroy');
});

Route::post('/chat-ai', [ChatbotController::class, 'chat'])->name('chat.ai');
Route::get('/check-models', function () {
    $apiKey = env('GOOGLE_API_KEY');

    // Gửi yêu cầu lấy danh sách model được phép sử dụng
    $response = Http::withOptions(['verify' => false]) // Tắt SSL
        ->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

    return $response->json();
});
Route::get('/check-models', function () {
    $apiKey = env('GOOGLE_API_KEY');
    // Hỏi Google danh sách model
    $response = Http::withOptions(['verify' => false])
        ->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

    return $response->json();
});
Route::middleware('auth', 'admin')->prefix('admin')->name('admin.')->group(function () {

    // ... các route cũ ...

    // Thêm các route cho Brand
    Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');

    // Route Sửa
    Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
    // Route Cập nhật (POST)
    Route::post('/brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');

    // Route Xóa
    Route::delete('/brand/destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
});
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.') // (1) Đã có tiền tố "admin."
    ->group(function () {
        // (2) Resource tên là "product"
        Route::resource('product', ProductController::class);
        Route::resource('users', UsersController::class);
        Route::delete('/product-image/{id}', [ProductController::class, 'deleteImage'])->name('product.image.delete');
    });

    use App\Http\Controllers\ProfileController;

// Nhóm route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/ho-so', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/ho-so/update', [ProfileController::class, 'update'])->name('profile.update');
    // Route mới cho địa chỉ
    Route::post('/ho-so/address', [ProfileController::class, 'addAddress'])->name('profile.address.add');
    Route::delete('/ho-so/address/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
    Route::get('/ho-so/don-hang/{id}', [ProfileController::class, 'showOrder'])->name('profile.order.show');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... các route cũ ...

    // Quản lý Tồn kho
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/update', [App\Http\Controllers\InventoryController::class, 'update'])->name('inventory.update');
});

Route::post('/add-to-cart', [GioHangController::class, 'addToCart'])->name('cart.add');
Route::patch('/update-cart', [GioHangController::class, 'updateCart'])->name('cart.update'); // Mới
Route::delete('/remove-from-cart', [GioHangController::class, 'removeCart'])->name('cart.remove'); // Mới
Route::get('/cart-count', [GioHangController::class, 'getCartCount'])->name('cart.count');
// Thêm vào nhóm middleware auth nếu bắt buộc đăng nhập để mua, hoặc để ngoài nếu cho phép khách vãng lai
Route::middleware(['auth'])->group(function () {
    Route::get('/thanh-toan', [GioHangController::class, 'checkout'])->name('checkout');
    Route::post('/thanh-toan', [GioHangController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/thanh-toan/vnpay-return', [GioHangController::class, 'vnpayReturn'])->name('vnpay.return');
});

// author: VuNamPhi
// google auth login
Route::middleware('google.guest')->prefix('auth/google')->name('auth.')->group(function () {
    Route::get('/', [GoogleAuthController::class, 'redirect'])->name('google');
    Route::get('/callback', [GoogleAuthController::class, 'callback']);
});


