<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Thống kê tổng quan
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();

        // 2. Thống kê doanh thu theo thời gian
        $todayRevenue = Order::whereDate('created_at', Carbon::today())
                             ->where('status', '!=', 'cancelled')->sum('total_price');
                             
        $thisMonthRevenue = Order::whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year)
                                 ->where('status', '!=', 'cancelled')->sum('total_price');

        // 3. Top sản phẩm bán chạy (Top 5)
        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product') // Eager load product để lấy ảnh
            ->get();

        // 4. Các đơn hàng mới nhất
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dasboard', compact(
            'totalRevenue', 
            'totalOrders', 
            'totalUsers', 
            'todayRevenue', 
            'thisMonthRevenue', 
            'topProducts',
            'recentOrders'
        ));
    }
}
