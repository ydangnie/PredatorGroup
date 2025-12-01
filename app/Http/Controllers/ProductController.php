<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 1. Hiển thị danh sách
    public function index()
    {
        // Eager load category, brand, variants để tối ưu query
        $products = Products::with(['category', 'brand', 'variants'])->latest()->get();
        $categories = Category::all();
        $brands = Brand::all();
        $productEdit = null;

        return view('admin.product', compact('products', 'categories', 'brands', 'productEdit'));
    }

    // 2. Chuyển sang chế độ Sửa
    public function edit($id)
    {
        $products = Products::with(['category', 'brand', 'variants'])->latest()->get();
        $categories = Category::all();
        $brands = Brand::all();
        $productEdit = Products::with('variants')->findOrFail($id);

        return view('admin.product', compact('products', 'categories', 'brands', 'productEdit'));
    }

    // 3. Thêm mới Sản phẩm & Variants
    public function store(Request $request)
    {
       $request->validate([
        'tensp' => 'required|string|max:255',
        'gender' => 'required|in:male,female,unisex', // <--- Thêm validate
        'gia' => 'required|numeric|min:0',
        'hinh_anh' => 'required|image|max:2048',
        'category_id' => 'nullable|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        // Validate variants...
        'variants.*.stock' => 'required|integer|min:0',
    ]);

    // Thêm 'gender' vào mảng lấy dữ liệu
    $data = $request->only(['tensp', 'gender', 'mota', 'gia', 'so_luong', 'sku', 'category_id', 'brand_id']);
        // Xử lý ảnh chính
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        $product = Products::create($data);

        // Lưu Variants nếu có
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Chỉ lưu nếu có size hoặc color
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // 4. Cập nhật
    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        
        $request->validate([
        'tensp' => 'required|string|max:255',
        'gender' => 'required|in:male,female,unisex', // <--- Thêm validate
        'gia' => 'required|numeric|min:0',
        'hinh_anh' => 'nullable|image|max:2048',
    ]);

    // Thêm 'gender' vào mảng lấy dữ liệu
    $data = $request->only(['tensp', 'gender', 'mota', 'gia', 'so_luong', 'sku', 'category_id', 'brand_id']);

        if ($request->hasFile('hinh_anh')) {
            if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
                Storage::disk('public')->delete($product->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        $product->update($data);

        // Xử lý Variants: Đơn giản nhất là xóa cũ thêm mới, hoặc update thông minh.
        // Cách đơn giản: Xóa hết variants cũ và tạo lại theo input mới
        $product->variants()->delete();

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật thành công!');
    }

    // 5. Xóa
    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
            Storage::disk('public')->delete($product->hinh_anh);
        }
        $product->delete(); // Variants sẽ tự xóa nhờ onCascade delete ở migration
        return redirect()->route('admin.product.index')->with('success', 'Đã xóa sản phẩm!');
    }
}