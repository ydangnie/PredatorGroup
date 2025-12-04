<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // 1. Hiển thị danh sách
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        $voucherEdit = null;
        return view('admin.voucher', compact('vouchers', 'voucherEdit'));
    }

    // 2. Hiển thị form sửa
    public function edit($id)
    {
        $vouchers = Voucher::latest()->get();
        $voucherEdit = Voucher::findOrFail($id);
        return view('admin.voucher', compact('vouchers', 'voucherEdit'));
    }

    // 3. Xử lý Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code|max:50',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ], [
            'code.unique' => 'Mã giảm giá này đã tồn tại!',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        Voucher::create([
            'code' => strtoupper($request->code), // Tự động viết hoa mã
            'type' => $request->type,
            'value' => $request->value,
            'quantity' => $request->quantity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    // 4. Xử lý Cập nhật
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
            'status' => 'required|boolean',
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'quantity' => $request->quantity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    // 5. Xóa
    public function destroy($id)
    {
        Voucher::destroy($id);
        return redirect()->route('admin.voucher.index')->with('success', 'Đã xóa mã giảm giá!');
    }
}