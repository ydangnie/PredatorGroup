<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'last_30_days');

        [$startDate, $endDate] = $this->resolveDateRange($filter);

        // ========== KPI - TỔNG QUAN ==========
        
        // Tổng doanh thu (chỉ đơn hoàn thành)
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
                             ->where('status', 'completed')
                             ->sum('total_price');

        // Tổng đơn hàng hoàn thành
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', 'completed')
                            ->count();

        // Tổng số khách hàng (toàn bộ hệ thống)
        $totalUsers = User::count();

        // ========== BIỂU ĐỒ DOANH THU THEO NGÀY ==========
        
        $sales = Order::selectRaw('DATE(created_at) as date, COALESCE(SUM(total_price), 0) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date');

        // Tạo đầy đủ các ngày trong khoảng thời gian (kể cả ngày không có doanh thu)
        $period = new \DatePeriod(
            $startDate->copy()->startOfDay(),
            new \DateInterval('P1D'),
            $endDate->copy()->addDay()
        );

        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $data[] = (int) ($sales->get($dateKey, 0));
        }

        $salesData = [
            'labels' => $labels,
            'data' => $data
        ];

        // ========== TOP 10 SẢN PHẨM BÁN CHẠY ==========
        
        $topSellingProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // ========== TRẢ DỮ LIỆU VỀ VIEW ==========
        
        return view('admin.statistics', compact(
            'filter',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'totalUsers',
            'salesData',
            'topSellingProducts'
        ));
    }

    /**
     * Xác định khoảng thời gian dựa trên filter
     */
    private function resolveDateRange(string $filter): array
    {
        $endDate = Carbon::now()->endOfDay();

        return match ($filter) {
            'today'        => [Carbon::today()->startOfDay(), $endDate],
            'last_7_days'  => [Carbon::now()->subDays(6)->startOfDay(), $endDate],
            'last_30_days' => [Carbon::now()->subDays(29)->startOfDay(), $endDate],
            'week'         => [Carbon::now()->startOfWeek(), $endDate],
            'month'        => [Carbon::now()->startOfMonth(), $endDate],
            'year'         => [Carbon::now()->startOfYear(), $endDate],
            default        => [Carbon::now()->subDays(29)->startOfDay(), $endDate],
        };
    }
}