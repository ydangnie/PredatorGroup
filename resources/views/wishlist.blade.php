<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Danh Sách Yêu Thích - Predator Group</title>
    
    @vite(['resources/css/layout/sanpham.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === DARK MODE THEME === */
        body { 
            background-color: #000; /* Nền đen chủ đạo */
            color: #eee; 
            font-family: 'Inter', sans-serif;
        }
        
        .wishlist-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            min-height: 60vh;
        }

        /* Tiêu đề */
        .wishlist-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .wishlist-title {
            font-size: 32px;
            font-weight: 700;
            color: #D4AF37; /* Màu vàng Gold */
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }
        .wishlist-divider {
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            margin: 0 auto;
        }

        /* Card sản phẩm tối màu */
        .wt-product-card {
            background: #1a1a1a; /* Xám đen */
            border: 1px solid #333;
            border-radius: 4px;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .wt-product-card:hover {
            transform: translateY(-5px);
            border-color: #D4AF37;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        /* Ảnh sản phẩm */
        .wt-product-image-wrapper {
            background: #222;
            padding: 10px;
        }

        /* Nút xóa Dark Mode */
        .btn-remove-wishlist {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 20;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #555;
            color: #ccc;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-remove-wishlist:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: #fff;
            transform: rotate(90deg);
        }

        /* Thông tin sản phẩm */
        .wt-product-info {
            padding: 15px;
        }
        .wt-product-brand {
            color: #888;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .wt-product-name {
            color: #fff;
            font-size: 16px;
            margin: 5px 0 10px;
            height: 40px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .wt-product-price {
            color: #D4AF37;
            font-weight: bold;
            font-size: 18px;
        }
        .date-added {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }

        /* Empty State Dark Mode */
        .empty-wishlist {
            text-align: center;
            padding: 80px 20px;
            background: #111;
            border: 1px dashed #333;
            border-radius: 8px;
        }
        .empty-icon {
            font-size: 60px;
            color: #333;
            margin-bottom: 20px;
        }
        .empty-text {
            color: #888;
            margin-bottom: 25px;
        }
        .btn-explore {
            display: inline-block;
            padding: 12px 35px;
            background: transparent;
            color: #D4AF37;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: none;
            border: 1px solid #D4AF37;
            transition: all 0.3s ease;
        }
        .btn-explore:hover {
            background: #D4AF37;
            color: #000;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
        }

        .fading-out {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.4s ease;
        }
        
        /* Toast Notification (nếu chưa có global css) */
        #toast-box {
            position: fixed;
            bottom: 30px; right: 30px; z-index: 9999;
        }
        .toast-msg {
            background: #1E1E1E; border-left: 4px solid #D4AF37;
            color: #fff; padding: 15px 20px; margin-top: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5); display: flex; align-items: center; gap: 10px;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }
    </style>
</head>

<body>
    @include('layouts.navbar.header')

    <div class="wishlist-container">
        <div class="wishlist-header">
            <h1 class="wishlist-title">Bộ Sưu Tập Yêu Thích</h1>
            <div class="wishlist-divider"></div>
        </div>

        <div id="wishlist-content">
            @if($wishlists->count() > 0)
                <div class="wt-products-grid">
                    @foreach($wishlists as $item)
                        @php $product = $item->product; @endphp
                        @if($product)
                        <div class="wt-product-card" id="wishlist-item-{{ $product->id }}">
                            {{-- Nút xóa --}}
                            <button class="btn-remove-wishlist" 
                                    onclick="removeFromWishlist(event, {{ $product->id }})"
                                    title="Xóa khỏi danh sách">
                                <i class="fa-solid fa-times"></i>
                            </button>

                            <a href="{{ route('chitietsanpham', ['id' => $product->id]) }}" style="text-decoration: none; color: inherit; display: block;">
                                <div class="wt-product-image-wrapper">
                                    <img src="{{ asset('storage/' . $product->hinh_anh) }}" 
                                         class="wt-product-image" 
                                         alt="{{ $product->tensp }}"
                                         onerror="this.src='https://placehold.co/300x300/333/999?text=No+Image'">
                                </div>
                                <div class="wt-product-info">
                                    <div class="wt-product-brand">
                                        {{ $product->brand ? $product->brand->ten_thuonghieu : 'PREDATOR' }}
                                    </div>
                                    <h3 class="wt-product-name">{{ $product->tensp }}</h3>
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                                        <span class="wt-product-price">{{ number_format($product->gia, 0, ',', '.') }} ₫</span>
                                        <span style="font-size: 18px; color: #D4AF37;"><i class="fa-solid fa-arrow-right"></i></span>
                                    </div>
                                    <div class="date-added">Đã thêm: {{ $item->created_at->format('d/m/Y') }}</div>
                                </div>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                {{-- Trạng thái trống --}}
                <div class="empty-wishlist">
                    <i class="fa-regular fa-heart empty-icon"></i>
                    <h3 style="color: #fff;">Danh sách yêu thích đang trống</h3>
                    <p class="empty-text">Hãy lưu lại những tuyệt tác thời gian mà bạn khao khát.</p>
                    <a href="{{ route('sanpham') }}" class="btn-explore">Khám phá ngay</a>
                </div>
            @endif
        </div>
    </div>

    <div id="toast-box"></div>

    @include('layouts.navbar.footer')

    <script>
        function removeFromWishlist(event, productId) {
            event.preventDefault();
            event.stopPropagation(); 

            const card = document.getElementById(`wishlist-item-${productId}`);
            if(!card) return;

            card.classList.add('fading-out');

            fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'removed') {
                    updateWishlistBadge(-1);
                    if(typeof showToast === 'function') showToast(data.message); // Sử dụng hàm toast nếu có
                    
                    setTimeout(() => {
                        card.remove();
                        checkEmptyState();
                    }, 400);
                } else {
                    card.classList.remove('fading-out');
                }
            })
            .catch(err => {
                console.error(err);
                card.classList.remove('fading-out');
            });
        }

        function updateWishlistBadge(amount) {
            const badge = document.getElementById('wishlist-count-badge');
            if(badge) {
                let currentCount = parseInt(badge.innerText) || 0;
                let newCount = currentCount + amount;
                badge.innerText = newCount > 0 ? newCount : 0;
                if(newCount <= 0) badge.style.display = 'none';
            }
        }

        function checkEmptyState() {
            const grid = document.querySelector('.wt-products-grid');
            const container = document.getElementById('wishlist-content');
            
            if (grid && grid.children.length === 0) {
                container.innerHTML = `
                    <div class="empty-wishlist">
                        <i class="fa-regular fa-heart empty-icon"></i>
                        <h3 style="color: #fff;">Danh sách yêu thích đang trống</h3>
                        <p class="empty-text">Hãy lưu lại những tuyệt tác thời gian mà bạn khao khát.</p>
                        <a href="{{ route('sanpham') }}" class="btn-explore">Khám phá ngay</a>
                    </div>
                `;
            }
        }

        // Hàm Toast fallback nếu chưa có
        if (typeof showToast !== 'function') {
            window.showToast = function(message) {
                const box = document.getElementById('toast-box');
                const toast = document.createElement('div');
                toast.className = 'toast-msg';
                toast.innerHTML = `<i class="fa-solid fa-check-circle" style="color: #D4AF37;"></i> <span>${message}</span>`;
                box.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        }
    </script>
</body>
</html>