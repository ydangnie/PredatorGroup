<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bộ Sưu Tập Đồng Hồ Cao Cấp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Chỉ load file CSS --}}
    @vite(['resources/css/layout/sanpham.css'])
    
    <style>
        .wt-pagination svg { width: 20px; }
        .wt-pagination nav { display: flex; justify-content: center; gap: 10px; }
        
        /* CSS Bổ sung cho Form lọc */
        .wt-filter-group { margin-bottom: 20px; }
        .wt-filter-group h4 { font-size: 16px; margin-bottom: 10px; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 5px;}
        .wt-filter-item { display: block; margin-bottom: 8px; font-size: 14px; cursor: pointer; color: #555; }
        .wt-filter-item:hover { color: #D4AF37; }
        .wt-filter-item input { margin-right: 8px; }
        .wt-btn-filter {
            width: 100%;
            background: #111;
            color: #D4AF37;
            padding: 10px;
            border: 1px solid #D4AF37;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 10px;
        }
        .wt-btn-filter:hover { background: #D4AF37; color: #fff; }

        /* CSS Bổ sung cho input tìm kiếm mới */
        .wt-search-input { 
            width: 100%; 
            padding: 8px 10px; 
            border: 1px solid #ddd; 
            margin-bottom: 10px; 
            box-sizing: border-box; 
            border-radius: 4px;
        }
        .wt-search-input:focus {
            border-color: #D4AF37;
            outline: none;
        }
        /* CSS cho nút Yêu thích */
        .wt-wishlist-btn {
            position: absolute; 
            top: 10px; 
            right: 10px; 
            z-index: 10; 
            background: rgba(255, 255, 255, 0.8); 
            border: none; 
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px; 
            cursor: pointer; 
            color: #ccc;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .wt-wishlist-btn:hover {
            transform: scale(1.1);
            background: #fff;
        }

        /* CSS cho Toast Notification */
        #toast-box {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-msg {
            background: #1E1E1E;
            border-left: 4px solid #D4AF37;
            color: #fff;
            padding: 15px 25px;
            border-radius: 4px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            animation: slideIn 0.5s ease, fadeOut 0.5s ease 2.5s forwards;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-msg i { font-size: 18px; }
        /* Màu icon tùy chỉnh */
        .toast-msg.added i { color: #4ade80; }
        .toast-msg.removed i { color: #f87171; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(100%); }
        }
    </style>
</head>

@include('layouts.navbar.header')

<div class="wt-main-container">
    <button class="wt-mobile-filter-toggle" onclick="toggleSidebar()">☰ Bộ Lọc Sản Phẩm</button>

    <div class="wt-content-wrapper">
        {{-- MỞ FORM ĐỂ BAO QUANH CẢ SIDEBAR VÀ SORT SELECT --}}
        <form action="{{ route('sanpham') }}" method="GET" id="filterForm" class="d-flex w-100" style="display: flex; gap: 20px; width: 100%;">
            
            {{-- Đã thay thế input hidden bằng input text trong sidebar để tìm kiếm trực tiếp --}}
            {{-- @if(request('keyword'))
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            @endif --}}

            <aside class="wt-filter-sidebar" id="wtFilterSidebar">
                <div class="wt-filter-header">
                    <h3 class="wt-filter-title">Bộ Lọc</h3>
                    <a href="{{ route('sanpham') }}" class="wt-clear-button" type="button" style="text-decoration: none; font-size: 12px;">Xóa Tất Cả</a>
                </div>

                {{-- THÊM 0. TÌM KIẾM SẢN PHẨM --}}
                <div class="wt-filter-group">
                    <h4>Tìm kiếm</h4>
                    <input type="text" 
                           name="keyword" 
                           value="{{ request('keyword') }}"
                           placeholder="Nhập tên sản phẩm..."
                           class="wt-search-input">
                    {{-- Nút "Áp Dụng" ở cuối sidebar sẽ submit form và tìm kiếm --}}
                </div>
                
                {{-- 1. Lọc Danh Mục (Từ DB Categories) --}}
                <div class="wt-filter-group">
                    <h4>Danh Mục</h4>
                    <label class="wt-filter-item">
                        <input type="radio" name="category" value="all" {{ request('category') == 'all' || !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                        Tất cả
                    </label>
                    @foreach($categories as $cate)
                    <label class="wt-filter-item">
                        <input type="radio" name="category" value="{{ $cate->id }}" {{ request('category') == $cate->id ? 'checked' : '' }} onchange="this.form.submit()">
                        {{ $cate->ten_danhmuc }}
                    </label>
                    @endforeach
                </div>

                {{-- 2. Lọc Thương Hiệu (Từ DB Brands) --}}
                <div class="wt-filter-group">
                    <h4>Thương Hiệu</h4>
                    <label class="wt-filter-item">
                        <input type="radio" name="brand" value="all" {{ request('brand') == 'all' || !request('brand') ? 'checked' : '' }} onchange="this.form.submit()">
                        Tất cả
                    </label>
                    @foreach($brands as $brand)
                    <label class="wt-filter-item">
                        <input type="radio" name="brand" value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="this.form.submit()">
                        {{ $brand->ten_thuonghieu }}
                    </label>
                    @endforeach
                </div>

                {{-- 3. Lọc Giới Tính (Cứng) --}}
                <div class="wt-filter-group">
                    <h4>Giới Tính</h4>
                    <label class="wt-filter-item">
                        <input type="radio" name="gender" value="all" {{ request('gender') == 'all' || !request('gender') ? 'checked' : '' }} onchange="this.form.submit()"> Tất cả
                    </label>
                    <label class="wt-filter-item">
                        <input type="radio" name="gender" value="male" {{ request('gender') == 'male' ? 'checked' : '' }} onchange="this.form.submit()"> Nam
                    </label>
                    <label class="wt-filter-item">
                        <input type="radio" name="gender" value="female" {{ request('gender') == 'female' ? 'checked' : '' }} onchange="this.form.submit()"> Nữ
                    </label>
                    <label class="wt-filter-item">
                        <input type="radio" name="gender" value="unisex" {{ request('gender') == 'unisex' ? 'checked' : '' }} onchange="this.form.submit()"> Unisex
                    </label>
                </div>
                
                {{-- Nút lọc dành cho mobile hoặc nếu muốn submit thủ công --}}
                <button type="submit" class="wt-btn-filter">Áp Dụng</button>
            </aside>

            <main class="wt-products-section" style="flex: 1;">
                <div class="wt-products-controls">
                    <span class="wt-products-count" id="wtProductCount">
                        @if($products->total() > 0)
                            Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trên tổng {{ $products->total() }} sản phẩm
                        @else
                            Không tìm thấy sản phẩm nào
                        @endif
                    </span>
                    
                    {{-- Select sắp xếp --}}
                    <select class="wt-sort-select" name="sort" id="wtSortSelect" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        {{-- ĐÃ SỬA: Thay price-high bằng price-desc để khớp với Controller --}}
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên: A-Z</option>
                    </select>
                </div>

                {{-- DANH SÁCH SẢN PHẨM --}}
                <div class="wt-products-grid" id="wtProductsGrid">
                    @if($products->count() > 0)
                        @foreach($products as $product)
                            <a href="{{ route('chitietsanpham', ['id' => $product->id]) }}" class="wt-product-card" style="position: relative;">
                                
                                {{-- Badge Mới --}}
                                @if($product->created_at > now()->subDays(7))
                                    <span class="wt-product-badge">MỚI</span>
                                @endif

                                {{-- NÚT TIM YÊU THÍCH (MỚI) --}}
                                <button type="button" 
                                        class="wt-wishlist-btn" 
                                        onclick="toggleWishlist(event, {{ $product->id }})">
                                    @if(isset($likedProductIds) && in_array($product->id, $likedProductIds))
                                        <i class="fa-solid fa-heart" style="color: #ef4444;"></i> {{-- Tim đỏ --}}
                                    @else
                                        <i class="fa-regular fa-heart"></i> {{-- Tim rỗng --}}
                                    @endif
                                </button>

                                <div class="wt-product-image-wrapper">
                                    <img src="{{ asset('storage/' . $product->hinh_anh) }}" 
                                         alt="{{ $product->tensp }}" 
                                         class="wt-product-image"
                                         onerror="this.src='https://placehold.co/300x300?text=No+Image'">
                                </div>

                                <div class="wt-product-info">
                                    <div class="wt-product-brand">
                                        {{ $product->brand ? $product->brand->ten_thuonghieu : 'No Brand' }}
                                    </div>

                                    <h3 class="wt-product-name">{{ $product->tensp }}</h3>

                                    <div class="wt-product-specs">
                                        <span class="wt-product-spec">
                                            @if($product->gender == 'male') <i class="fa-solid fa-mars"></i> Nam
                                            @elseif($product->gender == 'female') <i class="fa-solid fa-venus"></i> Nữ
                                            @else <i class="fa-solid fa-genderless"></i> Unisex
                                            @endif
                                        </span>
                                        <span class="wt-product-spec">
                                            <i class="fa-solid fa-clock"></i> {{ $product->category ? $product->category->ten_danhmuc : 'Khác' }}
                                        </span>
                                    </div>

                                    <div class="wt-product-price-section">
                                        <span class="wt-product-price">{{ number_format($product->gia, 0, ',', '.') }} VNĐ</span>
                                        <button class="wt-add-cart-button" type="button">Xem</button>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                            <h3>Không tìm thấy sản phẩm phù hợp.</h3>
                            <p>Vui lòng thử lại với từ khóa hoặc bộ lọc khác.</p>
                            <a href="{{ route('sanpham') }}" style="color: #D4AF37; text-decoration: underline;">Xóa bộ lọc</a>
                        </div>
                    @endif
                </div>

                {{-- PHÂN TRANG (PAGINATION) --}}
                <div class="wt-pagination-wrapper">
                    <div class="wt-pagination">
                        {{ $products->links() }} 
                    </div>
                </div>
            </main>
        </form> {{-- Đóng Form --}}
    </div>
</div>

{{-- Container cho Toast Notification --}}
<div id="toast-box"></div>

@include('layouts.navbar.footer')

<script>
    // Hàm bật tắt sidebar trên mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('wtFilterSidebar');
        sidebar.classList.toggle('active'); 
        
        // CSS đơn giản cho mobile toggle
        if(sidebar.style.display === 'block') {
            sidebar.style.display = 'none';
        } else {
            sidebar.style.display = 'block';
        }
    }

    // Hàm hiển thị Toast Notification
    function showToast(message, type) {
        const box = document.getElementById('toast-box');
        if (!box) return;

        const toast = document.createElement('div');
        toast.classList.add('toast-msg');
        if (type) toast.classList.add(type);

        let iconClass = 'fa-check-circle';
        if (type === 'removed') iconClass = 'fa-trash-can';

        toast.innerHTML = `<i class="fa-solid ${iconClass}"></i> <span>${message}</span>`;
        box.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3500);
    }

    // Hàm xử lý logic Wishlist
    function toggleWishlist(event, productId) {
        event.preventDefault(); 
        event.stopPropagation(); 

        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401 || response.status === 419) {
                if(confirm('Bạn cần đăng nhập để sử dụng tính năng này. Đi đến trang đăng nhập?')) {
                    window.location.href = '/dangnhap';
                }
                return null;
            }
            return response.json();
        })
        .then(data => {
            if(!data) return;

            const btn = event.target.closest('button');
            const icon = btn.querySelector('i');
            const badge = document.getElementById('wishlist-count-badge');
            
            let currentCount = 0;
            if (badge && badge.innerText) {
                currentCount = parseInt(badge.innerText) || 0;
            }

            if (data.status === 'added') {
                // Đổi tim đỏ
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                icon.style.color = '#ef4444';
                
                // Tăng số lượng badge
                if(badge) {
                    badge.innerText = currentCount + 1;
                    badge.style.display = 'inline-block';
                }
                
                // Hiển thị thông báo
                showToast(data.message, 'added');

            } else if (data.status === 'removed') {
                // Đổi tim rỗng
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                icon.style.color = '#ccc'; 
                
                // Giảm số lượng badge
                if(badge) {
                    let newCount = currentCount - 1;
                    badge.innerText = newCount > 0 ? newCount : 0;
                    if(newCount <= 0) badge.style.display = 'none';
                }

                // Hiển thị thông báo
                showToast(data.message, 'removed');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>