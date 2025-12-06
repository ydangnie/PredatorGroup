<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Hóa Đơn</title>

    {{-- Import CSS gốc của Admin để Sidebar hoạt động đúng --}}
    @vite(['resources/css/admin/admindasboard.css', 'resources/js/admin/dasboard.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- CSS RIÊNG BIỆT CHO TRANG ORDER (Bắt đầu bằng .ord-) --- */

        /* Chỉnh lại main-content để khớp với sidebar cố định */
        .ord-main-content {
            margin-left: 280px;
            /* Bằng chiều rộng sidebar */
            padding: 2rem;
            width: calc(100% - 280px);
            min-height: 100vh;
            background: #121212;
            /* Màu nền tối */
        }

        /* Top Bar riêng */
        .ord-top-bar {
            background: linear-gradient(135deg, #1c1c1c 0%, #0a0a0a 100%);
            padding: 1.5rem 2rem;
            border-radius: 12px;
            border: 1px solid #333;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Stats Grid */
        .ord-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .ord-stat-card {
            background: #1e1e1e;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #333;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Dải màu trạng thái */
        .ord-stat-card.total {
            border-left: 4px solid #fff;
        }

        .ord-stat-card.pending {
            border-left: 4px solid #ffc107;
        }

        .ord-stat-card.processing {
            border-left: 4px solid #17a2b8;
        }

        .ord-stat-card.shipping {
            border-left: 4px solid #3498db;
        }

        .ord-stat-card.completed {
            border-left: 4px solid #28a745;
        }

        .ord-stat-card.cancelled {
            border-left: 4px solid #dc3545;
        }

        .ord-stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .ord-stat-label {
            color: #888;
            font-size: 0.9rem;
        }

        .ord-stat-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 2rem;
            opacity: 0.1;
            color: #fff;
        }

        /* Bảng */
        .ord-table-wrapper {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #333;
            overflow-x: auto;
        }

        .ord-table {
            width: 100%;
            border-collapse: collapse;
            color: #e0e0e0;
        }

        .ord-table th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #444;
            color: #aaa;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .ord-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            vertical-align: middle;
        }

        .ord-table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Nút và Input */
        .ord-search-input {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #444;
            background: #2a2a2a;
            color: #fff;
            width: 300px;
            outline: none;
        }

        .ord-search-input:focus {
            border-color: #D4AF37;
        }

        .ord-btn-search {
            padding: 10px 20px;
            background: #D4AF37;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: #000;
            font-weight: bold;
        }

        .ord-select-status {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #444;
            background: #2a2a2a;
            color: #fff;
            cursor: pointer;
        }

        .ord-action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #333;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            margin-left: 5px;
            transition: 0.2s;
        }

        .ord-action-btn:hover {
            background: #D4AF37;
            color: #000;
        }

        /* Responsive cho Mobile */
        @media (max-width: 768px) {
            .ord-main-content {
                margin-left: 0;
                width: 100%;
            }
        }
        .ord-main-content{
            margin-left: 100px
            ;
            
        }
    </style>
</head>

<body>
    {{-- Logic thống kê --}}
    @php
    use App\Models\Order;
    $totalOrders = Order::count();
    $countPending = Order::where('status', 'pending')->count();
    $countProcessing = Order::where('status', 'processing')->count();
    $countShipping = Order::where('status', 'shipping')->count();
    $countCompleted = Order::where('status', 'completed')->count();
    $countCancelled = Order::where('status', 'cancelled')->count();
    @endphp

    {{-- CẤU TRÚC CONTAINER CHÍNH --}}
    <div style="display: flex; min-height: 100vh;">

        {{-- 1. Sidebar (Được include vào, vị trí fixed bên trái) --}}
        @include('admin.nav')

        {{-- 2. Nội dung chính (Đẩy sang phải để không bị Sidebar che) --}}
        <div class="ord-main-content">

            {{-- Header --}}
            <div class="ord-top-bar">
                <h2 style="margin:0; font-size:1.5rem; font-weight:700; color:#fff;">Quản Lý Đơn Hàng</h2>

                {{-- User Profile (Copy từ dashboard nếu cần hoặc để trống) --}}
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="ord-stat-card total">
                        <div class="ord-stat-value">{{ $totalOrders }}</div>
                        <div class="ord-stat-label">Tổng Đơn</div>
                        <i class="fas fa-list ord-stat-icon"></i>
                    </div>
                    <div class="ord-stat-card pending">
                        <div class="ord-stat-value" style="color:#ffc107">{{ $countPending }}</div>
                        <div class="ord-stat-label">Chờ Xử Lý</div>
                        <i class="fas fa-clock ord-stat-icon"></i>
                    </div>
                    <div class="ord-stat-card cancelled">
                        <div class="ord-stat-value" style="color:#dc3545">{{ $countCancelled }}</div>
                        <div class="ord-stat-label">Đã Hủy</div>
                        <i class="fas fa-times-circle ord-stat-icon"></i>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div style="background:rgba(40,167,69,0.2); color:#28a745; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #28a745;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            {{-- Thống kê --}}
            <div class="ord-stats-grid">

                <div class="ord-stat-card processing">
                    <div class="ord-stat-value" style="color:#17a2b8">{{ $countProcessing }}</div>
                    <div class="ord-stat-label">Đang Xử Lý</div>
                    <i class="fas fa-cogs ord-stat-icon"></i>
                </div>
                <div class="ord-stat-card shipping">
                    <div class="ord-stat-value" style="color:#3498db">{{ $countShipping }}</div>
                    <div class="ord-stat-label">Đang Giao</div>
                    <i class="fas fa-truck ord-stat-icon"></i>
                </div>
                <div class="ord-stat-card completed">
                    <div class="ord-stat-value" style="color:#28a745">{{ $countCompleted }}</div>
                    <div class="ord-stat-label">Hoàn Thành</div>
                    <i class="fas fa-check-circle ord-stat-icon"></i>
                </div>

            </div>

            {{-- Bảng dữ liệu --}}
            <div class="ord-table-wrapper">
                <div class="ord-header" style="display:flex; justify-content:space-between; margin-bottom:20px;">
                    <h3 style="margin:0; color:#fff;">Danh Sách</h3>
                    <form action="{{ route('admin.orders.index') }}" method="GET" style="display:flex; gap:10px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="ord-search-input" placeholder="Tìm mã đơn, tên khách...">
                        <button type="submit" class="ord-btn-search"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <table class="ord-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách Hàng</th>
                            <th>Tổng Tiền</th>
                            <th>Thanh Toán</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Đặt</th>
                            <th style="text-align:right;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td style="color:#D4AF37; font-weight:bold;">#{{ $order->id }}</td>
                            <td>
                                <div style="font-weight:600;">{{ $order->name }}</div>
                                <div style="font-size:0.8rem; color:#888;">{{ $order->phone }}</div>
                            </td>
                            <td style="font-weight:bold;">{{ number_format($order->total_price) }}₫</td>
                            <td style="text-transform:uppercase; font-size:0.85rem; color:#aaa;">{{ $order->payment_method }}</td>
                            <td>
                                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                                    @csrf
                                    <select name="status" class="ord-select-status" onchange="this.form.submit()"
                                        style="font-weight:600; color: {{ $order->status == 'cancelled' ? '#dc3545' : ($order->status == 'completed' ? '#28a745' : ($order->status == 'shipping' ? '#3498db' : ($order->status == 'processing' ? '#17a2b8' : '#ffc107'))) }}">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                                    </select>
                                </form>
                            </td>
                            <td style="color:#888;">{{ $order->created_at->format('d/m H:i') }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="ord-action-btn" title="Xem"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.orders.print', $order->id) }}" class="ord-action-btn" title="In" target="_blank"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:30px; color:#888;">Không tìm thấy dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="display:flex; justify-content:center; margin-top:20px;">
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div> {{-- End .ord-main-content --}}
    </div>

    {{-- Script toggle menu con (Quan trọng cho Sidebar) --}}
    <script>
        function toggleSubMenu(navItem) {
            const subMenu = navItem.nextElementSibling;
            const arrow = navItem.querySelector('.nav-arrow');
            if (subMenu && subMenu.classList.contains('sub-nav-container')) {
                if (subMenu.style.display === "none" || subMenu.style.display === "") {
                    subMenu.style.display = "block";
                    navItem.classList.add('active');
                    if (arrow) arrow.style.transform = "rotate(180deg)";
                } else {
                    subMenu.style.display = "none";
                    navItem.classList.remove('active');
                    if (arrow) arrow.style.transform = "rotate(0deg)";
                }
            }
        }
    </script>
</body>

</html>