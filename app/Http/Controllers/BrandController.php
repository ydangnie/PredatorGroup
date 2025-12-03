<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    // 1. Hiển thị danh sách (và form thêm mới - Modal)
    public function index()
    {
        $brands = Brand::all();
        $brandEdit = null; // Không có dữ liệu sửa
        return view('admin.brand', compact('brands', 'brandEdit'));
    }

    // 2. Chuyển sang chế độ sửa trên cùng trang index (Modal)
    public function edit($id)
    {
        $brands = Brand::all(); // Vẫn lấy danh sách để hiển thị nền
        $brandEdit = Brand::findOrFail($id); // Lấy item cần sửa
        return view('admin.brand', compact('brands', 'brandEdit'));
    }

    // 3. Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'ten_thuonghieu' => 'required|string|max:100',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            Brand::create([
                'ten_thuonghieu' => $request->ten_thuonghieu,
                'logo' => $path,
            ]);
            return redirect()->route('admin.brand.index')->with('success', 'Thêm thương hiệu thành công!');
        }
        return back()->with('error', 'Lỗi tải ảnh logo!');
    }

    // 4. Cập nhật
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        // Validate...
        $request->validate([
            'ten_thuonghieu' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['ten_thuonghieu']);

        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        // Quay về trang index (xóa query edit)
        return redirect()->route('admin.brand.index')->with('success', 'Cập nhật thành công!');
    }

    // 5. Xóa
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }
        $brand->delete();
        return redirect()->route('admin.brand.index')->with('success', 'Đã xóa thương hiệu!');
    }
}