<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quản Lý Đồng Hồ</title>
    @vite(['resources/css/admin/admindasboard.css', 'resources/js/admin/dasboard.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <div class="dashboard-container">
        <div class="sidebar" id="sidebar">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-clock"></i>
                    <a href="#">PREDATOR</a>
                    <span></span>
                </div>
            </div>

            <nav>
                <div class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Trang Chủ</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-shopping-bag"></i>
                    <span onclick="toggleBanner()">Giỏ hàng </span>
                    <div id="banner-content" style="display: none;">

                    </div>
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

                <div class="nav-item" style="">
                    <a href="{{ route('admin.statistics') }}" class="nav-item sub-nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Thống Kê</span>
                    </a>
                </div>

                <a href="{{ route('admin.inventory.index') }}" class="nav-item sub-nav-item">

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

        <div class="main-content">
            <div class="top-bar">
                <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                    <input type="text" class="search-bar" placeholder="Tìm kiếm đơn hàng, sản phẩm, khách hàng...">
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">

                    <div class="dropdown-container">
                        <div class="icon-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">12</span>
                        </div>
                        <div class="dropdown-menu dropdown-lg">
                            <div class="dropdown-header">
                                Thông Báo (12)
                            </div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <div class="dropdown-content">
                                    <span>Đơn hàng #DH-00847 đã hoàn thành</span>
                                    <span class="text-muted">5 phút trước</span>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-users"></i>
                                <div class="dropdown-content">
                                    <span>Khách hàng mới: Trần Thị B</span>
                                    <span class="text-muted">1 giờ trước</span>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-star"></i>
                                <div class="dropdown-content">
                                    <span>Đánh giá 5 sao mới</span>
                                    <span class="text-muted">3 giờ trước</span>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-footer">
                                <a href="#" class="dropdown-item">
                                    Xem tất cả thông báo
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-container">
                        <div class="icon-btn">
                            <i class="fas fa-envelope"></i>
                            <span class="notification-badge">5</span>
                        </div>
                        <div class="dropdown-menu dropdown-lg">
                            <div class="dropdown-header">
                                Tin Nhắn (5)
                            </div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <div class="dropdown-content">
                                    <span>Nguyễn Văn A</span>
                                    <span class="text-muted">Tôi cần tư vấn về mẫu Rolex...</span>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <div class="dropdown-content">
                                    <span>Lê Văn C</span>
                                    <span class="text-muted">Sản phẩm có sẵn không?</span>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-footer">
                                <a href="#" class="dropdown-item">
                                    Xem tất cả tin nhắn
                                </a>
                            </div>
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
                                <div style="font-weight: 600; color: #ffffff;">{{ Auth::user()->name}}</div>
                                <div style="font-size: 0.85rem; color: #9a9a9a;">{{ Auth::user()->role}} </div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu" id="profileDropdown">
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>Hồ Sơ Của Tôi</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Cài Đặt</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-question-circle"></i>
                                <span>Hỗ Trợ</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item danger">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Đăng Xuất</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI CARDS - DỮ LIỆU ĐỘNG -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-value">{{ number_format($revenueThisMonth, 0, ',', '.') }}₫</div>
                    <div class="stat-label">Doanh Thu Tháng Này</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 12.5%
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
                    <div class="stat-label">Đơn Hàng</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 8.2%
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon products">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                    <div class="stat-label">Sản Phẩm</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 3.1%
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon customers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
                    <div class="stat-label">Khách Hàng</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 15.3%
                    </span>
                </div>
            </div>

            <!-- BIỂU ĐỒ - DỮ LIỆU ĐỘNG -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Thống Kê Doanh Thu</h3>
                    <div class="chart-filters">
                        <button class="filter-btn active">7 Ngày</button>
                        <button class="filter-btn">30 Ngày</button>
                        <button class="filter-btn">90 Ngày</button>
                        <button class="filter-btn">1 Năm</button>
                    </div>
                </div>
                <canvas id="revenueChart" style="max-height: 350px;"></canvas>
            </div>

            <!-- BẢNG ĐƠN HÀNG - DỮ LIỆU ĐỘNG -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">Đơn Hàng Gần Đây</h3>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Sản Phẩm</th>
                            <th>Khách Hàng</th>
                            <th>Ngày Đặt</th>
                            <th>Giá Trị</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>#DH-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($order->items->first() && $order->items->first()->product && $order->items->first()->product->hinh_anh)
                                        <img src="{{ asset('storage/' . $order->items->first()->product->hinh_anh) }}"
                                            class="product-img" alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%232a2a2a' width='50' height='50'/%3E%3Ccircle cx='25' cy='25' r='15' fill='%233a3a3a'/%3E%3Ccircle cx='25' cy='25' r='8' fill='%23c0c0c0'/%3E%3C/svg%3E"
                                            class="product-img" alt="Watch">
                                    @endif
                                    <span>{{ $order->items->first()->product_name ?? 'Sản phẩm' }}</span>
                                </div>
                            </td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td style="font-weight: 600;">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                            <td>
                                @if($order->status == 'completed')
                                    <span class="status-badge completed">Hoàn Thành</span>
                                @elseif($order->status == 'processing')
                                    <span class="status-badge processing">Đang Xử Lý</span>
                                @elseif($order->status == 'pending')
                                    <span class="status-badge pending">Chờ Xác Nhận</span>
                                @else
                                    <span class="status-badge">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    <div class="action-btn"><i class="fas fa-eye"></i></div>
                                    <div class="action-btn"><i class="fas fa-edit"></i></div>
                                    <div class="action-btn"><i class="fas fa-trash"></i></div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                                Chưa có đơn hàng nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

        // BIỂU ĐỒ DOANH THU - DỮ LIỆU ĐỘNG TỪ PHP
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Doanh Thu (₫)',
                        data: @json($chartData),
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + '₫';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + '₫';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>