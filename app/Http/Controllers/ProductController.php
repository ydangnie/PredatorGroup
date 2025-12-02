<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductImage; // Import model mới
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Eager load thêm 'images'
        $products = Products::with(['category', 'brand', 'variants', 'images'])->latest()->get();
        $categories = Category::all();
        $brands = Brand::all();
        $productEdit = null;

        return view('admin.product', compact('products', 'categories', 'brands', 'productEdit'));
    }

    public function edit($id)
    {
        $products = Products::with(['category', 'brand', 'variants', 'images'])->latest()->get();
        $categories = Category::all();
        $brands = Brand::all();
        // Load kèm images
        $productEdit = Products::with(['variants', 'images'])->findOrFail($id);

        return view('admin.product', compact('products', 'categories', 'brands', 'productEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tensp' => 'required',
            'gia' => 'required|numeric|min:0',
            'hinh_anh' => 'required|image',
            // Validate album ảnh (tối đa 2MB mỗi ảnh)
            'album.*' => 'nullable|image|max:2048' 
        ]);

        $data = $request->except(['variants', 'album']); // Loại bỏ album khỏi data chính

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        $product = Products::create($data);

        // Xử lý Variants (Giữ nguyên code cũ của bạn)
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

        // Xử lý Album ảnh (Mới)
        if ($request->hasFile('album')) {
            foreach ($request->file('album') as $file) {
                $path = $file->store('product_gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        
        // ... (Validate giống cũ) ...

        $data = $request->except(['variants', 'album']);

        // Cập nhật ảnh đại diện (Giữ nguyên logic cũ)
        if ($request->hasFile('hinh_anh')) {
            if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
                Storage::disk('public')->delete($product->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        $product->update($data);

        // Cập nhật Variants (Giữ nguyên logic cũ)
        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

        // Thêm ảnh mới vào Album (Cộng dồn, không xóa ảnh cũ)
        if ($request->hasFile('album')) {
            foreach ($request->file('album') as $file) {
                $path = $file->store('product_gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        
        // Xóa ảnh đại diện
        if ($product->hinh_anh) Storage::disk('public')->delete($product->hinh_anh);
        
        // Xóa tất cả ảnh trong album
        foreach($product->images as $img) {
            if(Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }
        
        $product->delete();
        return redirect()->route('admin.product.index')->with('success', 'Đã xóa sản phẩm!');
    }

    // Hàm API để xóa từng ảnh trong album (dùng cho nút X trong form sửa)
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
        return back()->with('success', 'Đã xóa ảnh khỏi album');
    }
}