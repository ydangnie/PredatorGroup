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
    // 1. Hiển thị danh sách sản phẩm
    public function index()
    {
        // Lấy tất cả sản phẩm kèm theo quan hệ (Eager Loading) để tối ưu query
        // Sử dụng get() để lấy hết (vì giao diện admin hiện tại bạn đã bỏ phân trang)
        $products = Products::with(['category', 'brand', 'variants', 'images'])->latest()->get();
        
        $categories = Category::all();
        $brands = Brand::all();
        $productEdit = null; // Biến này null nghĩa là đang ở chế độ Thêm mới

        return view('admin.product', compact('products', 'categories', 'brands', 'productEdit'));
    }

    // 2. Hiển thị form sửa (load lại trang index kèm biến $productEdit)
    public function edit($id)
    {
        $products = Products::with(['category', 'brand', 'variants', 'images'])->latest()->get();
        $categories = Category::all();
        $brands = Brand::all();
        
        // Tìm sản phẩm cần sửa và load kèm biến thể + ảnh album
        $productEdit = Products::with(['variants', 'images'])->findOrFail($id);

        return view('admin.product', compact('products', 'categories', 'brands', 'productEdit'));
    }

    // 3. Xử lý Thêm mới
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'tensp' => 'required|string|max:255',
            'gia' => 'required|numeric|min:0',
            'hinh_anh' => 'required|image|max:2048', // Bắt buộc khi thêm mới
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:50',
            'album.*' => 'nullable|image|max:2048' // Validate từng ảnh trong album
        ]);

        // Loại bỏ các trường không có trong bảng products
        $data = $request->except(['variants', 'album']);

        // Upload ảnh đại diện
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        // Tạo sản phẩm
        $product = Products::create($data);

        // Xử lý Biến thể (Variants)
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Chỉ lưu nếu có nhập Size hoặc Màu
                if (!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

        // Xử lý Album ảnh phụ
        if ($request->hasFile('album')) {
            foreach ($request->file('album') as $file) {
                $path = $file->store('product_gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // 4. Xử lý Cập nhật
    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        
        // Validate dữ liệu
        $request->validate([
            'tensp' => 'required|string|max:255',
            'gia' => 'required|numeric|min:0',
            'hinh_anh' => 'nullable|image|max:2048', // QUAN TRỌNG: nullable để không bắt buộc chọn lại ảnh
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:50',
            'album.*' => 'nullable|image|max:2048'
        ]);

        $data = $request->except(['variants', 'album']);

        // Xử lý ảnh đại diện (Chỉ cập nhật nếu người dùng chọn ảnh mới)
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có
            if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
                Storage::disk('public')->delete($product->hinh_anh);
            }
            // Lưu ảnh mới
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        // Cập nhật thông tin chính
        $product->update($data);

        // Cập nhật Biến thể: Xóa hết cũ -> Tạo lại mới (Cách đơn giản nhất)
        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if (!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

        // Thêm ảnh vào Album (Cộng dồn vào danh sách cũ)
        if ($request->hasFile('album')) {
            foreach ($request->file('album') as $file) {
                $path = $file->store('product_gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật thành công!');
    }

    // 5. Xóa sản phẩm
    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        
        // Xóa ảnh đại diện khỏi thư mục
        if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
            Storage::disk('public')->delete($product->hinh_anh);
        }
        
        // Xóa tất cả ảnh trong album khỏi thư mục
        foreach($product->images as $img) {
            if(Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }
        
        // Xóa record trong DB (Variants và Images sẽ tự xóa nhờ cascade trong migration)
        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Đã xóa sản phẩm!');
    }

    // 6. Xóa từng ảnh trong Album (API gọi từ nút 'x' trong form sửa)
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Xóa file ảnh vật lý
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Xóa record
        $image->delete();

        return back()->with('success', 'Đã xóa ảnh khỏi album');
    }
}