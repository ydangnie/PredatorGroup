<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quản Lý Đồng Hồ</title>   
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-clock"></i>
                    <span>TimeKeeper</span>
                </div>
            </div>
            
            <nav>
                <div class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Trang Chủ</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Đơn Hàng</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-clock"></i>
                    <span>Sản Phẩm</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Khách Hàng</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Thống Kê</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-warehouse"></i>
                    <span>Kho Hàng</span>
                </div>
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

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                    <input type="text" class="search-bar" placeholder="Tìm kiếm đơn hàng, sản phẩm, khách hàng...">
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">12</span>
                    </div>
                    <div class="icon-btn">
                        <i class="fas fa-envelope"></i>
                        <span class="notification-badge">5</span>
                    </div>
                    <div class="user-profile">
                        <div class="avatar">AD</div>
                        <div>
                            <div style="font-weight: 600; color: #ffffff;">Admin</div>
                            <div style="font-size: 0.85rem; color: #9a9a9a;">Quản trị viên</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-value">1.245.800.000₫</div>
                    <div class="stat-label">Doanh Thu Tháng Này</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 12.5%
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-value">1,847</div>
                    <div class="stat-label">Đơn Hàng</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 8.2%
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon products">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">324</div>
                    <div class="stat-label">Sản Phẩm</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 3.1%
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon customers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">12,459</div>
                    <div class="stat-label">Khách Hàng</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 15.3%
                    </span>
                </div>
            </div>

            <!-- Chart -->
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

            <!-- Recent Orders Table -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">Đơn Hàng Gần Đây</h3>
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm Đơn Hàng
                    </button>
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
                        <tr>
                            <td>#DH-00847</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%232a2a2a' width='50' height='50'/%3E%3Ccircle cx='25' cy='25' r='15' fill='%233a3a3a'/%3E%3Ccircle cx='25' cy='25' r='8' fill='%23c0c0c0'/%3E%3C/svg%3E" class="product-img" alt="Watch">
                                    <span>Rolex Submariner</span>
                                </div>
                            </td>
                            <td>Nguyễn Văn A</td>
                            <td>15/01/2024</td>
                            <td style="font-weight: 600;">285.000.000₫</td>
                            <td><span class="status-badge completed">Hoàn Thành</span></td>
                            <td>
                                <div class="action-btns">
                                    <div class="action-btn"><i class="fas fa-eye"></i></div>
                                    <div class="action-btn"><i class="fas fa-edit"></i></div>
                                    <div class="action-btn"><i class="fas fa-trash"></i></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#DH-00846</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%232a2a2a' width='50' height='50'/%3E%3Crect x='15' y='15' width='20' height='20' rx='3' fill='%233a3a3a'/%3E%3Ccircle cx='25' cy='25' r='6' fill='%23a0a0a0'/%3E%3C/svg%3E" class="product-img" alt="Watch">
                                    <span>Omega Seamaster</span>
                                </div>
                            </td>
                            <td>Trần Thị B</td>
                            <td>15/01/2024</td>
                            <td style="font-weight: 600;">156.000.000₫</td>
                            <td><span class="status-badge processing">Đang Xử Lý</span></td>
                            <td>
                                <div class="action-btns">
                                    <div class="action-btn"><i class="fas fa-eye"></i></div>
                                    <div class="action-btn"><i class="fas fa-edit"></i></div>
                                    <div class="action-btn"><i class="fas fa-trash"></i></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#DH-00845</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%232a2a2a' width='50' height='50'/%3E%3Ccircle cx='25' cy='25' r='18' fill='%233a3a3a'/%3E%3Cpath d='M25 15 L25 25 L32 25' stroke='%23808080' stroke-width='2' fill='none'/%3E%3C/svg%3E" class="product-img" alt="Watch">
                                    <span>Patek Philippe Nautilus</span>
                                </div>
                            </td>
                            <td>Lê Văn C</td>
                            <td>14/01/2024</td>
                            <td style="font-weight: 600;">895.000.000₫</td>
                            <td><span class="status-badge pending">Chờ Xác Nhận</span></td>
                            <td>
                                <div class="action-btns">
                                    <div class="action-btn"><i class="fas fa-eye"></i></div>
                                    <div class="action-btn"><i class="fas fa-edit"></i></div>
                                    <div class="action-btn"><i class="fas fa-trash"></i></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#DH-00844</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%232a2a2a' width='50' height='50'/%3E%3Crect x='10' y='10' width='30' height='30' rx='15' fill='%233a3a3a'/%3E%3Ccircle cx='25' cy='25' r='10' fill='%23c0c0c0'/%3E%3C/svg%3E" class="product-img" alt="Watch">
                                    <span>Audemars Piguet Royal Oak</span>
                                </div>
                            </td>
                            <td>Phạm Thị D</td>
                            <td>14/01/2024</td>
                            <td style="font-weight: 600;">645.000.000₫</td>
                            <td><span class="status-badge completed">Hoàn Thành</span></td>
                            <td>
                                <div class="action-btns">
                                    <div class="action-btn"><i class="fas fa-eye"></i></div>
                                    <div class="action-btn"><i class="fas fa-edit"></i></div>
                                    <div class="action-btn"><i class="fas fa-trash"></i></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#DH-00843</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%232a2a2a' width='50' height='50'/%3E%3Ccircle cx='25' cy='25' r='16' fill='%233a3a3a'/%3E%3Ccircle cx='25' cy='25' r='4' fill='%23a0a0a0'/%3E%3C/svg%3E" class="product-img" alt="Watch">
                                    <span>Tag Heuer Carrera</span>
                                </div>
                            </td>
                            <td>Hoàng Văn E</td>
                            <td>13/01/2024</td>
                            <td style="font-weight: 600;">125.000.000₫</td>
                            <td><span class="status-badge processing">Đang Xử Lý</span></td>
                            <td>
                                <div class="action-btns">
                                    <div class="action-btn"><i class="fas fa-eye"></i></div>
                                    <div class="action-btn"><i class="fas fa-edit"></i></div>
                                    <div class="action-btn"><i class="fas fa-trash"></i></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
      
    </script>
</body>
</html>