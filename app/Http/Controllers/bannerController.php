<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class bannerController extends Controller
{
    // Danh sách
    public function index()
    {
        $banners = Banner::all(); // Lấy tất cả banner
        return view('admin.banner', compact('banners'));
    }

    // Thêm mới (Store)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:100',
            'mota' => 'nullable|string',
            'thuonghieu' => 'nullable|string|max:45',
            'hinhanh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Bắt buộc có ảnh
        ]);

        if ($request->hasFile('hinhanh')) {
            // Lưu ảnh vào public/banners
            $path = $request->file('hinhanh')->store('banners', 'public');

            Banner::create([
                'title' => $request->title,
                'mota' => $request->mota,
                'thuonghieu' => $request->thuonghieu,
                'hinhanh' => $path, // Lưu đường dẫn vào cột 'hinhanh'
            ]);

            return redirect()->route('admin.banner')->with('success', 'Thêm banner thành công!');
        }

        return back()->with('error', 'Lỗi upload ảnh!');
    }

    // Xóa
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Xóa file ảnh cũ nếu có
        if ($banner->hinhanh && Storage::disk('public')->exists($banner->hinhanh)) {
            Storage::disk('public')->delete($banner->hinhanh);
        }

        $banner->delete();
        return redirect()->route('admin.banner')->with('success', 'Xóa banner thành công!');
    }
}