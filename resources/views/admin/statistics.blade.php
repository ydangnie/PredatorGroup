<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Admin - {{ ucfirst($filter ?? 'Tổng Quan') }}</title>
    @vite(['resources/css/admin/admindasboard.css', 'resources/js/admin/dasboard.js'])
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.3.1"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .filter-button {
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #2a2a2a;
            color: #ffffff;
        }
        .filter-button:hover {
            background: #3a3a3a;
        }
        .filter-button.active {
            background: #3b82f6;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .date-range-info {
            font-size: 0.875rem;
            color: #9a9a9a;
            margin-top: 0.5rem;
        }
        .top-products-table {
            width: 100%;
            border-collapse: collapse;
        }
        .top-products-table thead {
            background: #2a2a2a;
        }
        .top-products-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #9a9a9a;
            text-transform: uppercase;
        }
        .top-products-table td {
            padding: 1rem;
            border-bottom: 1px solid #2a2a2a;
            color: #ffffff;
        }
        .top-products-table tr:hover {
            background: #2a2a2a;
        }
        .product-name {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .quantity-badge {
            background: #3b82f6;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- SIDEBAR - GIỐNG DASHBOARD -->
        <div class="sidebar" id="sidebar">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-clock"></i>
                    <a href="{{ route('admin.dasboard') }}">PREDATOR</a>
                    <span></span>
                </div>
            </div>

            <nav>
                <a href="{{ route('admin.dasboard') }}" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Trang Chủ</span>
                </a>
                
                <div class="nav-item">
                    <i class="fas fa-shopping-bag"></i>
                    <span onclick="toggleBanner()">Giỏ hàng</span>
                    <div id="banner-content" style="display: none;"></div>
                </div>

                <div class="nav-item" onclick="toggleSubMenu(this)">
                    <i class="fas fa-clock"></i>
                    <span>Quản lý</span>
                    <i class="fas fa-chevron-down nav-arrow"></i>
                </div>
                <div class="sub-nav-container" style="display: none;">
                    <a href="{{ route('admin.category.index') }}" class="nav-item sub-nav-item">
                        <i class="fas fa-tags"></i>
                        <span> Danh mục</span>
                    </a>
                    <a href="{{ route('admin.product.index') }}" class="nav-item sub-nav-item">
                        <i class="fas fa-box-open"></i>
                        <span> Sản phẩm</span>
                    </a>
                    <a href="{{ route('admin.brand.index') }}" class="nav-item sub-nav-item">
                        <i class="fas fa-box-open"></i>
                        <span> Thương hiệu</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-item sub-nav-item">
                        <i class="fas fa-box-open"></i>
                        <span> Người dùng</span>
                    </a>
                    <a href="{{ route('admin.admin.banner') }}" class="nav-item sub-nav-item">
                        <i class="fas fa-box-open"></i>
                        <span>Banner</span>
                    </a>
                    <a href="{{ route('admin.voucher.index') }}" class="nav-item sub-nav-item">
                        <i class="fas fa-ticket-alt"></i>Voucher
                    </a>
                </div>
                
                <div class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Khách Hàng</span>
                </div>

                <a href="{{ route('admin.statistics') }}" class="nav-item active">
                    <i class="fas fa-chart-line"></i>
                    <span>Thống Kê</span>
                </a>

                <a href="{{ route('admin.inventory.index') }}" class="nav-item">
                    <i class="fas fa-warehouse"></i>
                    <span> Kho hàng</span>
                </a>

                <div class="nav-item">
                    <i class="fas fa-tags"></i>
                    <span>Khuyến Mãi</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-star"></i>
                    <span>Đánh Giá</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Cài Đặt</span>
                </div>
            </nav>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- TOP BAR -->
            <div class="top-bar">
                <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                    <h2 style="font-size: 1.5rem; font-weight: 600; color: #ffffff;">
                        <i class="fas fa-chart-line"></i> Trang Thống Kê Quản Trị
                    </h2>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="dropdown-container">
                        <div class="icon-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">12</span>
                        </div>
                    </div>

                    <div class="dropdown-container">
                        <div class="user-profile" id="profileToggle">
                            <div class="avatar" style="overflow: hidden;">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=D4AF37&color=000' }}"
                                    alt="Avatar"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #ffffff;">{{ Auth::user()->name }}</div>
                                <div style="font-size: 0.85rem; color: #9a9a9a;">{{ Auth::user()->role }}</div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BỘ LỌC THỜI GIAN -->
            <div style="background: #1a1a1a; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #ffffff; margin-bottom: 1rem;">
                    Chọn khoảng thời gian:
                </h3>
                
                <form id="filterForm" method="GET" action="{{ route('admin.statistics') }}">
                    <div class="filter-buttons">
                        <button type="submit" name="filter" value="today" 
                            class="filter-button {{ $filter === 'today' ? 'active' : '' }}">
                            Hôm nay
                        </button>
                        <button type="submit" name="filter" value="last_7_days" 
                            class="filter-button {{ $filter === 'last_7_days' ? 'active' : '' }}">
                            7 ngày qua
                        </button>
                        <button type="submit" name="filter" value="last_30_days" 
                            class="filter-button {{ $filter === 'last_30_days' ? 'active' : '' }}">
                            30 ngày qua
                        </button>
                        <button type="submit" name="filter" value="week" 
                            class="filter-button {{ $filter === 'week' ? 'active' : '' }}">
                            Tuần này
                        </button>
                        <button type="submit" name="filter" value="month" 
                            class="filter-button {{ $filter === 'month' ? 'active' : '' }}">
                            Tháng này
                        </button>
                        <button type="submit" name="filter" value="year" 
                            class="filter-button {{ $filter === 'year' ? 'active' : '' }}">
                            Năm nay
                        </button>
                    </div>
                </form>

                <div class="date-range-info">
                    Dữ liệu từ <strong>{{ $startDate->format('d/m/Y') }}</strong> → <strong>{{ $endDate->format('d/m/Y') }}</strong>
                </div>
            </div>

            <!-- KPI CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }}₫</div>
                    <div class="stat-label">Tổng Doanh Thu</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
                    <div class="stat-label">Đơn Hàng Hoàn Thành</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon customers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalUsers, 0, ',', '.') }}</div>
                    <div class="stat-label">Tổng Khách Hàng</div>
                </div>
            </div>

            <!-- BIỂU ĐỒ + TOP SẢN PHẨM -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 2rem;">
                <!-- BIỂU ĐỒ DOANH THU -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h3 class="chart-title">Doanh Thu Theo Thời Gian</h3>
                    </div>
                    <canvas id="salesChart" style="max-height: 400px;"></canvas>
                </div>

                <!-- TOP 10 SẢN PHẨM BÁN CHẠY -->
                <div style="background: #1a1a1a; padding: 1.5rem; border-radius: 12px;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #ffffff; margin-bottom: 1rem;">
                        Top 10 Sản Phẩm Bán Chạy
                    </h3>
                    <div style="overflow-x: auto;">
                        <table class="top-products-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th style="text-align: right;">Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topSellingProducts as $item)
                                    <tr>
                                        <td>
                                            <div class="product-name" title="{{ $item->product_name }}">
                                                {{ $item->product_name }}
                                            </div>
                                        </td>
                                        <td style="text-align: right;">
                                            <span class="quantity-badge">
                                                {{ number_format($item->total_quantity, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" style="text-align: center; padding: 2rem; color: #9a9a9a;">
                                            Không có dữ liệu
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSubMenu(navItem) {
            const subMenu = navItem.nextElementSibling;
            const arrow = navItem.querySelector('.nav-arrow');

            if (subMenu && subMenu.classList.contains('sub-nav-container')) {
                if (subMenu.style.display === "none" || subMenu.style.display === "") {
                    subMenu.style.display = "block";
                    navItem.classList.add('active');
                    if (arrow) {
                        arrow.style.transform = "rotate(180deg)";
                    }
                } else {
                    subMenu.style.display = "none";
                    navItem.classList.remove('active');
                    if (arrow) {
                        arrow.style.transform = "rotate(0deg)";
                    }
                }
            }
        }

        function toggleBanner() {
            var bannerContent = document.getElementById('banner-content');
            if (bannerContent.style.display === 'none') {
                bannerContent.style.display = 'block';
            } else {
                bannerContent.style.display = 'none';
            }
        }

        // BIỂU ĐỒ DOANH THU
        document.addEventListener('DOMContentLoaded', function () {
            const salesData = @json($salesData ?? ['labels' => [], 'data' => []]);

            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: salesData.labels ?? [],
                    datasets: [{
                        label: 'Doanh Thu (₫)',
                        data: salesData.data ?? [],
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        borderColor: '#3b82f6',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: context => 'Doanh thu: ' + Number(context.parsed.y).toLocaleString('vi-VN') + ' ₫'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: {
                                color: '#9a9a9a',
                                callback: value => value.toLocaleString('vi-VN') + '₫'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#9a9a9a'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>