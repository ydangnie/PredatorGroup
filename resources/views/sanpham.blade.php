<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>

@include('layouts.navbar.header')

<div class="wt-main-container">
    <button class="wt-mobile-filter-toggle" onclick="toggleSidebar()">☰ Bộ Lọc Sản Phẩm</button>

    <div class="wt-content-wrapper">
        {{-- MỞ FORM ĐỂ BAO QUANH CẢ SIDEBAR VÀ SORT SELECT --}}
        <form action="{{ route('sanpham') }}" method="GET" id="filterForm" class="d-flex w-100" style="display: flex; gap: 20px; width: 100%;">
            
            {{-- Giữ lại từ khóa tìm kiếm nếu có --}}
            @if(request('keyword'))
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            @endif

            <aside class="wt-filter-sidebar" id="wtFilterSidebar">
                <div class="wt-filter-header">
                    <h3 class="wt-filter-title">Bộ Lọc</h3>
                    <a href="{{ route('sanpham') }}" class="wt-clear-button" type="button" style="text-decoration: none; font-size: 12px;">Xóa Tất Cả</a>
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
                        <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên: A-Z</option>
                    </select>
                </div>

                {{-- DANH SÁCH SẢN PHẨM --}}
                <div class="wt-products-grid" id="wtProductsGrid">
                    @if($products->count() > 0)
                        @foreach($products as $product)
                            <a href="{{ route('chitietsanpham', ['id' => $product->id]) }}" class="wt-product-card">
                                
                                @if($product->created_at > now()->subDays(7))
                                    <span class="wt-product-badge">MỚI</span>
                                @endif

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

@include('layouts.navbar.footer')

<script>
    // Hàm bật tắt sidebar trên mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('wtFilterSidebar');
        sidebar.classList.toggle('active'); // Cần thêm CSS .active cho sidebar ở mobile view nếu chưa có
        
        // CSS đơn giản cho mobile toggle (nếu chưa có trong file css gốc)
        if(sidebar.style.display === 'block') {
            sidebar.style.display = 'none';
        } else {
            sidebar.style.display = 'block';
        }
    }
</script>
