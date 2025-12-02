<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products; // 1. Import Model

class SanPhamController extends Controller
{
    public function sanpham(Request $request){
        // 2. Lấy danh sách sản phẩm, kèm Thương hiệu và Danh mục để hiển thị
        // paginate(9): Lấy 9 sản phẩm mỗi trang
        $products = Products::with(['brand', 'category'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(9);

        // 3. Trả về view kèm biến $products
        return view('sanpham', compact('products'));
    }
}