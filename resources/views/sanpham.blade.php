<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bộ Sưu Tập Đồng Hồ Cao Cấp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Chỉ load file CSS --}}
    @vite(['resources/css/layout/sanpham.css'])
    
    {{-- CSS phụ trợ để phân trang mặc định của Laravel trông đẹp hơn nếu chưa custom --}}
    <style>
        .wt-pagination svg { width: 20px; }
        .wt-pagination nav { display: flex; justify-content: center; gap: 10px; }
    </style>
</head>

@include('layouts.navbar.header')

<div class="wt-main-container">
    <button class="wt-mobile-filter-toggle">☰ Bộ Lọc Sản Phẩm</button>

    <div class="wt-content-wrapper">
        <aside class="wt-filter-sidebar" id="wtFilterSidebar">
            {{-- ... (Giữ nguyên nội dung bên trong aside) ... --}}
            <div class="wt-filter-header">
                <h3 class="wt-filter-title">Bộ Lọc</h3>
                <button class="wt-clear-button">Xóa Tất Cả</button>
            </div>
            {{-- ... --}}
        </aside>

        <main class="wt-products-section">
            <div class="wt-products-controls">
                {{-- Hiển thị số lượng sản phẩm --}}
                <span class="wt-products-count" id="wtProductCount">
                    Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trên tổng {{ $products->total() }} sản phẩm
                </span>
                <select class="wt-sort-select" id="wtSortSelect">
                    <option value="default">Sắp Xếp Mặc Định</option>
                    <option value="price-low">Giá: Thấp đến Cao</option>
                    <option value="price-high">Giá: Cao đến Thấp</option>
                    <option value="name-asc">Tên: A-Z</option>
                </select>
            </div>

            {{-- DANH SÁCH SẢN PHẨM ĐỘNG --}}
            <div class="wt-products-grid" id="wtProductsGrid">
                @if($products->count() > 0)
                    @foreach($products as $product)
                        {{-- Link trỏ đến trang chi tiết (cần truyền ID hoặc Slug) --}}
                        {{-- Giả sử route chi tiết nhận tham số id: route('chitietsanpham', ['id' => $product->id]) --}}
                        <a href="{{ route('chitietsanpham', ['id' => $product->id]) }}" class="wt-product-card">
                            
                            {{-- Badge Mới (Hiển thị nếu sản phẩm mới tạo trong 7 ngày) --}}
                            @if($product->created_at > now()->subDays(7))
                                <span class="wt-product-badge">MỚI</span>
                            @endif

                            <div class="wt-product-image-wrapper">
                                {{-- Hiển thị ảnh từ storage --}}
                                <img src="{{ asset('storage/' . $product->hinh_anh) }}" 
                                     alt="{{ $product->tensp }}" 
                                     class="wt-product-image"
                                     onerror="this.src='https://placehold.co/300x300?text=No+Image'">
                            </div>

                            <div class="wt-product-info">
                                {{-- Tên thương hiệu --}}
                                <div class="wt-product-brand">
                                    {{ $product->brand ? $product->brand->ten_thuonghieu : 'No Brand' }}
                                </div>

                                {{-- Tên sản phẩm --}}
                                <h3 class="wt-product-name">{{ $product->tensp }}</h3>

                                {{-- Thông số kỹ thuật --}}
                                <div class="wt-product-specs">
                                    {{-- Giới tính --}}
                                    <span class="wt-product-spec">
                                        @if($product->gender == 'male') <i class="fa-solid fa-mars"></i> Nam
                                        @elseif($product->gender == 'female') <i class="fa-solid fa-venus"></i> Nữ
                                        @else <i class="fa-solid fa-genderless"></i> Unisex
                                        @endif
                                    </span>
                                    
                                    {{-- Tên danh mục --}}
                                    <span class="wt-product-spec">
                                        <i class="fa-solid fa-clock"></i> {{ $product->category ? $product->category->ten_danhmuc : '' }}
                                    </span>
                                </div>

                                <div class="wt-product-price-section">
                                    {{-- Giá tiền --}}
                                    <span class="wt-product-price">{{ number_format($product->gia, 0, ',', '.') }} VNĐ</span>
                                    <button class="wt-add-cart-button">Thêm</button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                        <h3>Chưa có sản phẩm nào.</h3>
                    </div>
                @endif
            </div>

            {{-- PHÂN TRANG (PAGINATION) --}}
            <div class="wt-pagination-wrapper">
                <div class="wt-pagination">
                    {{-- Sử dụng link phân trang mặc định của Laravel --}}
                    {{ $products->links() }} 
                </div>
            </div>
        </main>
    </div>
</div>

@include('layouts.navbar.footer')