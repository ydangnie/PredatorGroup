<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - TimeLuxe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @vite(['resources/css/checkout/giohang.css'])
</head>

<body class="whcart_body_z19a">
    @include('layouts.navbar.header')
    
    <div class="whcart_page_shell_z19a">
        <div class="whcart_maxwrap_z19a">
            <main class="whcart_main_z19a">
                
                {{-- KIỂM TRA GIỎ HÀNG CÓ DỮ LIỆU KHÔNG --}}
                @if(session('cart') && count(session('cart')) > 0)
                    @php $grandTotal = 0; @endphp
                    
                    <section class="whcart_cart_block_z19a" aria-label="Nội dung giỏ hàng">
                        <div class="whcart_cart_inner_z19a">
                            <div class="whcart_cart_headerline_z19a">
                                <div>
                                    <div class="whcart_cart_title_z19a">Giỏ hàng của bạn</div>
                                    <div class="whcart_cart_subtitle_z19a">{{ count(session('cart')) }} sản phẩm trong giỏ • Giao nhanh toàn quốc</div>
                                </div>
                                <div class="whcart_cart_badgepill_z19a">
                                    <span class="whcart_cart_badge_dot_z19a"></span>
                                    <span>Đảm bảo chính hãng</span>
                                </div>
                            </div>

                            <div class="whcart_cart_list_z19a">
                                {{-- VÒNG LẶP SẢN PHẨM --}}
                                @foreach(session('cart') as $id => $details)
                                    @php 
                                        $lineTotal = $details['price'] * $details['quantity'];
                                        $grandTotal += $lineTotal;
                                    @endphp
                                    
                                    <article class="whcart_cart_item_z19a" data-id="{{ $id }}">
                                        <div class="whcart_cart_item_media_z19a">
                                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                        </div>
                                        
                                        <div class="whcart_cart_item_textblock_z19a">
                                            <div class="whcart_cart_item_name_z19a">{{ $details['name'] }}</div>
                                            {{-- Nếu có size/màu thì hiện ở đây --}}
                                            <div class="whcart_cart_item_desc_z19a">Mã SP: #{{ $id }}</div> 
                                            <div class="whcart_cart_item_meta_z19a">
                                                <span class="whcart_cart_item_meta_tag_z19a">Mới 100%</span>
                                                <span class="whcart_cart_item_meta_tag_z19a">Bảo hành chính hãng</span>
                                            </div>
                                        </div>
                                        
                                        <div class="whcart_cart_item_controls_z19a">
                                            <div class="whcart_cart_item_priceblock_z19a">
                                                {{-- Giá đơn vị --}}
                                                <div class="whcart_cart_item_price_z19a">{{ number_format($details['price'], 0, ',', '.') }}₫</div>
                                                <div class="whcart_cart_item_price_note_z19a">Thành tiền: <span class="line-total">{{ number_format($lineTotal, 0, ',', '.') }}₫</span></div>
                                            </div>
                                            
                                            <div>
                                                <div class="whcart_cart_item_qtywrap_z19a" aria-label="Số lượng">
                                                    <button class="whcart_cart_item_qtybtn_z19a btn-minus" type="button" onclick="updateQty(this, -1)">−</button>
                                                    <input class="whcart_cart_item_qtyinput_z19a qty-input" type="number" value="{{ $details['quantity'] }}" min="1" onchange="updateCartAjax(this)" />
                                                    <button class="whcart_cart_item_qtybtn_z19a btn-plus" type="button" onclick="updateQty(this, 1)">+</button>
                                                </div>
                                                <div class="whcart_cart_item_remove_z19a" onclick="removeItem(this)">Xóa</div>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                            <div class="whcart_cart_footer_z19a">
                                <a href="{{ route('sanpham') }}" class="whcart_cart_footer_link_z19a" style="text-decoration: none;">← Tiếp tục xem sản phẩm</a>
                                <div class="whcart_cart_footer_hint_z19a">Miễn phí giao hàng với đơn từ 5.000.000₫</div>
                            </div>
                        </div>
                    </section>

                    <aside class="whcart_summary_block_z19a" aria-label="Tóm tắt đơn hàng">
                        <div class="whcart_summary_inner_z19a">
                            <div class="whcart_summary_title_z19a">Tóm tắt đơn hàng</div>

                            <div class="whcart_summary_rows_z19a">
                                <div class="whcart_summary_row_z19a">
                                    <div class="whcart_summary_row_label_z19a">Tạm tính</div>
                                    <div class="whcart_summary_row_value_z19a" id="cart-subtotal">{{ number_format($grandTotal, 0, ',', '.') }}₫</div>
                                </div>
                                <div class="whcart_summary_row_z19a">
                                    <div class="whcart_summary_row_label_z19a">Phí vận chuyển</div>
                                    <div class="whcart_summary_row_value_z19a">Miễn phí</div>
                                </div>
                                <div class="whcart_summary_row_z19a whcart_summary_row_important_z19a whcart_summary_row_total_z19a">
                                    <div class="whcart_summary_row_label_z19a">Tổng cộng</div>
                                    <div class="whcart_summary_row_value_z19a" id="cart-total">{{ number_format($grandTotal, 0, ',', '.') }}₫</div>
                                </div>
                            </div>

                            <div class="whcart_summary_coupon_z19a">
                                <div class="whcart_summary_coupon_label_z19a">Mã ưu đãi</div>
                                <div class="whcart_summary_coupon_fieldrow_z19a">
                                    <input id="whcart_coupon_input_z19a" class="whcart_summary_coupon_input_z19a" type="text" placeholder="Nhập mã giảm giá">
                                    <button type="button" class="whcart_summary_coupon_button_z19a">Áp dụng</button>
                                </div>
                            </div>

                            <div class="whcart_summary_notice_z19a">
                                Sản phẩm chính hãng Predator Group. Bảo hành điện tử toàn cầu.
                            </div>

                            <div class="whcart_summary_actions_z19a">
                                <a href="{{ route('checkout') }}" class="whcart_summary_btn_primary_z19a" style="display:block; text-align:center; text-decoration:none;">Tiến hành thanh toán</a>
                            </div>

                            <div class="whcart_summary_secure_z19a">
                                <div class="whcart_summary_secure_icon_z19a">✓</div>
                                <div>Bảo mật thanh toán chuẩn SSL</div>
                            </div>
                        </div>
                    </aside>

                @else
                    {{-- TRANG GIỎ HÀNG TRỐNG --}}
                    <div style="text-align: center; padding: 100px 20px; color: #fff;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#444" stroke-width="1.5" style="margin-bottom: 20px;">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <h2 style="font-size: 24px; margin-bottom: 10px; font-family: 'Poppins', sans-serif;">Giỏ hàng của bạn đang trống</h2>
                        <p style="color: #888; margin-bottom: 30px;">Hãy chọn những sản phẩm đẳng cấp để thêm vào bộ sưu tập.</p>
                        <a href="{{ route('sanpham') }}" class="whcart_summary_btn_primary_z19a" style="text-decoration: none; display: inline-block; width: auto; padding: 12px 40px;">Mua Sắm Ngay</a>
                    </div>
                @endif

            </main>
        </div>
        @include('layouts.navbar.footer')
    </div>

    {{-- JAVASCRIPT XỬ LÝ AJAX --}}
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // 1. Xử lý nút bấm Tăng/Giảm
        function updateQty(btn, change) {
            const input = btn.parentElement.querySelector('.qty-input');
            let newVal = parseInt(input.value) + change;
            if(newVal < 1) newVal = 1;
            
            input.value = newVal;
            updateCartAjax(input); // Gọi hàm cập nhật
        }

        // 2. Gọi API cập nhật số lượng
        function updateCartAjax(input) {
            const article = input.closest('article');
            const id = article.getAttribute('data-id');
            const qty = input.value;

            fetch('{{ route("cart.update") }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ id: id, quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Cập nhật thành tiền dòng đó
                    const lineTotalEl = article.querySelector('.line-total');
                    if(lineTotalEl) lineTotalEl.innerText = data.item_total + '₫';

                    // Cập nhật tổng đơn hàng
                    updateSummary(data.grand_total);
                }
            });
        }

        // 3. Xử lý Xóa sản phẩm
        function removeItem(btn) {
            if(!confirm("Bạn có chắc muốn xóa sản phẩm này?")) return;

            const article = btn.closest('article');
            const id = article.getAttribute('data-id');

            fetch('{{ route("cart.remove") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Xóa dòng HTML với hiệu ứng mờ dần
                    article.style.transition = "opacity 0.5s";
                    article.style.opacity = "0";
                    setTimeout(() => {
                        article.remove();
                        
                        // Nếu hết sản phẩm thì reload để hiện trang trống
                        if(data.cart_count == 0) location.reload();
                        
                    }, 500);

                    // Cập nhật tổng tiền
                    updateSummary(data.grand_total);
                }
            });
        }

        // Helper: Cập nhật các số tổng ở sidebar
        function updateSummary(totalStr) {
            const subtotalEl = document.getElementById('cart-subtotal');
            const totalEl = document.getElementById('cart-total');
            
            if(subtotalEl) subtotalEl.innerText = totalStr + '₫';
            if(totalEl) totalEl.innerText = totalStr + '₫';
        }
    </script>
</body>
</html>