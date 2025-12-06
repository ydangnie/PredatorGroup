<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // 1. Hiển thị Dashboard & Thống kê tổng quan
    public function index()
    {
        // A. Thống kê tổng số
        $totalRevenue = Order::where('status', 'completed')->sum('total_price'); // Chỉ tính đơn hoàn thành
        $totalOrders = Order::count();
        $totalProducts = Products::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();

        // B. Top 5 Sản phẩm bán chạy nhất
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed') // Chỉ tính đơn hoàn thành
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.total) as total_revenue'))
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // C. Đơn hàng mới nhất
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dasboard', compact('totalRevenue', 'totalOrders', 'totalProducts', 'totalUsers', 'topProducts', 'recentOrders'));
    }

    // 2. API lấy dữ liệu Biểu đồ (AJAX)
    public function getChartData(Request $request)
    {
        $filter = $request->filter ?? 'week'; // Mặc định theo tuần
        $data = [];
        $labels = [];

        $query = Order::where('status', 'completed');

        switch ($filter) {
            case 'day': // 7 ngày qua
                $startDate = Carbon::now()->subDays(6);
                $query->where('created_at', '>=', $startDate);
                $raw = $query->select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('SUM(total_price) as total')
                )->groupBy('date')->orderBy('date')->get();

                // Format dữ liệu
                for ($i = 0; $i < 7; $i++) {
                    $date = $startDate->copy()->addDays($i)->format('Y-m-d');
                    $labels[] = $startDate->copy()->addDays($i)->format('d/m');
                    $record = $raw->where('date', $date)->first();
                    $data[] = $record ? $record->total : 0;
                }
                break;

            case 'month': // Theo từng ngày trong tháng này
                $startDate = Carbon::now()->startOfMonth();
                $daysInMonth = Carbon::now()->daysInMonth;
                
                $query->where('created_at', '>=', $startDate);
                $raw = $query->select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('SUM(total_price) as total')
                )->groupBy('date')->orderBy('date')->get();

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = Carbon::createFromDate(null, null, $i)->format('Y-m-d');
                    $labels[] = $i;
                    $record = $raw->where('date', $date)->first();
                    $data[] = $record ? $record->total : 0;
                }
                break;

            case 'year': // 12 tháng trong năm
                $year = Carbon::now()->year;
                $query->whereYear('created_at', $year);
                $raw = $query->select(
                    DB::raw('MONTH(created_at) as month'), 
                    DB::raw('SUM(total_price) as total')
                )->groupBy('month')->orderBy('month')->get();

                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = "Thg $i";
                    $record = $raw->where('month', $i)->first();
                    $data[] = $record ? $record->total : 0;
                }
                break;
                
            default: // 'week' (Mặc định)
                // Giống 'day'
                $startDate = Carbon::now()->subDays(6);
                $query->where('created_at', '>=', $startDate);
                $raw = $query->select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('SUM(total_price) as total')
                )->groupBy('date')->orderBy('date')->get();

                foreach($raw as $item) {
                    $labels[] = Carbon::parse($item->date)->format('d/m');
                    $data[] = $item->total;
                }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}