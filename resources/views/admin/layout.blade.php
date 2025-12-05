<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title', 'Tổng quan')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Dán toàn bộ CSS bạn gửi ở trên vào đây */
        {!! file_get_contents(public_path('css/admin-dashboard.css')) !!}
        /* Hoặc nếu bạn dùng Vite, thì để trong file riêng */
    </style>
</head>
<body class="bg-gray-950 text-gray-100">

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-crown text-3xl"></i>
                <span>Admin Pro</span>
            </div>
        </div>

        <nav class="nav-menu">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Tổng quan</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="nav-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i>
                <span>Đơn hàng</span>
            </a>
            <a href="{{ route('admin.products') }}" class="nav-item {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Sản phẩm</span>
            </a>

            <!-- MENU THỐNG KÊ - HIỆN NGAY TẠI CHỖ -->
            <div class="nav-item {{ request()->is('admin/statistics') ? 'active' : '' }}" 
                 onclick="loadStatistics()" style="cursor: pointer;">
                <i class="fas fa-chart-line"></i>
                <span>Thống kê</span>
            </div>

            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Khách hàng</span>
            </a>
            <a href="{{ route('admin.logout') }}" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <div class="top-bar">
            <div class="flex items-center gap-4">
                <button class="lg:hidden" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h1 class="text-2xl font-bold">@yield('page-title', 'Tổng quan')</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="icon-btn relative">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="dropdown-container">
                    <div class="user-profile">
                        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="dropdown-menu">
                        <div class="dropdown-header">Tài khoản</div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Hồ sơ</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.logout') }}" class="dropdown-item danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-area p-6">
            @yield('content')
        </div>
    </main>
</div>

<script>
// Toggle sidebar trên mobile
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// Load thống kê bằng AJAX (hiện ngay trong main-content)
function loadStatistics() {
    const mainContent = document.getElementById('main-content');
    const contentArea = mainContent.querySelector('.content-area');

    // Hiệu ứng loading
    contentArea.innerHTML = `<div class="flex justify-center items-center h-96"><i class="fas fa-spinner fa-spin text-4xl text-gray-500"></i></div>`;

    fetch('{{ route("admin.statistics.data") }}')
        .then(response => response.text())
        .then(html => {
            contentArea.innerHTML = html;
            document.querySelector('.top-bar h1').textContent = 'Thống Kê Doanh Thu';
            history.pushState({}, '', '{{ route("admin.statistics") }}');
        })
        .catch(() => {
            contentArea.innerHTML = '<p class="text-red-400">Lỗi tải dữ liệu thống kê!</p>';
        });
}

// Đóng sidebar khi click ngoài (mobile)
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth < 1024 && !sidebar.contains(e.target) && !e.target.closest('button')) {
        sidebar.classList.remove('active');
    }
});
</script>

</body>
</html>