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
            'so_luong' => 'nullable|integer|min:0', // <--- Đã thêm
            'hinh_anh' => 'required|image',
            'album.*' => 'nullable|image|max:2048'
        ]);

        $data = $request->except(['variants', 'album']);

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        $product = Products::create($data);

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

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
            'so_luong' => 'nullable|integer|min:0', // <--- Đã thêm
            'hinh_anh' => 'nullable|image',
            'album.*' => 'nullable|image|max:2048'
        ]);

        $data = $request->except(['variants', 'album']);

        if ($request->hasFile('hinh_anh')) {
            if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
                Storage::disk('public')->delete($product->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('products', 'public');
        }

        $product->update($data);

        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if(!empty($variant['size']) || !empty($variant['color'])) {
                    $product->variants()->create($variant);
                }
            }
        }

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
        if ($product->hinh_anh && Storage::disk('public')->exists($product->hinh_anh)) {
            Storage::disk('public')->delete($product->hinh_anh);
        }
        foreach($product->images as $img) {
            if(Storage::disk('public')->exists($img->image_path)) Storage::disk('public')->delete($img->image_path);
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
}