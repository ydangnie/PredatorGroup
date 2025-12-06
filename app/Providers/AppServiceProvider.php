<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Review;
use App\Models\OrderItem;
use App\Models\Products; // Hoặc Product tùy tên model bạn dùng

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Chia sẻ dữ liệu cho Header (tìm sản phẩm đã mua nhưng chưa đánh giá)
        View::composer('layouts.navbar.header', function ($view) {
            $productsToReview = collect([]);
            
            if (Auth::check()) {
                $userId = Auth::id();

                // 1. Lấy danh sách ID sản phẩm đã mua trong các đơn hàng "completed"
                // Lưu ý: Đảm bảo status trong DB của bạn là 'completed' hoặc 'giaohangthanhcong'
                $boughtProductIds = OrderItem::whereHas('order', function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('status', 'completed'); // <--- Kiểm tra đúng trạng thái đơn hàng của bạn
                })->pluck('product_id')->unique();

                // 2. Lấy danh sách ID sản phẩm User này đã từng đánh giá
                $reviewedProductIds = Review::where('user_id', $userId)->pluck('product_id')->unique();

                // 3. Tìm các ID chưa đánh giá (Có trong mục 1 nhưng không có trong mục 2)
                $pendingReviewIds = $boughtProductIds->diff($reviewedProductIds);

                if ($pendingReviewIds->isNotEmpty()) {
                    $productsToReview = Products::whereIn('id', $pendingReviewIds)->get();
                }
            }

            $view->with('productsToReview', $productsToReview);
        });
    }
}
