<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ChiTietSanPhamCtr extends Controller
{
    public function chitietsanpham($id) {
        // 1. Lấy sản phẩm theo ID, kèm theo các quan hệ
        $product = Products::with(['category', 'brand', 'images', 'variants'])->findOrFail($id);

        // 2. Lấy sản phẩm liên quan (Cùng danh mục, trừ sản phẩm hiện tại)
        $relatedProducts = Products::where('category_id', $product->category_id)
                                   ->where('id', '!=', $id)
                                   ->take(4) // Lấy 4 sản phẩm
                                   ->get();

        // 3. Xử lý logic hiển thị biến thể (Lấy danh sách màu và size duy nhất)
        $colors = $product->variants->pluck('color')->unique()->filter();
        $sizes = $product->variants->pluck('size')->unique()->filter();

        return view('chitietsanpham', compact('product', 'relatedProducts', 'colors', 'sizes'));
    }
}