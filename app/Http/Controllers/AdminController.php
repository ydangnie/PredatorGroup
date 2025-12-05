<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // ========== KPI CARDS ==========
        
        // Doanh thu tháng này
        $revenueThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->where('status', 'completed')
                                  ->sum('total_price');
        
        // Tổng đơn hàng
        $totalOrders = Order::where('status', 'completed')->count();
        
        // Tổng sản phẩm
        $totalProducts = Products::count();
        
        // Tổng khách hàng
        $totalCustomers = User::where('role', '!=', 'admin')->count();
        
        // ========== BIỂU ĐỒ DOANH THU 7 NGÀY ==========
        
        $last7Days = Order::selectRaw('DATE(created_at) as date, COALESCE(SUM(total_price), 0) as revenue')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date');
        
        // Tạo đầy đủ 7 ngày
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d/m');
            $chartData[] = (int) $last7Days->get($dateKey, 0);
        }
        
        // ========== ĐÔN HÀNG GẦN ĐÂY (5 đơn mới nhất) ==========
        
        $recentOrders = Order::with(['items.product', 'user'])
                             ->orderBy('created_at', 'desc')
                             ->limit(5)
                             ->get();
        
        return view('admin.dasboard', compact(
            'revenueThisMonth',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'chartLabels',
            'chartData',
            'recentOrders'
        ));
    }
}