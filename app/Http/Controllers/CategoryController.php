<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import thư viện xử lý chuỗi

class CategoryController extends Controller
{
    // 1. Hiển thị danh sách & Form Modal
    public function index()
    {
        $categories = Category::all();
        $categoryEdit = null;
        return view('admin.category', compact('categories', 'categoryEdit'));
    }

    // 2. Chuyển sang chế độ sửa
    public function edit($id)
    {
        $categories = Category::all();
        $categoryEdit = Category::findOrFail($id);
        return view('admin.category', compact('categories', 'categoryEdit'));
    }

    // 3. Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'ten_danhmuc' => 'required|string|max:255',
            'mota' => 'nullable|string',
        ]);

        Category::create([
            'ten_danhmuc' => $request->ten_danhmuc,
            // Tự động tạo slug không dấu (VD: Áo Thun -> ao-thun)
            'slug' => Str::slug($request->ten_danhmuc),
            'mota' => $request->mota,
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Thêm danh mục thành công!');
    }

    // 4. Cập nhật
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'ten_danhmuc' => 'required|string|max:255',
            'mota' => 'nullable|string',
        ]);

        $category->update([
            'ten_danhmuc' => $request->ten_danhmuc,
            'slug' => Str::slug($request->ten_danhmuc), // Cập nhật lại slug theo tên mới
            'mota' => $request->mota,
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Cập nhật thành công!');
    }

    // 5. Xóa
    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('admin.category.index')->with('success', 'Đã xóa danh mục!');
    }
}