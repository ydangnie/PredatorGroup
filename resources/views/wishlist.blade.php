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

<script>
    // Chỉ cần giữ lại logic xử lý xóa
    function toggleWishlist(event, productId) {
        event.preventDefault();
        
        // Gọi API xóa
        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            // Cập nhật số lượng trên header
            const badge = document.getElementById('wishlist-count-badge');
            if(badge) {
                let currentCount = parseInt(badge.innerText) || 0;
                if(currentCount > 0) {
                    badge.innerText = currentCount - 1;
                    if(currentCount - 1 === 0) badge.style.display = 'none';
                }
            }

            // Gọi hàm thông báo từ Footer
            if(typeof showToast === 'function') {
                showToast(data.message, 'removed');
            }
        });
        
        // Xóa ngay phần tử giao diện (UI)
        // Tìm thẻ cha .wt-product-card để xóa
        const card = event.target.closest('.wt-product-card');
        if(card) card.remove();
        
        // Kiểm tra nếu xóa hết thì hiện thông báo rỗng (Optional)
        const grid = document.querySelector('.wt-products-grid');
        if(grid && grid.children.length === 0) {
            document.querySelector('.wishlist-container').innerHTML = `
                <div style="text-align: center; padding: 50px;">
                    <h3>Bạn chưa có sản phẩm yêu thích nào.</h3>
                    <a href="/sanpham">Khám phá ngay</a>
                </div>`;
        }
    }
</script>

@include('layouts.navbar.footer')