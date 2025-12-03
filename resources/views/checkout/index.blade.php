<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán | Predator Group</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/layout/main.css'])
    
    <style>
        body { background-color: #121212; color: #e0e0e0; font-family: 'Inter', sans-serif; }
        .checkout-container { max-width: 1200px; margin: 120px auto 60px; padding: 0 20px; display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; }
        
        /* Card Styles */
        .chk-card { background: #1E1E1E; border: 1px solid #333; border-radius: 8px; padding: 30px; }
        .chk-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #333; padding-bottom: 15px; }
        .chk-title { font-family: 'Playfair Display', serif; font-size: 24px; color: #fff; margin: 0; }
        
        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-size: 13px; color: #888; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .form-input { width: 100%; background: #121212; border: 1px solid #333; color: #fff; padding: 12px 15px; border-radius: 4px; font-size: 14px; transition: 0.3s; }
        .form-input:focus { border-color: #D4AF37; outline: none; }
        textarea.form-input { resize: vertical; min-height: 100px; }

        /* Address Select Button */
        .btn-address-book {
            background: transparent; border: 1px solid #D4AF37; color: #D4AF37;
            padding: 6px 15px; border-radius: 4px; font-size: 12px; font-weight: 700;
            text-transform: uppercase; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 5px;
        }
        .btn-address-book:hover { background: #D4AF37; color: #000; }

        /* Payment Methods */
        .payment-methods { display: flex; flex-direction: column; gap: 15px; margin-top: 10px; }
        .payment-option { display: flex; align-items: center; gap: 15px; padding: 15px; border: 1px solid #333; border-radius: 6px; cursor: pointer; transition: 0.3s; background: #161616; }
        .payment-option.selected { border-color: #D4AF37; background: rgba(212, 175, 55, 0.05); }
        .payment-radio { accent-color: #D4AF37; width: 18px; height: 18px; }
        .payment-info h4 { color: #fff; font-size: 14px; margin: 0 0 4px 0; font-weight: 600; }
        .payment-info p { color: #888; font-size: 12px; margin: 0; }

        /* Order Summary */
        .order-summary { background: #161616; border: 1px solid #333; border-radius: 8px; padding: 25px; position: sticky; top: 100px; }
        .summary-item { display: flex; gap: 15px; margin-bottom: 20px; border-bottom: 1px solid #2a2a2a; padding-bottom: 20px; }
        .sm-img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #333; }
        .sm-info { flex: 1; }
        .sm-name { color: #fff; font-size: 14px; font-weight: 600; margin-bottom: 4px; display: block; }
        .sm-meta { color: #888; font-size: 12px; }
        .sm-price { color: #D4AF37; font-size: 14px; font-weight: 600; }
        
        .total-row { display: flex; justify-content: space-between; margin-bottom: 12px; color: #aaa; font-size: 14px; }
        .total-row.final { border-top: 1px solid #333; padding-top: 15px; margin-top: 15px; color: #D4AF37; font-size: 18px; font-weight: 700; }
        
        .btn-confirm { 
            display: block; width: 100%; background: #D4AF37; color: #000; border: none; 
            padding: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; 
            border-radius: 4px; cursor: pointer; transition: 0.3s; margin-top: 25px; 
        }
        .btn-confirm:hover { background: #c5a028; transform: translateY(-2px); }

        /* MODAL ADDRESS */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); z-index: 9999; display: none;
            justify-content: center; align-items: center;
        }
        .modal-content {
            background: #1E1E1E; width: 500px; max-width: 90%; border-radius: 8px;
            border: 1px solid #333; padding: 30px; position: relative;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 15px; }
        .close-modal { background: none; border: none; color: #fff; font-size: 24px; cursor: pointer; }
        
        .addr-list-item {
            background: #121212; border: 1px solid #333; padding: 15px; 
            border-radius: 4px; margin-bottom: 10px; cursor: pointer; transition: 0.2s;
        }
        .addr-list-item:hover { border-color: #D4AF37; background: #1a1a1a; }
        .addr-name { color: #fff; font-weight: 700; font-size: 14px; margin-bottom: 5px; }
        .addr-text { color: #aaa; font-size: 13px; }
        
        /* Error Alert */
        .alert-error {
            background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #dc3545;
            padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;
        }
        .alert-error ul { margin: 0; padding-left: 20px; }

        @media (max-width: 992px) { .checkout-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('layouts.navbar.header')

    <div class="checkout-container">
        
        {{-- 1. HIỂN THỊ LỖI NẾU CÓ --}}
        @if ($errors->any())
            <div class="alert-error">
                <strong>Vui lòng kiểm tra lại thông tin:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="chk-card">
                <div class="chk-header">
                    <h2 class="chk-title">Thông Tin Giao Hàng</h2>
                    {{-- Nút mở Modal chọn địa chỉ --}}
                    @if(isset($addresses) && $addresses->count() > 0)
                    <button type="button" class="btn-address-book" onclick="openAddressModal()">
                        <i class="fa-solid fa-address-book"></i> Chọn từ sổ địa chỉ
                    </button>
                    @endif
                </div>
                
                <div class="form-group">
                    <label class="form-label">Họ và tên người nhận</label>
                    <input type="text" name="name" id="inpName" class="form-input" value="{{ old('name', $info['name']) }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" id="inpPhone" class="form-input" value="{{ old('phone', $info['phone']) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" value="{{ $info['email'] }}" disabled style="color:#666; cursor:not-allowed;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ chi tiết</label>
                    <input type="text" name="address" id="inpAddress" class="form-input" value="{{ old('address', $info['address']) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Ghi chú (Tùy chọn)</label>
                    <textarea name="note" class="form-input" placeholder="Giao giờ hành chính...">{{ old('note') }}</textarea>
                </div>

                <h2 class="chk-title" style="margin-top: 40px; margin-bottom: 20px;">Phương Thức Thanh Toán</h2>
                <div class="payment-methods">
                    <label class="payment-option selected" onclick="selectPayment(this)">
                        <input type="radio" name="payment_method" value="cod" class="payment-radio" checked>
                        <div class="payment-info">
                            <h4>Thanh toán khi nhận hàng (COD)</h4>
                            <p>Thanh toán tiền mặt cho shipper khi nhận hàng.</p>
                        </div>
                    </label>
                    <label class="payment-option" onclick="selectPayment(this)">
                        <input type="radio" name="payment_method" value="banking" class="payment-radio">
                        <div class="payment-info">
                            <h4>Thanh toán qua VNPAY</h4>
                            <p>Thẻ ATM, Visa, MasterCard, QR Code (Qua cổng VNPAY).</p>
                        </div>
                    </label>
                </div>
            </div>
        </form>

        <div class="order-summary">
            <h2 class="chk-title" style="font-size: 20px; border-bottom: none; margin-bottom: 20px;">Đơn Hàng ({{ count($cart) }})</h2>
            <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px; padding-right: 5px;">
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp
                    <div class="summary-item">
                        <img src="{{ asset('storage/' . $item['image']) }}" class="sm-img">
                        <div class="sm-info">
                            <span class="sm-name">{{ $item['name'] }}</span>
                            <div style="display:flex; justify-content:space-between;">
                                <span class="sm-meta">SL: {{ $item['quantity'] }}</span>
                                <span class="sm-price">{{ number_format($item['price'] * $item['quantity']) }}₫</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="total-row"><span>Tạm tính</span><span>{{ number_format($total) }}₫</span></div>
            <div class="total-row"><span>Phí vận chuyển</span><span style="color: #4ade80;">Miễn phí</span></div>
            <div class="total-row final"><span>TỔNG CỘNG</span><span>{{ number_format($total) }}₫</span></div>
            
            {{-- NÚT ĐẶT HÀNG (GỌI HÀM JS ĐỂ SUBMIT FORM AN TOÀN) --}}
            <button type="button" onclick="submitCheckout()" class="btn-confirm">ĐẶT HÀNG NGAY</button>
            
            <a href="{{ route('giohang') }}" style="display:block; text-align:center; margin-top:15px; color:#666; font-size:13px; text-decoration:underline;">Quay lại giỏ hàng</a>
        </div>
    </div>

    @if(isset($addresses) && $addresses->count() > 0)
    <div class="modal-overlay" id="addressModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="color:#fff; margin:0;">Chọn Địa Chỉ Giao Hàng</h3>
                <button class="close-modal" onclick="closeAddressModal()">&times;</button>
            </div>
            <div style="max-height: 300px; overflow-y: auto;">
                @foreach($addresses as $addr)
                <div class="addr-list-item" onclick="selectAddress('{{ $addr->name }}', '{{ $addr->phone }}', '{{ $addr->address }}')">
                    <div class="addr-name">{{ $addr->name }} | {{ $addr->phone }}</div>
                    <div class="addr-text">{{ $addr->address }}</div>
                    @if($addr->is_default) <span style="color:#D4AF37; font-size:11px; font-weight:bold;">[Mặc định]</span> @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @include('layouts.navbar.footer')

    <script>
        function selectPayment(element) {
            document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            element.querySelector('input').checked = true;
        }

        // Modal Logic
        const modal = document.getElementById('addressModal');
        function openAddressModal() { if(modal) modal.style.display = 'flex'; }
        function closeAddressModal() { if(modal) modal.style.display = 'none'; }

        // Chọn địa chỉ
        function selectAddress(name, phone, address) {
            document.getElementById('inpName').value = name;
            document.getElementById('inpPhone').value = phone;
            document.getElementById('inpAddress').value = address;
            closeAddressModal();
        }

        // Đóng modal khi click ra ngoài
        window.onclick = function(event) {
            if (event.target == modal) closeAddressModal();
        }

        // Hàm submit form an toàn
        function submitCheckout() {
            // Kiểm tra sơ bộ
            const name = document.getElementById('inpName').value;
            const phone = document.getElementById('inpPhone').value;
            const address = document.getElementById('inpAddress').value;

            if(!name || !phone || !address) {
                alert('Vui lòng điền đầy đủ thông tin giao hàng!');
                return;
            }

            document.getElementById('checkoutForm').submit();
        }
    </script>
</body>
</html>