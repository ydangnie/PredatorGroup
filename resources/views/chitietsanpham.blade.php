<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->tensp }} - Predator Group</title>
    {{-- CSS chính --}}
    @vite(['resources/css/layout/chitietsanpham.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        .toast-msg i {
            color: #D4AF37;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>
</head>

<body>
    @include('layouts.navbar.header')

    <div class="wtch-container-main" id="main1">
        <div class="wtch-breadcrumb">
            <a href="/">Trang chủ</a> /
            <a href="{{ route('sanpham') }}">Sản phẩm</a> /
            <span>{{ $product->tensp }}</span>
        </div>

        <div class="wtch-product-section">
            <div class="wtch-gallery-zone">
                <div class="wtch-main-image">
                    <img id="mainImage" src="{{ asset('storage/' . $product->hinh_anh) }}" alt="{{ $product->tensp }}">
                    <button class="wtch-wishlist-btn" onclick="toggleWishlist(this)" title="Thêm vào yêu thích">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>

                @if($product->images->count() > 0)
                <div class="wtch-thumbnail-row">
                    <div class="wtch-thumb-item active" onclick="changeImage(this, '{{ asset('storage/' . $product->hinh_anh) }}')">
                        <img src="{{ asset('storage/' . $product->hinh_anh) }}" alt="Main">
                    </div>
                    @foreach($product->images as $img)
                    <div class="wtch-thumb-item" onclick="changeImage(this, '{{ asset('storage/' . $img->image_path) }}')">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Gallery">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="wtch-details-zone">
                <div>
                    <span class="wtch-brand-tag">{{ $product->brand ? $product->brand->ten_thuonghieu : 'PREDATOR' }}</span>
                    <h1 class="wtch-product-title">{{ $product->tensp }}</h1>

                    <div class="wtch-rating-zone">
                        <div class="wtch-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <span>(Mã SP: {{ $product->sku ?? 'N/A' }})</span>
                    </div>
                </div>

                <div class="wtch-price-zone">
                    <span class="wtch-current-price">{{ number_format($product->gia, 0, ',', '.') }} ₫</span>
                </div>

                @if(isset($colors) && $colors->count() > 0)
                <div class="wtch-attr-section">
                    <div class="wtch-attr-label">Màu sắc</div>
                    <div class="wtch-size-options">
                        @foreach($colors as $color)
                        <div class="wtch-size-item" onclick="selectAttr(this)">{{ $color }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($sizes) && $sizes->count() > 0)
                <div class="wtch-attr-section">
                    <div class="wtch-attr-label">Kích thước</div>
                    <div class="wtch-size-options">
                        @foreach($sizes as $size)
                        <div class="wtch-size-item" onclick="selectAttr(this)">{{ $size }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="wtch-attr-section">
                    <div class="wtch-attr-label">Số lượng</div>
                    <div class="wtch-quantity-zone">
                        <div class="wtch-qty-control">
                            <button class="wtch-qty-btn" onclick="changeQty(-1)">−</button>
                            <input type="text" class="wtch-qty-input" value="1" id="qtyInput" readonly>
                            <button class="wtch-qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                        <span style="font-size: 13px; color: #666;">Còn {{ $product->so_luong }} sản phẩm</span>
                    </div>
                </div>

                <div class="wtch-action-buttons">
                    <button class="wtch-btn-primary" onclick="addToCart()">
                        <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                    </button>
                    <button class="wtch-btn-secondary">Mua Ngay</button>
                </div>

                <div class="wtch-features-grid">
                    <div class="wtch-feature-item"><i class="fa-solid fa-shield-halved wtch-feature-icon"></i> Bảo hành chính hãng</div>
                    <div class="wtch-feature-item"><i class="fa-solid fa-truck-fast wtch-feature-icon"></i> Miễn phí vận chuyển</div>
                    <div class="wtch-feature-item"><i class="fa-solid fa-rotate wtch-feature-icon"></i> Đổi trả trong 30 ngày</div>
                    <div class="wtch-feature-item"><i class="fa-regular fa-gem wtch-feature-icon"></i> 100% Authentic</div>
                </div>
            </div>
        </div>

        <div class="wtch-info-tabs">
            <div class="wtch-tab-nav">
                <button class="wtch-tab-btn active" onclick="switchTab('desc')">Mô tả sản phẩm</button>
                <button class="wtch-tab-btn" onclick="switchTab('specs')">Thông số kỹ thuật</button>
            </div>

            <div class="wtch-tab-content active" id="tab-desc">
                @if($product->mota)
                {!! nl2br(e($product->mota)) !!}
                @else
                <p style="color: #666; font-style: italic;">Đang cập nhật mô tả.</p>
                @endif
            </div>

            <div class="wtch-tab-content" id="tab-specs">
                <table class="wtch-specs-table">
                    <tr>
                        <td>Thương hiệu</td>
                        <td>{{ $product->brand ? $product->brand->ten_thuonghieu : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Danh mục</td>
                        <td>{{ $product->category ? $product->category->ten_danhmuc : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Giới tính</td>
                        <td>{{ $product->gender == 'male' ? 'Nam' : ($product->gender == 'female' ? 'Nữ' : 'Unisex') }}</td>
                    </tr>
                    <tr>
                        <td>Mã sản phẩm</td>
                        <td>{{ $product->sku ?? '---' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="wtch-related-section">
            <h3 class="wtch-section-title">Có thể bạn sẽ thích</h3>
            <div class="wtch-products-grid">
                @foreach($relatedProducts as $related)
                <a href="{{ route('chitietsanpham', ['id' => $related->id]) }}" class="wtch-product-card">
                    <div class="wtch-card-image">
                        <img src="{{ asset('storage/' . $related->hinh_anh) }}" alt="{{ $related->tensp }}">
                    </div>
                    <div class="wtch-card-body">
                        <h4 class="wtch-card-title">{{ $related->tensp }}</h4>
                        <div class="wtch-card-current">{{ number_format($related->gia, 0, ',', '.') }} ₫</div>
                    </div>
                </a>
                @endforeach
            </div>
            @if($relatedProducts->isEmpty())
            <p style="text-align:center; color:#666;">Chưa có sản phẩm liên quan.</p>
            @endif
        </div>
    </div>

    @include('layouts.navbar.footer')

    {{-- Thêm CSS cho thông báo Toast --}}
    <style>
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

        .toast-msg i {
            color: #D4AF37;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>

    <div id="toast-box"></div>

<script>
    // Hàm đổi ảnh chính
    function changeImage(el, src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.wtch-thumb-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
    }

    // Hàm chọn size/màu
    function selectAttr(el) {
        Array.from(el.parentNode.children).forEach(sib => sib.classList.remove('selected'));
        el.classList.add('selected');
    }

    // Hàm yêu thích
    function toggleWishlist(btn) {
        btn.classList.toggle('active');
        let icon = btn.querySelector('i');
        if (btn.classList.contains('active')) {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            icon.style.color = '#d4af37';
        } else {
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            icon.style.color = '#fff';
        }
    }

    // Hàm tăng giảm số lượng
    function changeQty(n) {
        let input = document.getElementById('qtyInput');
        let val = parseInt(input.value) + n;
        // SỬA LỖI: Viết liền mạch cú pháp Blade trên 1 dòng
        const max = {{ $product->so_luong ?? 100 }}; 
        
        if (val >= 1 && val <= max) input.value = val;
    }

    // Hàm chuyển tab
    function switchTab(id) {
        document.querySelectorAll('.wtch-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.wtch-tab-content').forEach(c => c.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById('tab-' + id).classList.add('active');
    }

    // --- HÀM THÊM VÀO GIỎ HÀNG (Đã sửa lỗi) ---
    function addToCart() {
        // SỬA LỖI: Viết liền cú pháp lấy ID
        const productId = {{ $product->id }};
        const qtyInput = document.getElementById('qtyInput');
        const quantity = parseInt(qtyInput.value);

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // 1. Cập nhật số lượng trên Header (nếu có badge)
                const badge = document.getElementById('cart-count-badge');
                if (badge) {
                    badge.innerText = data.total_qty;
                    badge.style.display = 'inline-block';
                }

                // 2. Hiện thông báo thành công (Toast)
                showToast('Thành công!', `Đã thêm ${quantity} sản phẩm vào giỏ.`);
            } else {
                alert('Có lỗi xảy ra: ' + (data.error || 'Vui lòng thử lại'));
            }
        })
        .catch(error => console.error('Lỗi:', error));
    }

    // Hàm hiển thị thông báo Toast
    function showToast(title, msg) {
        const box = document.getElementById('toast-box');
        if (!box) return; // Kiểm tra nếu chưa có div toast-box

        const toast = document.createElement('div');
        toast.classList.add('toast-msg');
        toast.innerHTML = `<i class="fa-solid fa-check-circle"></i> <div><strong>${title}</strong><br><span style="font-size:12px; color:#ccc;">${msg}</span></div>`;
        box.appendChild(toast);

        // Tự động xóa sau 3.5s
        setTimeout(() => {
            toast.remove();
        }, 3500);
    }
</script>
</body>

</html>