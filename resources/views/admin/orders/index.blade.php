<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng | Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    
    {{-- Sử dụng chung CSS của Admin (Banner/Product) để đồng bộ Layout --}}
    @vite(['resources/css/admin/banner.css'])

    <style>
        /* --- Custom CSS cho trang Orders --- */
        
        /* Header Bar */
        .ord-top-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
        }
        
        /* Stats Grid (Lưới thống kê) */
        .ord-stats-grid {
            display: grid; 
            grid-template-columns: repeat(6, 1fr); 
            gap: 15px; 
            margin-bottom: 30px;
        }
        
        .ord-stat-card {
            background: #1e1e1e; 
            border: 1px solid #333; 
            border-radius: 8px; 
            padding: 20px;
            position: relative; 
            overflow: hidden; 
            display: flex; 
            flex-direction: column; 
            justify-content: center;
            transition: transform 0.3s ease;
        }
        
        .ord-stat-card:hover { transform: translateY(-5px); }

        /* Màu sắc viền trái cho từng loại thẻ */
        .ord-stat-card.total { border-left: 4px solid #fff; }
        .ord-stat-card.pending { border-left: 4px solid #ffc107; }      /* Vàng */
        .ord-stat-card.processing { border-left: 4px solid #3b82f6; }   /* Xanh dương */
        .ord-stat-card.shipping { border-left: 4px solid #a855f7; }     /* Tím */
        .ord-stat-card.completed { border-left: 4px solid #4ade80; }    /* Xanh lá */
        .ord-stat-card.cancelled { border-left: 4px solid #ef4444; }    /* Đỏ */

        .ord-stat-value { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 5px; }
        .ord-stat-label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; }
        .ord-stat-icon { 
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%); 
            font-size: 35px; opacity: 0.1; color: #fff; 
        }

        /* Search Input */
        .ord-search-input {
            background: #1e1e1e; border: 1px solid #333; color: #fff; 
            padding: 8px 15px; border-radius: 4px; outline: none; width: 250px;
        }
        .ord-search-input:focus { border-color: #D4AF37; }
        
        .ord-btn-search {
            background: #D4AF37; color: #000; border: none; 
            padding: 8px 15px; border-radius: 4px; cursor: pointer; margin-left: 5px;
        }

        /* Select Status (Trong bảng) */
        .status-select {
            background: transparent; border: 1px solid #444; color: #ccc; 
            padding: 5px; border-radius: 4px; font-size: 12px; cursor: pointer; width: 100%;
        }
        .status-select:focus { border-color: #D4AF37; outline: none; }

        /* Responsive */
        @media (max-width: 1200px) { .ord-stats-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .ord-stats-grid { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>

<body>
    {{-- Menu bên trái --}}
    @include('admin.nav')
    
    {{-- Container chính (Margin left đã được xử lý trong banner.css) --}}
    <div class="banner-container">
        
        {{-- Thông báo thành công --}}
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        {{-- 1. Header Trang --}}
        <div class="ord-top-bar">
            <h2 style="color: #fff; margin: 0; font-size: 24px;"><i class="fas fa-chart-line"></i> Tổng Quan Đơn Hàng</h2>
        </div>

        {{-- 2. Thẻ Thống Kê (Stats Cards) --}}
        <div class="ord-stats-grid">
            <div class="ord-stat-card total">
                <div class="ord-stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="ord-stat-label">Tổng Đơn</div>
                <i class="fas fa-clipboard-list ord-stat-icon"></i>
            </div>
            <div class="ord-stat-card pending">
                <div class="ord-stat-value" style="color:#ffc107">{{ $stats['pending'] ?? 0 }}</div>
                <div class="ord-stat-label">Chờ Xử Lý</div>
                <i class="fas fa-clock ord-stat-icon"></i>
            </div>
            <div class="ord-stat-card processing">
                <div class="ord-stat-value" style="color:#3b82f6">{{ $stats['processing'] ?? 0 }}</div>
                <div class="ord-stat-label">Đang Xử Lý</div>
                <i class="fas fa-cogs ord-stat-icon"></i>
            </div>
            <div class="ord-stat-card shipping">
                <div class="ord-stat-value" style="color:#a855f7">{{ $stats['shipping'] ?? 0 }}</div>
                <div class="ord-stat-label">Đang Giao</div>
                <i class="fas fa-shipping-fast ord-stat-icon"></i>
            </div>
            <div class="ord-stat-card completed">
                <div class="ord-stat-value" style="color:#4ade80">{{ $stats['completed'] ?? 0 }}</div>
                <div class="ord-stat-label">Hoàn Thành</div>
                <i class="fas fa-check-circle ord-stat-icon"></i>
            </div>
            <div class="ord-stat-card cancelled">
                <div class="ord-stat-value" style="color:#ef4444">{{ $stats['cancelled'] ?? 0 }}</div>
                <div class="ord-stat-label">Đã Hủy</div>
                <i class="fas fa-times-circle ord-stat-icon"></i>
            </div>
        </div>

        {{-- 3. Bảng Danh Sách Đơn Hàng --}}
        <div class="card-box">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-list"></i> Danh Sách Đơn Hàng</h5>
                
                {{-- Form Tìm kiếm --}}
                <form action="{{ route('admin.orders.index') }}" method="GET" style="display:flex; align-items:center;">
                    <input type="text" name="search" value="{{ request('search') }}" class="ord-search-input" placeholder="Mã đơn, tên khách, SĐT...">
                    <button type="submit" class="ord-btn-search"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="card-body-custom p-0">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Khách Hàng</th>
                            <th width="15%">Tổng Tiền</th>
                            <th width="10%">Thanh Toán</th>
                            <th width="15%">Trạng Thái</th>
                            <th width="15%">Ngày Đặt</th>
                            <th width="10%" class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td style="color: #D4AF37; font-weight: bold;">#{{ $order->id }}</td>
                            <td>
                                <div style="font-weight: 600; color: #fff;">{{ $order->name }}</div>
                                <div style="font-size: 12px; color: #888;">{{ $order->phone }}</div>
                            </td>
                            <td style="font-weight: 700; color: #fff;">{{ number_format($order->total_price) }}₫</td>
                            <td style="text-transform: uppercase; font-size: 12px; color: #aaa;">
                                {{ $order->payment_method == 'cod' ? 'Tiền mặt' : 'Chuyển khoản' }}
                            </td>
                            <td>
                                {{-- Dropdown Cập Nhật Nhanh --}}
                                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                                    @csrf
                                    <select name="status" class="status-select" onchange="this.form.submit()"
                                            style="color: {{ $order->status == 'cancelled' ? '#ef4444' : ($order->status == 'completed' ? '#4ade80' : '#ffc107') }}">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                                    </select>
                                </form>
                            </td>
                            <td style="color: #888; font-size: 13px;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-action btn-edit" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.print', $order->id) }}" class="btn-action btn-save" title="In hóa đơn" target="_blank" style="margin-left: 5px;">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                                <i class="fas fa-box-open" style="font-size: 40px; margin-bottom: 10px; opacity:0.5"></i><br>
                                Không tìm thấy đơn hàng nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                {{-- Phân trang --}}
                <div style="padding: 20px; display: flex; justify-content: center;">
                    {{ $orders->appends(request()->all())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>