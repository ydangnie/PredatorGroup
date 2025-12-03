<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // 1. Hiển thị bảng tồn kho
    public function index()
    {
        // Lấy sản phẩm kèm biến thể
        $products = Products::with('variants')->latest()->get();
        return view('admin.inventory', compact('products'));
    }

    // 2. Cập nhật nhanh số lượng (Sync)
    public function update(Request $request)
    {
        $data = $request->all();

        // A. Cập nhật số lượng cho Biến thể (nếu có)
        if (isset($data['variants'])) {
            foreach ($data['variants'] as $variantId => $stock) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $variant->update(['stock' => $stock]);
                }
            }
        }

        // B. Cập nhật số lượng cho Sản phẩm đơn (không có biến thể)
        if (isset($data['products'])) {
            foreach ($data['products'] as $productId => $stock) {
                $product = Products::find($productId);
                if ($product && $product->variants->count() == 0) {
                    $product->update(['so_luong' => $stock]);
                }
            }
        }

        // C. CHỨC NĂNG SYNC: Tự động tính tổng tồn kho cho sản phẩm có biến thể
        $productsWithVariants = Products::has('variants')->get();
        foreach ($productsWithVariants as $prod) {
            $totalStock = $prod->variants->sum('stock');
            $prod->update(['so_luong' => $totalStock]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật và đồng bộ kho hàng thành công!');
    }
}