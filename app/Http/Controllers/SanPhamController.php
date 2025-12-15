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

        // 2. Tìm kiếm theo từ khóa (navbar search / sidebar search)
        if ($request->has('keyword') && $request->keyword != null) {
            $keyword = $request->keyword;

            // Định nghĩa ánh xạ từ tiếng Việt sang giá trị DB (male, female, unisex)
            // Dùng strtolower để tìm kiếm không phân biệt chữ hoa/thường.
            $loweredKeyword = strtolower($keyword);
            $genderMap = [
                'nam' => 'male',
                'nữ' => 'female',
                'nu' => 'female', // Thêm trường hợp gõ không dấu
                'unisex' => 'unisex',
            ];

            $genderToSearch = null;
            
            // Kiểm tra xem từ khóa có chứa từ giới tính cụ thể nào không
            foreach ($genderMap as $viTerm => $dbValue) {
                // Dùng str_contains để kiểm tra từ khóa (vd: "đồng hồ nam" chứa "nam")
                if (str_contains($loweredKeyword, $viTerm)) {
                    $genderToSearch = $dbValue;
                    break;
                }
            }

            // BẮT ĐẦU PHẦN ĐÃ CẬP NHẬT
            $query->where(function ($q) use ($keyword, $genderToSearch) {
                // 1. Tìm kiếm theo Tên sản phẩm (tensp) - Vẫn tìm kiếm cả cụm từ
                $q->where('tensp', 'LIKE', "%{$keyword}%")
                  
                  // 2. HOẶC tìm kiếm theo Tên danh mục (ten_danhmuc)
                  ->orWhereHas('category', function ($q2) use ($keyword) {
                      $q2->where('ten_danhmuc', 'LIKE', "%{$keyword}%");
                  });

                // 3. HOẶC tìm kiếm theo Giới tính (gender) - Chỉ tìm kiếm khi xác định được giới tính
                if ($genderToSearch) {
                    // Nếu tìm thấy từ giới tính ("nam", "nữ"), áp dụng tìm kiếm chính xác
                    // (gender = 'male' hoặc 'female' hoặc 'unisex')
                    $q->orWhere('gender', $genderToSearch);
                }
                // Lưu ý: Đã loại bỏ orWhere('gender', 'LIKE', "%{$keyword}%") không hiệu quả
            });
            // KẾT THÚC PHẦN ĐÃ CẬP NHẬT
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