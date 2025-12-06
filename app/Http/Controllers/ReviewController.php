<?php
// app/Http/Controllers/ReviewController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để gửi ý kiến.');
        }

        $userId = Auth::id();
        $productId = $request->product_id;

        // Kiểm tra lại trạng thái mua hàng (Backend check)
        $hasPurchased = OrderItem::where('product_id', $productId)
            ->whereHas('order', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->exists();

        // Validate dữ liệu
        if ($hasPurchased) {
            // Người mua hàng: Bắt buộc có sao
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'so_sao' => 'required|integer|min:1|max:5',
                'binh_luan' => 'nullable|string|max:500',
            ]);
        } else {
            // Người chưa mua: Không được phép có sao, bắt buộc có nội dung bình luận
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'binh_luan' => 'required|string|max:500', // Bắt buộc nhập nội dung
            ]);
        }

        // Lưu vào database
        Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'so_sao' => $hasPurchased ? $request->so_sao : null, // Nếu chưa mua thì để null
            'binh_luan' => $request->binh_luan,
        ]);

        $msg = $hasPurchased ? 'Đánh giá sản phẩm thành công!' : 'Bình luận của bạn đã được gửi!';
        return redirect()->back()->with('success', $msg);
    }
}