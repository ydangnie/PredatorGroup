<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\OrderItem; // Nhớ import Model OrderItem
use Illuminate\Support\Facades\Auth;

class ChiTietSanPhamCtr extends Controller
{
    public function chitietsanpham($id) {
        // 1. Lấy sản phẩm kèm đánh giá
        $product = Products::with(['category', 'brand', 'images', 'variants', 'reviews.user'])
                           ->findOrFail($id);

        // Tính toán sao trung bình (chỉ tính những review có sao)
        $averageRating = $product->reviews->whereNotNull('so_sao')->avg('so_sao') ?? 0;
        $reviewCount = $product->reviews->whereNotNull('so_sao')->count();

        // 2. Kiểm tra User đã mua hàng chưa
        $hasPurchased = false;
        if (Auth::check()) {
            $hasPurchased = OrderItem::where('product_id', $id)
                ->whereHas('order', function($q) {
                    $q->where('user_id', Auth::id())
                      ->where('status', 'completed'); // Chỉ tính đơn đã hoàn thành
                })->exists();
        }

        // 3. Lấy sản phẩm liên quan & biến thể
        $relatedProducts = Products::where('category_id', $product->category_id)
                                   ->where('id', '!=', $id)->take(4)->get();
        $colors = $product->variants->pluck('color')->unique()->filter();
        $sizes = $product->variants->pluck('size')->unique()->filter();

        return view('chitietsanpham', compact('product', 'relatedProducts', 'colors', 'sizes', 'averageRating', 'reviewCount', 'hasPurchased'));
    }
}
