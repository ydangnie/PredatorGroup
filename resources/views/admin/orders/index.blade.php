@extends('admin.dasboard') {{-- Hoặc extends layout chính của bạn nếu dasboard không phải layout --}}

@section('content')
{{-- Lưu ý: Nếu dashboard.blade.php của bạn không có @yield('content'), bạn cần chỉnh lại dashboard để nhúng nội dung này vào --}}
{{-- Tạm thời mình giả định bạn sẽ chèn code này vào phần .main-content --}}

<div class="table-container" style="margin-top: 20px; padding: 20px; background: #1a1a1a; border-radius: 10px;">
    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 class="chart-title" style="color: #fff; margin: 0;">Quản Lý Hóa Đơn</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background: #28a745; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="admin-table" style="width: 100%; border-collapse: collapse; color: #e0e0e0;">
        <thead>
            <tr style="border-bottom: 1px solid #333; text-align: left;">
                <th style="padding: 12px;">Mã Đơn</th>
                <th style="padding: 12px;">Khách Hàng</th>
                <th style="padding: 12px;">SĐT</th>
                <th style="padding: 12px;">Tổng Tiền</th>
                <th style="padding: 12px;">Trạng Thái</th>
                <th style="padding: 12px;">Ngày Đặt</th>
                <th style="padding: 12px;">Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr style="border-bottom: 1px solid #2a2a2a;">
                <td style="padding: 12px;">#{{ $order->id }}</td>
                <td style="padding: 12px;">{{ $order->name }}</td>
                <td style="padding: 12px;">{{ $order->phone }}</td>
                <td style="padding: 12px; font-weight: bold; color: #D4AF37;">
                    {{ number_format($order->total_price) }}₫
                </td>
                <td style="padding: 12px;">
                    @if($order->status == 'pending')
                        <span class="status-badge pending" style="background: #ffc107; color: #000; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Chờ xử lý</span>
                    @elseif($order->status == 'processing')
                        <span class="status-badge processing" style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Đang xử lý</span>
                    @elseif($order->status == 'completed')
                        <span class="status-badge completed" style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Hoàn thành</span>
                    @else
                        <span class="status-badge cancelled" style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Đã hủy</span>
                    @endif
                </td>
                <td style="padding: 12px;">{{ $order->created_at->format('d/m/Y') }}</td>
                <td style="padding: 12px;">
                    <a href="{{ route('admin.orders.show', $order->id) }}" style="color: #3498db; margin-right: 10px; text-decoration: none;">
                        <i class="fas fa-eye"></i> Xem
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div style="margin-top: 20px;">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection