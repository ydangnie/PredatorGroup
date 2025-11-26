<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // 1. Hiển thị danh sách (và form thêm/sửa)
    public function index()
    {
        $banners = Banner::all();
        $bannerEdit = null; // Mặc định không có banner nào đang sửa
        return view('admin.banner', compact('banners', 'bannerEdit'));
    }

    // 2. Hàm khi bấm nút "Sửa"
    public function edit($id)
    {
        $banners = Banner::all(); // Vẫn lấy danh sách để hiện khung bên trái
        $bannerEdit = Banner::findOrFail($id); // Lấy banner cần sửa để điền vào khung bên phải
        
        // Trả về view cũ (admin.banner) nhưng có thêm biến $bannerEdit
        return view('admin.banner', compact('banners', 'bannerEdit'));
    }

    // 3. Thêm mới (Giữ nguyên logic)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:100',
            'mota' => 'nullable|string',
            'thuonghieu' => 'nullable|string|max:45',
            'link' => 'nullable|string|max:255',
            'hinhanh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('hinhanh')) {
            $path = $request->file('hinhanh')->store('banners', 'public');
            Banner::create([
                'title' => $request->title,
                'mota' => $request->mota,
                'thuonghieu' => $request->thuonghieu,
                'link' => $request->link,
                'hinhanh' => $path,
            ]);
            return redirect()->route('admin.banner.index')->with('success', 'Thêm thành công!');
        }
        return back()->with('error', 'Lỗi ảnh!');
    }

    // 4. Cập nhật (Logic update)
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:100',
            'mota' => 'nullable|string',
            'thuonghieu' => 'nullable|string|max:45',
            'link' => 'nullable|string|max:255',
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'mota', 'thuonghieu', 'link']);

        if ($request->hasFile('hinhanh')) {
            if ($banner->hinhanh && Storage::disk('public')->exists($banner->hinhanh)) {
                Storage::disk('public')->delete($banner->hinhanh);
            }
            $data['hinhanh'] = $request->file('hinhanh')->store('banners', 'public');
        }

        $banner->update($data);
        
        // Sau khi update xong, quay về trang index (form reset về thêm mới)
        return redirect()->route('admin.banner.index')->with('success', 'Cập nhật thành công!');
    }

    // 5. Xóa (Giữ nguyên)
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->hinhanh && Storage::disk('public')->exists($banner->hinhanh)) {
            Storage::disk('public')->delete($banner->hinhanh);
        }
        $banner->delete();
        return redirect()->route('admin.banner.index')->with('success', 'Đã xóa!');
    }
}