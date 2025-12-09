@include('layouts.navbar.header')

<style>
    .wishlist-container { max-width: 1200px; margin: 40px auto; padding: 0 15px; min-height: 60vh; }
    .wishlist-title { text-align: center; margin-bottom: 30px; font-size: 28px; font-weight: bold; color: #111; }
    /* Tận dụng lại class css của trang sản phẩm */
    @import url('{{ asset("resources/css/layout/sanpham.css") }}');
</style>

<div class="wishlist-container">
    <h1 class="wishlist-title">Danh Sách Yêu Thích ❤️</h1>

    @if($wishlists->count() > 0)
        <div class="wt-products-grid">
            @foreach($wishlists as $item)
                @php $product = $item->product; @endphp
                @if($product)
                <div class="wt-product-card" style="position: relative;">
                    {{-- Nút xóa nhanh --}}
                    <button onclick="toggleWishlist(event, {{ $product->id }}); this.closest('.wt-product-card').remove();" 
                            style="position: absolute; top: 10px; right: 10px; z-index: 10; border: none; background: #fff; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-times" style="color: #555;"></i>
                    </button>

                    <a href="{{ route('chitietsanpham', ['id' => $product->id]) }}" style="text-decoration: none; color: inherit;">
                        <div class="wt-product-image-wrapper">
                            <img src="{{ asset('storage/' . $product->hinh_anh) }}" class="wt-product-image" alt="{{ $product->tensp }}">
                        </div>
                        <div class="wt-product-info">
                            <h3 class="wt-product-name">{{ $product->tensp }}</h3>
                            <div class="wt-product-price-section">
                                <span class="wt-product-price">{{ number_format($product->gia, 0, ',', '.') }} VNĐ</span>
                                <span style="font-size: 12px; color: #888;">Thêm ngày: {{ $item->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 50px;">
            <i class="fa-regular fa-heart" style="font-size: 50px; color: #ccc; margin-bottom: 20px;"></i>
            <h3>Bạn chưa có sản phẩm yêu thích nào.</h3>
            <a href="{{ route('sanpham') }}" style="color: #D4AF37; text-decoration: underline;">Khám phá ngay</a>
        </div>
    @endif
</div>

{{-- Script xử lý xóa (dùng lại logic cũ) --}}
<script>
    function toggleWishlist(event, productId) {
        event.preventDefault();
        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        // Không cần xử lý UI update phức tạp ở đây vì ta remove luôn element cha
    }
</script>

@include('layouts.navbar.footer')
