<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PREDATORWATCH - Luxury Timepieces</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Đảm bảo FontAwesome được load nếu chưa có trong app.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* CSS CHO THÔNG BÁO & POPUP */
        .header-item-notify { position: relative; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; }
        
        /* Badge số lượng đỏ trên chuông */
        .notify-badge {
            position: absolute;
            top: 0px;
            right: 0px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            border: 1px solid #000;
        }

        /* Dropdown thông báo */
        .notify-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: -50px;
            width: 320px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 1000;
            overflow: hidden;
            border: 1px solid #eee;
        }
        .notify-dropdown.active { display: block; }
        
        .notify-header {
            background: #111;
            padding: 10px 15px;
            font-weight: bold;
            color: #D4AF37;
            border-bottom: 1px solid #333;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .notify-body {
            max-height: 300px;
            overflow-y: auto;
            background: #fff;
        }

        .notify-item {
            display: flex;
            padding: 12px 15px;
            border-bottom: 1px solid #f1f1f1;
            text-decoration: none;
            align-items: center;
            transition: background 0.2s;
        }
        .notify-item:hover { background: #f9f9f9; }
        .notify-item img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 12px;
            border: 1px solid #eee;
        }
        .notify-info { flex: 1; }
        .notify-title {
            font-size: 13px;
            font-weight: 700;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 2px;
        }
        .notify-desc { font-size: 11px; color: #155724; font-weight: 500; }

        /* Toast Popup góc màn hình */
        .review-toast {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: #fff;
            border-left: 4px solid #D4AF37;
            width: 320px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-radius: 4px;
            z-index: 9999;
            animation: slideInLeft 0.5s ease;
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
        }
        .toast-header {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f8f8;
            border-top-right-radius: 4px;
        }
        .toast-body { padding: 15px; font-size: 14px; color: #444; line-height: 1.5; }
        .btn-close-toast { background: none; border: none; font-size: 18px; cursor: pointer; color: #999; }
        .btn-close-toast:hover { color: #333; }
        .btn-view-now {
            background: #111;
            color: #D4AF37;
            border: 1px solid #D4AF37;
            padding: 6px 15px;
            border-radius: 2px;
            font-size: 12px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-view-now:hover { background: #D4AF37; color: #fff; }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>


<header class="header">
    <div class="header-top">
        <div class="header-top-content">
            <span>✉ contact@predatorwatch.com</span>
            <span>⚡ MIỄN PHÍ VẬN CHUYỂN CHO ĐƠN HÀNG TRÊN 10 TRIỆU</span>
            <span>📞 1900 888 999</span>
        </div>
    </div>
    <div class="header-main">
        <div class="logo"><a href="/">PREDATORWATCH</a></div>

        <nav>
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item">
                    <a href="{{ route('sanpham') }}" class="nav-link">
                        SẢN PHẨM
                        <span class="dropdown-arrow">▼</span>
                    </a>
                    <div class="mega-menu">
                        <div class="mega-menu-grid">
                            <div class="mega-menu-column">
                                <h4>Theo Thương Hiệu</h4>
                                <a href="#" class="mega-menu-item">Rolex</a>
                                <a href="#" class="mega-menu-item">Omega</a>
                                <a href="#" class="mega-menu-item">Patek Philippe</a>
                                <a href="#" class="mega-menu-item">Audemars Piguet</a>
                            </div>
                            <div class="mega-menu-column">
                                <h4>Theo Danh Mục </h4>
                                <a href="#" class="mega-menu-item">Đồng Hồ Lặn</a>
                                <a href="#" class="mega-menu-item">Đồng Hồ Quân Đội</a>
                                <a href="#" class="mega-menu-item">Đồng Hồ Thụy Sĩ</a>
                                <a href="#" class="mega-menu-item">Apple Watch</a>
                            </div>
                            <div class="mega-menu-column">
                                <h4>Mức giá </h4>
                                <a href="#" class="mega-menu-item">Trên 5 triệu</a>
                                <a href="#" class="mega-menu-item">Trên 10 triệu</a>
                                <a href="#" class="mega-menu-item">trên 15 triệu</a>
                                <a href="#" class="mega-menu-item">trên 50 triệu</a>
                            </div>

                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">Nam</a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">Nữ</a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">Dịch Vụ
                        <span class="dropdown-arrow">▼</span>
                    </a>
                    <ul class="dropdown">
                        <li><a href="{{ route('lienhe') }}" class="dropdown-item">Liên hệ</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="header-actions">
            <form action="#" method="GET" class="search-box" id="searchBox">
                <input type="text" name="keyword" class="search-input" placeholder="Tìm kiếm...">
                <button type="button" class="action-btn" onclick="toggleSearch()">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <a class="action-btn" href="{{ route('giohang') }}" style="position: relative;">
                <i class="fa-solid fa-cart-shopping"></i>
                {{-- Badge số lượng --}}
                <span id="cart-count-badge"
                    style="position: absolute; top: -5px; right: -5px; 
                 background-color: #D4AF37; color: #000; 
                 font-size: 10px; font-weight: bold; 
                 padding: 2px 5px; border-radius: 50%; 
                 display: none;">0</span>
            </a>

            {{-- 
                ============================================
                ICON THÔNG BÁO (CHUÔNG) - MỚI THÊM
                ============================================
            --}}
            @auth
            <div class="action-btn header-item-notify" style="cursor: pointer;" onclick="toggleNotify()">
                <i class="fa-regular fa-bell"></i>
                
                @if(isset($productsToReview) && $productsToReview->count() > 0)
                    <span class="notify-badge">{{ $productsToReview->count() }}</span>
                    
                    <div class="notify-dropdown" id="notifyDropdown">
                        <div class="notify-header">Sản phẩm chờ đánh giá</div>
                        <div class="notify-body">
                            @foreach($productsToReview as $prod)
                            <a href="{{ route('chitietsanpham', $prod->id) }}#review-section" class="notify-item">
                                <img src="{{ asset('storage/' . $prod->hinh_anh) }}" alt="img">
                                <div class="notify-info">
                                    <div class="notify-title">{{ $prod->tensp }}</div>
                                    <div class="notify-desc"><i class="fa-solid fa-check-circle"></i> Đã giao hàng. Đánh giá ngay!</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @else
            <button class="action-btn">
                <i class="fa-regular fa-heart"></i>
            </button>
            @endauth

            <li class="nav-item" id="logout">
                @guest
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                @endguest
                @auth
                <a href="#" class="action-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="dropdown-arrow">▼</span>
                </a>
                <ul class="dropdown">
                    @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('admin.dasboard') }}" class="dropdown-item">Quản trị admin</a></li>
                    @endif
                    <li><a href="{{ route('profile.index') }}" class="dropdown-item">Hồ sơ</a></li>
                    <li style="color: red;"><a href="#" class="dropdown-item">Lịch sử đơn hàng</a></li>
                    <li style="color: red;"><a href="{{ route('dangxuat') }}" class="dropdown-item">Đăng xuất</a></li>
                </ul>
                @endauth
            </li>
            <button class="mobile-toggle" onclick="toggleMenu()">☰</button>
        </div>
    </div>
</header>

{{-- 
    ============================================
    POPUP NHẮC NHỞ (TOAST) - MỚI THÊM
    ============================================
--}}
@if(auth()->check() && isset($productsToReview) && $productsToReview->count() > 0)
<div id="review-reminder-toast" class="review-toast">
    <div class="toast-header">
        <strong class="me-auto" style="color:#D4AF37"><i class="fa-solid fa-star"></i> Đánh giá sản phẩm</strong>
        <button type="button" class="btn-close-toast" onclick="closeToast()">&times;</button>
    </div>
    <div class="toast-body">
        Bạn có <b>{{ $productsToReview->count() }}</b> sản phẩm đã nhận hàng nhưng chưa đánh giá. 
        <br><span style="font-size:12px; color:#888;">Hãy đánh giá để giúp cộng đồng mua sắm tốt hơn!</span>
        <div style="margin-top: 15px; text-align: right;">
            <button class="btn-view-now" onclick="toggleNotify()">Xem ngay</button>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Fetch số lượng giỏ hàng
        fetch('{{ route("cart.count") }}')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('cart-count-badge');
                if(data.count > 0) {
                    badge.innerText = data.count;
                    badge.style.display = 'inline-block';
                }
            })
            .catch(err => console.log('Lỗi cart count:', err));

        // 2. Tự động tắt Toast sau 15 giây nếu không tương tác
        setTimeout(() => {
            closeToast();
        }, 15000);
    });

    // Hàm bật/tắt dropdown thông báo
    function toggleNotify() {
        const dropdown = document.getElementById('notifyDropdown');
        if (dropdown) {
            dropdown.classList.toggle('active');
        }
    }

    // Hàm đóng Popup Toast
    function closeToast() {
        const toast = document.getElementById('review-reminder-toast');
        if (toast) {
            toast.style.display = 'none';
        }
    }

    // Click ra ngoài thì tắt dropdown thông báo
    window.addEventListener('click', function(e) {
        const notifyBox = document.querySelector('.header-item-notify');
        if (notifyBox && !notifyBox.contains(e.target)) {
            const dropdown = document.getElementById('notifyDropdown');
            if (dropdown) dropdown.classList.remove('active');
        }
    });

    // Các hàm cũ (toggleSearch, toggleMenu) nếu có ở file JS ngoài thì OK, 
    // nếu chưa có thì cần đảm bảo chúng tồn tại.
    function toggleSearch() {
        const box = document.getElementById('searchBox');
        box.classList.toggle('active'); // Bạn cần CSS cho class .active này nếu chưa có
    }
    
    function toggleMenu() {
        const menu = document.getElementById('navMenu');
        menu.classList.toggle('active'); // Bạn cần CSS cho class .active này nếu chưa có
    }
</script>