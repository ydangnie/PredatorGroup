<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // 1. Hiển thị danh sách & Form Modal
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        $voucherEdit = null;
        return view('admin.voucher', compact('vouchers', 'voucherEdit'));
    }

    // 2. Chuyển sang chế độ sửa (trên cùng trang index)
    public function edit($id)
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        $voucherEdit = Voucher::findOrFail($id);
        return view('admin.voucher', compact('vouchers', 'voucherEdit'));
    }

    // 3. Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code|max:50',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'code.unique' => 'Mã giảm giá này đã tồn tại!',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        Voucher::create($request->all());

        return redirect()->route('admin.voucher.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    // 4. Cập nhật
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $voucher->update($request->all());

        return redirect()->route('admin.voucher.index')->with('success', 'Cập nhật thành công!');
    }

    // 5. Xóa
    public function destroy($id)
    {
        Voucher::destroy($id);
        return redirect()->route('admin.voucher.index')->with('success', 'Đã xóa mã giảm giá!');
    }
}