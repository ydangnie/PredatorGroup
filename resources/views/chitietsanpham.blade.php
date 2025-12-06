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
        /* Toast Notification Styles */
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
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }

        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(100%); }
        }

        /* Review Section Styles */
        .wtch-reviews-section {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .wtch-review-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            font-family: inherit;
        }
        
        .wtch-review-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }

        .review-item {
            border-bottom: 1px solid #f1f1f1;
            padding: 20px 0;
        }
        
        .review-item:last-child {
            border-bottom: none;
        }

        .review-avatar {
            width: 40px;
            height: 40px;
            background: #eee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #555;
            margin-right: 15px;
            font-size: 16px;
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

        {{-- Phần chi tiết sản phẩm --}}
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
                            {{-- Hiển thị sao trung bình trên đầu trang --}}
                            @php $rating = $averageRating ?? 0; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($rating) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <span>(Mã SP: {{ $product->sku ?? 'N/A' }} | {{ $reviewCount ?? 0 }} đánh giá)</span>
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

        {{-- Tabs thông tin --}}
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

        {{-- ========================================== --}}
        {{-- PHẦN ĐÁNH GIÁ & BÌNH LUẬN (ĐÃ SỬA LOGIC) --}}
        {{-- ========================================== --}}
        <div class="wtch-reviews-section" id="review-section">
            <h3 class="wtch-section-title">Khách hàng nói về sản phẩm</h3>
            
            <div class="wtch-rating-summary" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="font-size: 48px; font-weight: bold; color: #333;">
                        {{ number_format($averageRating ?? 0, 1) }}/5
                    </div>
                    <div>
                        <div class="wtch-stars" style="color: #FFD700; font-size: 20px; margin-bottom: 5px;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($averageRating ?? 0) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <span style="color: #666;">Dựa trên {{ $reviewCount ?? 0 }} đánh giá thực tế</span>
                    </div>
                </div>
            </div>

            {{-- Thông báo lỗi/thành công từ Session --}}
            @if(session('success'))
                <div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            {{-- LOGIC HIỂN THỊ FORM --}}
            @auth
                @if(isset($hasPurchased) && $hasPurchased)
                    {{-- FORM CHO NGƯỜI ĐÃ MUA HÀNG (Có chọn Sao) --}}
<form action="{{ route('review.store') }}" method="POST" class="wtch-review-form" style="margin-bottom: 40px; background: #fffcf0; padding: 20px; border: 1px dashed #D4AF37; border-radius: 6px;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <h4 style="margin-bottom: 15px; font-size: 16px; color: #D4AF37;"><i class="fa-solid fa-award"></i> Đánh giá sản phẩm đã mua</h4>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="font-weight: 600; margin-right: 10px;">Chất lượng sản phẩm:</label>
                            <div class="star-rating-input" style="display: inline-block;">
                                <select name="so_sao" style="padding: 5px; border-radius: 4px; border: 1px solid #D4AF37; outline: none;">
                                    <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                    <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                                    <option value="3">⭐⭐⭐ (Bình thường)</option>
                                    <option value="2">⭐⭐ (Không hài lòng)</option>
                                    <option value="1">⭐ (Rất tệ)</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <textarea name="binh_luan" rows="3" placeholder="Sản phẩm dùng thế nào? Hãy chia sẻ cảm nhận của bạn..." style="width:100%; padding:10px; border:1px solid #D4AF37; border-radius:4px;"></textarea>
                        </div>

                        <button type="submit" class="wtch-btn-primary" style="padding: 8px 25px; font-size: 14px;">Gửi đánh giá</button>
                    </form>
                @else
                    {{-- FORM CHO NGƯỜI CHƯA MUA (Chỉ Bình luận, Không sao) --}}
                    <form action="{{ route('review.store') }}" method="POST" class="wtch-review-form" style="margin-bottom: 40px; background: #f9f9f9; padding: 20px; border-radius: 6px;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <h4 style="margin-bottom: 15px; font-size: 16px; color: #333;"><i class="fa-regular fa-comments"></i> Hỏi đáp & Bình luận</h4>
                        
                        {{-- Không có input so_sao ở đây --}}

                        <div style="margin-bottom: 15px;">
                            <textarea name="binh_luan" rows="3" required placeholder="Bạn có thắc mắc gì về sản phẩm này không?" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;"></textarea>
                        </div>
<button type="submit" class="wtch-btn-secondary" style="padding: 8px 25px; font-size: 14px;">Gửi câu hỏi</button>
                    </form>
                @endif
            @else
                <div style="margin-bottom: 30px; padding: 20px; background: #f9f9f9; text-align: center; border-radius: 8px;">
                    <p>Vui lòng <a href="{{ route('login') }}" style="color: #D4AF37; font-weight: bold; text-decoration: underline;">đăng nhập</a> để đánh giá hoặc bình luận.</p>
                </div>
            @endauth

            {{-- DANH SÁCH REVIEW & COMMENT --}}
            <div class="wtch-review-list">
                @if($product->reviews && $product->reviews->count() > 0)
                    @foreach($product->reviews as $review)
                    <div class="review-item">
                        <div style="display: flex;">
                            {{-- Avatar: Màu vàng nếu là người mua, màu xám nếu là khách --}}
                            <div class="review-avatar" style="{{ $review->so_sao ? 'background:#D4AF37; color:#fff;' : '' }}">
                                {{ strtoupper(substr($review->user ? $review->user->name : 'A', 0, 1)) }}
                            </div>
                            <div style="flex: 1;">
                                <div class="review-header" style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <div style="font-weight: bold; color: #333; display: flex; align-items: center; gap: 8px;">
                                        {{ $review->user ? $review->user->name : 'Người dùng ẩn danh' }}
                                        
                                        {{-- Badge xác nhận đã mua hàng --}}
                                        @if($review->so_sao)
                                            <span style="font-size: 11px; background: #e6fffa; color: #2c7a7b; padding: 2px 8px; border-radius: 12px; border: 1px solid #b2f5ea; display: flex; align-items: center; gap: 4px;">
                                                <i class="fa-solid fa-check-circle"></i> Đã mua hàng
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size: 12px; color: #999;">
                                        {{ $review->created_at->format('d/m/Y') }}
                                    </div>
                                </div>

                                {{-- Chỉ hiện sao nếu có (tức là người mua) --}}
                                @if($review->so_sao)
                                    <div class="review-stars" style="color: #FFD700; font-size: 12px; margin-bottom: 8px;">
                                        @for($i = 1; $i <= 5; $i++)
<i class="{{ $i <= $review->so_sao ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                @else
                                    <div style="margin-bottom: 5px; font-size: 12px; color: #888; font-style: italic;">
                                        <i class="fa-regular fa-comment-dots"></i> Câu hỏi / Thảo luận
                                    </div>
                                @endif

                                <div class="review-content" style="color: #555; line-height: 1.6; font-size: 14px;">
                                    {{ $review->binh_luan }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p style="text-align: center; color: #777; font-style: italic; margin-top: 20px;">Chưa có đánh giá nào. Hãy là người đầu tiên chia sẻ cảm nhận!</p>
                @endif
            </div>
        </div>

        {{-- Sản phẩm liên quan --}}
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

        // Hàm thêm vào giỏ hàng
        function addToCart() {
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
                    const badge = document.getElementById('cart-count-badge');
                    if (badge) {
                        badge.innerText = data.total_qty;
                        badge.style.display = 'inline-block';
                    }
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
            if (!box) return;

            const toast = document.createElement('div');
            toast.classList.add('toast-msg');
            toast.innerHTML = `<i class="fa-solid fa-check-circle"></i> <div><strong>${title}</strong><br><span style="font-size:12px; color:#ccc;">${msg}</span></div>`;
            box.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3500);
        }
    </script>
</body>

</html>
