<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng #{{ $order->id }} | Predator Group</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
    
    <style>
       
        .container-detail { max-width: 1000px; margin: 120px auto 60px; padding: 0 20px; }
        
        /* Card */
        .order-card { background: #1E1E1E; border: 1px solid #333; border-radius: 8px; padding: 30px; margin-bottom: 30px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .order-id { font-family: 'Playfair Display', serif; font-size: 24px; color: #D4AF37; margin: 0; }
        .order-date { color: #888; font-size: 14px; }
        .btn-back { color: #ccc; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .btn-back:hover { color: #D4AF37; }

        /* Tracking Timeline */
        .tracking-wrap { padding: 20px 0; margin-bottom: 40px; }
        .step-wizard { display: flex; justify-content: space-between; position: relative; }
        .step-wizard::before { content: ''; position: absolute; top: 15px; left: 0; width: 100%; height: 2px; background: #333; z-index: 0; }
        .step-item { position: relative; z-index: 1; text-align: center; flex: 1; }
        .step-circle { width: 32px; height: 32px; background: #333; border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; color: #888; font-size: 14px; border: 2px solid #333; transition: 0.3s; }
        .step-text { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; }
        
        /* Active State */
        .step-item.active .step-circle { background: #D4AF37; border-color: #D4AF37; color: #000; }
        .step-item.active .step-text { color: #D4AF37; }
        /* Completed State (đã qua) */
        .step-item.completed .step-circle { background: #1E1E1E; border-color: #D4AF37; color: #D4AF37; }

        /* Info Grid */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px; }
        .info-box h4 { color: #fff; font-size: 16px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        .info-text { color: #aaa; font-size: 14px; line-height: 1.6; }
        .info-text strong { color: #fff; }

        /* Product List */
        .product-list { width: 100%; border-collapse: collapse; }
        .product-list th { text-align: left; padding: 15px; border-bottom: 1px solid #444; color: #888; font-size: 12px; text-transform: uppercase; }
        .product-list td { padding: 20px 15px; border-bottom: 1px solid #333; vertical-align: middle; }
        .prod-img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #444; margin-right: 15px; }
        .prod-info { display: flex; align-items: center; }
        .prod-name { color: #fff; font-weight: 600; font-size: 14px; display: block; }
        .prod-meta { color: #888; font-size: 12px; }

        /* Total */
        .total-section { display: flex; justify-content: flex-end; padding-top: 20px; }
        .total-box { width: 300px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #aaa; }
        .total-row.final { border-top: 1px solid #444; padding-top: 15px; margin-top: 15px; color: #D4AF37; font-size: 18px; font-weight: 700; }
    </style>
</head>
<body>
    @include('layouts.navbar.header')

    <div class="container-detail">
        <a href="{{ route('profile.index') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Quay lại hồ sơ</a>
        <br><br>

        <div class="order-card">
            <div class="section-header">
                <div>
                    <h1 class="order-id">Đơn hàng #{{ $order->id }}</h1>
                    <span class="order-date">Ngày đặt: {{ $order->created_at->format('H:i d/m/Y') }}</span>
                </div>
                <span style="padding: 6px 15px; background: rgba(212, 175, 55, 0.1); color: #D4AF37; border: 1px solid #D4AF37; border-radius: 4px; font-size: 13px; font-weight: bold; text-transform: uppercase;">
                    {{ $order->status }}
                </span>
            </div>

            <div class="tracking-wrap">
                <div class="step-wizard">
                    {{-- Logic hiển thị trạng thái active dựa trên status đơn hàng --}}
                    @php
                        $status = $order->status; // pending, processing, shipping, completed, cancelled
                        $steps = ['pending', 'processing', 'shipping', 'completed'];
                        $labels = ['Chờ Duyệt', 'Đang Xử Lý', 'Đang Giao', 'Hoàn Thành'];
                        $currentIndex = array_search($status, $steps);
                        if ($status == 'cancelled') $currentIndex = -1; // Đã hủy thì không active cái nào hoặc xử lý riêng
                    @endphp

                    @foreach($steps as $index => $step)
                        <div class="step-item {{ $index <= $currentIndex ? ($index == $currentIndex ? 'active' : 'completed') : '' }}">
                            <div class="step-circle">
                                @if($index < $currentIndex) <i class="fa-solid fa-check"></i> 
                                @else {{ $index + 1 }} @endif
                            </div>
                            <div class="step-text">{{ $labels[$index] }}</div>
                        </div>
                    @endforeach
                </div>
                @if($status == 'cancelled')
                    <div style="text-align: center; color: #ef4444; margin-top: 20px; font-weight: bold;">ĐƠN HÀNG ĐÃ BỊ HỦY</div>
                @endif
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <h4>Địa chỉ nhận hàng</h4>
                    <div class="info-text">
                        <strong>{{ $order->name ?? $order->user->name }}</strong><br>
                        {{ $order->phone ?? $order->user->phone }}<br>
                        {{ $order->address ?? $order->user->address }}
                    </div>
                </div>
                <div class="info-box">
                    <h4>Thông tin thanh toán</h4>
                    <div class="info-text">
                        Phương thức: <strong>{{ strtoupper($order->payment_method) }}</strong><br>
                        Trạng thái: {{ $order->status == 'completed' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </div>
                </div>
            </div>

            <table class="product-list">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>SL</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="prod-info">
                                <img src="{{ $item->product ? asset('storage/'.$item->product->hinh_anh) : 'https://placehold.co/60' }}" class="prod-img">
                                <div>
                                    <span class="prod-name">{{ $item->product_name ?? 'Sản phẩm' }}</span>
                                    {{-- Nếu có size/color lưu trong item thì hiện ở đây --}}
                                </div>
                            </div>
                        </td>
                        <td>{{ number_format($item->price) }}₫</td>
                        <td>x{{ $item->quantity }}</td>
                        <td style="text-align: right; color: #fff;">{{ number_format($item->price * $item->quantity) }}₫</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-box">
                    <div class="total-row">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->total_price) }}₫</span>
                    </div>
                    <div class="total-row">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="total-row final">
                        <span>TỔNG CỘNG:</span>
                        <span>{{ number_format($order->total_price) }}₫</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('layouts.navbar.footer')
</body>
</html>