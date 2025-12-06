<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index(Request $request) {
        // 1. Lọc theo trạng thái hoặc tìm kiếm (nếu có)
        $query = Order::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $orders = $query->paginate(10);

        // 2. Đếm số lượng cho các thẻ thống kê (Tối ưu Query)
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    // Xem chi tiết
    public function show($id) {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái
    public function updateStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        
        // Kiểm tra logic đơn giản (ví dụ: Đã hủy thì không được chuyển lại)
        if($order->status == 'cancelled' && $request->status != 'cancelled') {
             // Tùy chọn: có thể chặn hoặc cho phép tùy logic shop bạn
             // return back()->with('error', 'Đơn hàng đã hủy không thể khôi phục.');
        }

        $order->update(['status' => $request->status]);
        
        return back()->with('success', 'Cập nhật trạng thái đơn hàng #' . $id . ' thành công!');
    }
    
    // In hóa đơn
    public function printInvoice($id) {
        $order = Order::with('items', 'user')->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }
}