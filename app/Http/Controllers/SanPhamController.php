<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Category;
use App\Models\Brand;

class SanPhamController extends Controller
{
    public function sanpham(Request $request){
        // 1. Khởi tạo query
        $query = Products::with(['brand', 'category']);

        // 2. Tìm kiếm theo từ khóa (navbar search)
        if ($request->has('keyword') && $request->keyword != null) {
            $keyword = $request->keyword;
            $query->where('tensp', 'LIKE', "%{$keyword}%");
        }

        // 3. Lọc theo Danh mục (Category)
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        // 4. Lọc theo Thương hiệu (Brand)
        if ($request->has('brand') && $request->brand != 'all') {
            $query->where('brand_id', $request->brand);
        }

        // 5. Lọc theo Giới tính (Gender - có sẵn trong DB)
        if ($request->has('gender') && $request->gender != 'all') {
            $query->where('gender', $request->gender);
        }

        // 6. Xử lý Sắp xếp
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-asc':
                    $query->orderBy('gia', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('gia', 'desc');
                    break;
                case 'name-asc':
                    $query->orderBy('tensp', 'asc');
                    break;
                case 'newest': // Mới nhất
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Mặc định sắp xếp mới nhất
            $query->orderBy('created_at', 'desc');
        }

        // 7. Phân trang (giữ lại các tham số filter khi chuyển trang)
        $products = $query->paginate(9)->withQueryString();

        // 8. Lấy danh sách Danh mục và Thương hiệu để đổ dữ liệu vào Sidebar lọc
        $categories = Category::all(); // Dựa vào bảng categories trong SQL
        $brands = Brand::all();        // Dựa vào bảng brands trong SQL

        // 9. Trả về view
        return view('sanpham', compact('products', 'categories', 'brands'));
    }
}