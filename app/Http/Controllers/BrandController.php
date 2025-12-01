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
        $brandEdit = null; 
        return view('admin.brand', compact('brands', 'brandEdit'));
    }

    // 2. Hàm khi bấm nút "Sửa" -> Chuyển sang trang sửa hoặc load lại trang với biến edit
    // Theo style của BannerController bạn gửi thì edit dùng view index nhưng load data vào form, 
    // tuy nhiên bạn cũng có file banner_edit.blade.php riêng. 
    // Dưới đây mình làm theo cách bạn dùng cho Banner: edit riêng một trang cho rõ ràng hoặc dùng chung. 
    // Dựa vào file banner_edit.blade.php bạn gửi, mình sẽ làm trang edit riêng cho giống style đó.
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand_edit', compact('brand'));
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

        $request->validate([
            'ten_thuonghieu' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['ten_thuonghieu']);

        if ($request->hasFile('logo')) {
            // Xóa ảnh cũ nếu có
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);
        
        return redirect()->route('admin.brand.index')->with('success', 'Cập nhật thương hiệu thành công!');
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