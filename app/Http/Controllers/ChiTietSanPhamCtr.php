<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ChiTietSanPhamCtr extends Controller
{
    public function chitietsanpham($id)
    {
        // 1. Lấy sản phẩm kèm reviews và user của review đó
        $product = Products::with(['category', 'brand', 'images', 'variants', 'reviews.user'])
            ->findOrFail($id);

        // Tính toán số sao trung bình (Optional)
        $averageRating = $product->reviews->avg('so_sao') ?? 0;
        $reviewCount = $product->reviews->count();

        // 2. Lấy sản phẩm liên quan
        $relatedProducts = Products::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        $colors = $product->variants->pluck('color')->unique()->filter();
        $sizes = $product->variants->pluck('size')->unique()->filter();

        // Truyền thêm biến reviews, averageRating, reviewCount sang View
        return view('chitietsanpham', compact('product', 'relatedProducts', 'colors', 'sizes', 'averageRating', 'reviewCount'));
    }
}
