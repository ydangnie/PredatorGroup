<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Products; // 1. Import Model

class SanPhamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('sanpham.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('sanpham.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $sanPham)
    {
        //
        $product = $sanPham;
        return view('sanpham.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
        //
    public function sanpham(Request $request){
        // 2. Lấy danh sách sản phẩm, kèm Thương hiệu và Danh mục để hiển thị
        // paginate(9): Lấy 9 sản phẩm mỗi trang
        $products = Products::with(['brand', 'category'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(9);

        // 3. Trả về view kèm biến $products
        return view('sanpham', compact('products'));
    }
}
