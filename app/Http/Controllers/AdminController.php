<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Products; // Lưu ý: Model của bạn tên là Products (số nhiều)
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * 1. Hiển thị Dashboard & Thống kê tổng quan
     */
    public function index()
    {
        // A. Thống kê tổng số
        // Lưu ý: Cột tổng tiền trong DB của bạn là 'total_price' hay 'total'? Hãy kiểm tra kỹ
        // Ở đây mình để 'total' cho khớp với logic bên dưới, nếu là 'total_price' hãy sửa lại.
        $totalRevenue = Order::where('status', 'completed')->sum('total_price'); 
        $totalOrders = Order::count();
        
        // Kiểm tra xem model Products có tồn tại không, nếu lỗi hãy đổi thành Product
        $totalProducts = Products::count(); 
        
        // Đếm user không phải là admin
        $totalUsers = User::where('role', '!=', 'admin')->count();

        // B. Top 5 Sản phẩm bán chạy nhất
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed') 
            ->select(
                'order_items.product_name', 
                DB::raw('SUM(order_items.quantity) as total_sold'), 
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // C. 5 Đơn hàng mới nhất
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dasboard', compact('totalRevenue', 'totalOrders', 'totalProducts', 'totalUsers', 'topProducts', 'recentOrders'));
    }

    /**
     * 2. API lấy dữ liệu Biểu đồ (AJAX gọi vào đây)
     */
    public function getChartData(Request $request)
    {
        $filter = $request->filter ?? 'week'; // Mặc định hiển thị theo tuần
        
        $data = [];
        $labels = [];

        // Query cơ bản: chỉ lấy đơn hàng đã hoàn thành
        $query = Order::where('status', 'completed');

        switch ($filter) {
            case 'week': // Sửa lại key từ 'day' thành 'week' cho khớp với nút bấm ở View
            case 'day':  // Giữ cả case day phòng hờ
                $startDate = Carbon::now()->subDays(6); // 7 ngày gần nhất
                $query->where('created_at', '>=', $startDate);
                
                // Group by theo ngày
                $raw = $query->select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('SUM(total_price) as total')
                )->groupBy('date')->orderBy('date')->get();

                // Loop 7 ngày để lấp đầy ngày không có đơn
                for ($i = 0; $i < 7; $i++) {
                    $dateObj = $startDate->copy()->addDays($i);
                    $dateStr = $dateObj->format('Y-m-d');
                    
                    $labels[] = $dateObj->format('d/m'); // Label hiển thị: 15/12
                    
                    $record = $raw->where('date', $dateStr)->first();
                    $data[] = $record ? $record->total : 0;
                }
                break;

            case 'month': // Dữ liệu từng ngày trong tháng hiện tại
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $daysInMonth = Carbon::now()->daysInMonth; 
                
                $query->whereBetween('created_at', [$startDate, $endDate]);

                $raw = $query->select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('SUM(total_price) as total')
                )->groupBy('date')->orderBy('date')->get();

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    // Tạo ngày chuẩn Y-m-d để so sánh
                    $date = Carbon::createFromDate(null, null, $i)->format('Y-m-d');
                    $labels[] = $i; // Label: 1, 2, 3...
                    
                    $record = $raw->where('date', $date)->first();
                    $data[] = $record ? $record->total : 0;
                }
                break;

            case 'year': // Dữ liệu 12 tháng
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
                
            default: // Mặc định xử lý giống 'week'
                 $startDate = Carbon::now()->subDays(6);
                $query->where('created_at', '>=', $startDate);
                
                $raw = $query->select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('SUM(total_price) as total')
                )->groupBy('date')->orderBy('date')->get();

                for ($i = 0; $i < 7; $i++) {
                    $dateObj = $startDate->copy()->addDays($i);
                    $dateStr = $dateObj->format('Y-m-d');
                    $labels[] = $dateObj->format('d/m');
                    $record = $raw->where('date', $dateStr)->first();
                    $data[] = $record ? $record->total : 0;
                }
        }

        // QUAN TRỌNG: Trả về key là 'values' để khớp với JS fetch(data.values)
        return response()->json([
            'labels' => $labels,
            'values' => $data 
        ]);
    }
}