<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Hiển thị danh sách yêu thích
    public function index()
    {
        $user_id = Auth::id();
        $wishlists = Wishlist::where('user_id', $user_id)->with('product')->get();
        return view('wishlist', compact('wishlists'));
    }

    // Thêm hoặc Xóa sản phẩm khỏi yêu thích (Toggle)
    public function toggle($product_id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'login_required', 'message' => 'Vui lòng đăng nhập!']);
        }

        $user_id = Auth::id();
        $exists = Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed', 'message' => 'Đã xóa khỏi yêu thích']);
        } else {
            Wishlist::create([
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
            return response()->json(['status' => 'added', 'message' => 'Đã thêm vào yêu thích']);
        }
    }
    
    // API để lấy số lượng wishlist hiển thị lên Header (nếu cần dùng AJAX)
    public function count() {
        if (!Auth::check()) return response()->json(['count' => 0]);
        return response()->json(['count' => Wishlist::where('user_id', Auth::id())->count()]);
    }
}