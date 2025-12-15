<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Quản Trị | Predator Group</title>

    {{-- Sử dụng CSS chung của Admin --}}
    @vite(['resources/css/admin/banner.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- Thư viện Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* --- DASHBOARD SPECIFIC CSS --- */

        /* 1. Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #1E1E1E;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #D4AF37;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 13px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            opacity: 0.1;
            color: #fff;
        }

        .stat-card.revenue .stat-value {
            color: #D4AF37;
        }

        /* Màu vàng cho tiền */

        /* 2. Chart Section */
        .chart-section {
            background: #1E1E1E;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        /* Filter Buttons */
        .chart-filter button {
            background: transparent;
            border: 1px solid #444;
            color: #aaa;
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 5px;
            transition: 0.3s;
        }

        .chart-filter button:hover,
        .chart-filter button.active {
            background: #D4AF37;
            color: #000;
            border-color: #D4AF37;
            font-weight: bold;
        }

        /* 3. Tables Grid */
        .tables-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .table-card {
            background: #1E1E1E;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 20px;
            height: 100%;
        }

        .table-title {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }

        /* Custom Table */
        .ds-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ds-table th {
            text-align: left;
            padding: 10px;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }

        .ds-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #2a2a2a;
            font-size: 14px;
            color: #ccc;
        }

        .ds-table tr:last-child td {
            border-bottom: none;
        }

        .top-prod-name {
            color: #fff;
            font-weight: 500;
        }

        .top-prod-sales {
            color: #D4AF37;
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .tables-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    @include('admin.nav')

    {{-- Dùng class .banner-container để giữ margin bên trái cho Sidebar --}}
    <div class="banner-container">

        <h2 style="color: #fff; font-size: 24px; margin-bottom: 30px;">Tổng Quan</h2>

        <div class="stats-grid">
            <div class="stat-card revenue">
                <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }}₫</div>
                <div class="stat-label">Doanh thu thực tế</div>
                <i class="fas fa-coins stat-icon"></i>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalOrders }}</div>
                <div class="stat-label">Tổng đơn hàng</div>
                <i class="fas fa-shopping-bag stat-icon"></i>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Sản phẩm đang bán</div>
                <i class="fas fa-box-open stat-icon"></i>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Khách hàng</div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </div>

        <div class="chart-section">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-area"></i> Biểu Đồ Doanh Thu</div>
                <div class="chart-filter">
                    <button onclick="loadChart('day')" class="active" id="btn-day">7 Ngày</button>
                    <button onclick="loadChart('month')" id="btn-month">Tháng này</button>
                    <button onclick="loadChart('year')" id="btn-year">Năm nay</button>
                </div>
            </div>
            <div style="height: 350px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="tables-grid">
            <div class="table-card">
                <h3 class="table-title"><i class="fas fa-crown" style="color:#D4AF37"></i> Top Sản Phẩm Bán Chạy</h3>
                <table class="ds-table">
                    <thead>
                        <tr>
                            <th>Sản Phẩm</th>
                            <th style="text-align:center;">Đã Bán</th>
                            <th style="text-align:right;">Doanh Thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $prod)
                        <tr>
                            <td><span class="top-prod-name">{{ Str::limit($prod->product_name, 30) }}</span></td>
                            <td style="text-align:center;">{{ $prod->total_sold }}</td>
                            <td style="text-align:right;" class="top-prod-sales">{{ number_format($prod->total_revenue) }}₫</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-card">
                <h3 class="table-title"><i class="fas fa-clock"></i> Đơn Hàng Mới</h3>
                <table class="ds-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Trạng Thái</th>
                            <th style="text-align:right;">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td style="color:#aaa">#{{ $order->id }}</td>
                            <td>
                                @php
                                $colors = ['pending'=>'#ffc107', 'processing'=>'#3b82f6', 'completed'=>'#4ade80', 'cancelled'=>'#ef4444'];
                                $labels = ['pending'=>'Chờ duyệt', 'processing'=>'Đang xử lý', 'completed'=>'Hoàn thành', 'cancelled'=>'Đã hủy'];
                                $color = $colors[$order->status] ?? '#fff';
                                $label = $labels[$order->status] ?? $order->status;
                                @endphp
                                <span style="color:{{$color}}; font-size:11px; font-weight:bold; text-transform:uppercase;">{{ $label }}</span>
                            </td>
                            <td style="text-align:right; color:#fff;">{{ number_format($order->total_price) }}₫</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;">Chưa có đơn hàng</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        let revenueChart; // Biến toàn cục lưu biểu đồ

        function loadChart(filter) {
            // 1. Active Button
            document.querySelectorAll('.chart-filter button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('btn-' + filter).classList.add('active');

            // 2. Fetch Data từ Laravel
            fetch(`{{ route('admin.chart.data') }}?filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    renderChart(data.labels, data.values); // Đổi data.data thành data.values
                });
        }

        function renderChart(labels, data) {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Hủy biểu đồ cũ nếu có để vẽ lại
            if (revenueChart) {
                revenueChart.destroy();
            }

            // Gradient màu vàng kim
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(212, 175, 55, 0.5)'); // #D4AF37 mờ
            gradient.addColorStop(1, 'rgba(212, 175, 55, 0.0)');

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh Thu (VNĐ)',
                        data: data,
                        borderColor: '#D4AF37',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#D4AF37',
                        fill: true,
                        tension: 0.4 // Đường cong mềm mại
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#333'
                            },
                            ticks: {
                                color: '#888'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#888'
                            }
                        }
                    }
                }
            });
        }

        // Load mặc định khi vào trang
        document.addEventListener('DOMContentLoaded', function() {
            loadChart('day');
        });
    </script>
</body>

</html>