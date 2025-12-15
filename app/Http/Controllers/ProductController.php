<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
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
        $productEdit = Products::with(['variants', 'images'])->findOrFail($id);
        return view('admin.product', compact('products', 'categories', 'brands', 'productEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tensp' => 'required',
            'gia' => 'required|numeric|min:0',
            'so_luong' => 'nullable|integer|min:0',
            'hinh_anh' => 'required|image',
            'album.*' => 'nullable|image|max:2048'
        ]);

        // Loại bỏ variants, album và _token khỏi mảng data để tạo Product
        $data = $request->except(['variants', 'album', '_token']);

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        // Tạo sản phẩm chính
        $product = Products::create($data);

        // Xử lý thêm biến thể (ProductVariant)
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Kiểm tra nếu có size hoặc màu thì mới lưu
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create([
                        'size' => $variant['size'],
                        'color' => $variant['color'],
                        'stock' => $variant['stock'] ?? 0, // Lấy đúng tên cột stock
                    ]);
                }
            }
        }

        // Xử lý album ảnh phụ
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
        
        $request->validate([
            'tensp' => 'required',
            'gia' => 'required|numeric|min:0',
            'so_luong' => 'nullable|integer|min:0',
            'hinh_anh' => 'nullable|image',
            'album.*' => 'nullable|image|max:2048'
        ]);

        // Loại bỏ các trường không thuộc bảng products
        $data = $request->except(['variants', 'album', '_token']);

        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có
            if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
                Storage::disk('public')->delete($product->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        // Cập nhật thông tin sản phẩm chính
        $product->update($data);

        // Xử lý biến thể: Xóa cũ -> Thêm mới
        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create([
                        'size' => $variant['size'],
                        'color' => $variant['color'],
                        'stock' => $variant['stock'] ?? 0,
                    ]);
                }
            }
        }

        // Xử lý thêm ảnh vào album (không xóa ảnh cũ, chỉ thêm mới)
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
        
        // Xóa ảnh chính
        if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
            Storage::disk('public')->delete($product->hinh_anh);
        }
        
        // Xóa album ảnh
        foreach($product->images as $img) {
            if(Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }
        
        $product->delete();
        return redirect()->route('admin.product.index')->with('success', 'Đã xóa sản phẩm!');
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
        return back()->with('success', 'Đã xóa ảnh khỏi album');
    }
    public function search(Request $request)
{
    $keyword = $request->input('keyword');

    // Tìm kiếm theo tên sản phẩm hoặc mô tả
    $products = Products::where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%")
                        ->paginate(12);

    return view('sanpham', compact('products', 'keyword'));
}
}