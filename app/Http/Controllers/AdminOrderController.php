<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    // Danh sách đơn hàng
    public function index() {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // Chi tiết đơn hàng (Hóa đơn)
    public function show($id) {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        return view('admin.orders.show', compact('order')); // View này sẽ là mẫu hóa đơn
    }

    // Cập nhật trạng thái
    public function updateStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
    
    // In hóa đơn (Optional - trả về view chỉ chứa nội dung hóa đơn để in)
    public function printInvoice($id) {
        $order = Order::with('items', 'user')->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }
}