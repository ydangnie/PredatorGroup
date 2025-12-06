<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'binh_luan' => 'nullable|string|max:500',
        ]);

        // Kiểm tra xem người dùng đã đăng nhập chưa (Middleware 'auth' ở route sẽ lo, nhưng check thêm cho chắc)
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để đánh giá.');
        }

        // Tạo đánh giá mới
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'so_sao' => $request->so_sao,
            'binh_luan' => $request->binh_luan,
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}