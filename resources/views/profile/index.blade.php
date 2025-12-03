<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản | Predator Group</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/layout/main.css'])
    
    <style>
        /* --- CSS CHUNG --- */
        body { background-color: #121212; color: #e0e0e0; font-family: 'Inter', sans-serif; }
        
        /* GRID CONTAINER CỐ ĐỊNH */
        .profile-container { 
            max-width: 1200px; 
            margin: 120px auto 60px; 
            padding: 0 20px; 
            display: grid; 
            grid-template-columns: 280px 1fr; 
            gap: 30px;
            /* Chiều cao cố định cho toàn bộ khu vực Profile */
            height: 750px; 
        }
        
        /* SIDEBAR - Luôn cao bằng Container */
        .pro-sidebar { 
            background: #1E1E1E; 
            border-radius: 8px; 
            border: 1px solid #333; 
            overflow: hidden; 
            height: 100%; /* Chiếm hết chiều cao cha */
            display: flex;
            flex-direction: column;
        }
        
        /* CONTENT - Cố định & Cuộn bên trong */
        .pro-content { 
            background: #1E1E1E; 
            border-radius: 8px; 
            border: 1px solid #333; 
            padding: 40px; 
            height: 100%; /* Chiếm hết chiều cao cha */
            overflow-y: auto; /* QUAN TRỌNG: Cuộn nội dung nếu dài quá */
            position: relative;
        }

        /* TÙY CHỈNH THANH CUỘN (SCROLLBAR) CHO ĐẸP */
        .pro-content::-webkit-scrollbar { width: 6px; }
        .pro-content::-webkit-scrollbar-track { background: #1a1a1a; border-radius: 4px; }
        .pro-content::-webkit-scrollbar-thumb { background: #444; border-radius: 4px; }
        .pro-content::-webkit-scrollbar-thumb:hover { background: #D4AF37; }

        /* Các phần tử khác giữ nguyên style cũ */
        .user-box { padding: 30px 20px; text-align: center; border-bottom: 1px solid #333; flex-shrink: 0; }
        .avatar-img { width: 100px; height: 100px; border-radius: 50%; border: 2px solid #D4AF37; object-fit: cover; margin-bottom: 10px; }
        .menu-list { list-style: none; padding: 0; margin: 0; flex-grow: 1; overflow-y: auto; }
        .menu-item { padding: 18px 25px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 12px; color: #aaa; border-left: 3px solid transparent; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .menu-item:hover, .menu-item.active { background: #252525; color: #D4AF37; border-left-color: #D4AF37; }

        .tab-pane { display: none; animation: fadeIn 0.3s ease; }
        .tab-pane.active { display: block; }
        
        .form-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 25px; position: sticky; top: -40px; background: #1E1E1E; z-index: 10; padding-top: 10px; }
        .form-title { font-family: 'Playfair Display', serif; font-size: 24px; color: #fff; margin: 0; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .form-input { width: 100%; background: #121212; border: 1px solid #333; color: #fff; padding: 12px 15px; border-radius: 4px; font-size: 14px; transition: 0.3s; }
        .form-input:focus { border-color: #D4AF37; outline: none; }
        .form-input:disabled { background: transparent; border-color: transparent; color: #ccc; padding-left: 0; cursor: default; }
        
        /* Radio & Buttons */
        .radio-group { display: flex; gap: 25px; margin-top: 10px; }
        .custom-radio { display: flex; align-items: center; gap: 10px; cursor: pointer; position: relative; font-size: 14px; color: #ccc; }
        .custom-radio input { position: absolute; opacity: 0; cursor: pointer; }
        .checkmark { height: 18px; width: 18px; background-color: #121212; border: 1px solid #444; border-radius: 50%; position: relative; transition: 0.3s; }
        .custom-radio input:checked ~ .checkmark { background-color: #D4AF37; border-color: #D4AF37; box-shadow: 0 0 5px rgba(212, 175, 55, 0.4); }
        .checkmark:after { content: ""; position: absolute; display: none; left: 5px; top: 5px; width: 6px; height: 6px; border-radius: 50%; background: #000; }
        .custom-radio input:checked ~ .checkmark:after { display: block; }
        .custom-radio input:disabled:checked ~ .checkmark { opacity: 0.8; cursor: default; }

        .btn-edit { background: transparent; border: 1px solid #D4AF37; color: #D4AF37; padding: 6px 15px; font-size: 12px; border-radius: 4px; cursor: pointer; transition: 0.3s; font-weight: 600; text-transform: uppercase; display: flex; align-items: center; gap: 8px; }
        .btn-edit:hover { background: #D4AF37; color: #000; }
        .btn-save { background: #D4AF37; color: #000; border: none; padding: 10px 25px; font-weight: 700; border-radius: 4px; cursor: pointer; text-transform: uppercase; }
        .btn-cancel { background: transparent; border: 1px solid #444; color: #ccc; padding: 10px 25px; border-radius: 4px; cursor: pointer; font-weight: 600; }
        .action-buttons { display: none; gap: 10px; justify-content: flex-end; margin-top: 30px; border-top: 1px solid #333; padding-top: 20px; }
        
        /* Address List */
        .address-item { background: #121212; border: 1px solid #333; padding: 15px; border-radius: 6px; margin-bottom: 15px; position: relative; }
        .address-item.default { border-color: #D4AF37; }
        .badge-default { position: absolute; top: 10px; right: 10px; color: #D4AF37; font-size: 11px; border: 1px solid #D4AF37; padding: 2px 6px; border-radius: 3px; font-weight: bold; }
        .btn-delete { color: #ff4444; background: none; border: none; cursor: pointer; font-size: 12px; text-decoration: underline; padding: 0; }

        /* Table */
        .order-table { width: 100%; border-collapse: collapse; }
        .order-table th { text-align: left; padding: 12px; color: #888; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #444; position: sticky; top: 0; background: #1E1E1E; z-index: 5; }
        .order-table td { padding: 15px 12px; border-bottom: 1px solid #2a2a2a; font-size: 14px; color: #ccc; }
        .status-badge { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .status-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .status-completed { background: rgba(74, 222, 128, 0.1); color: #4ade80; }
        .status-cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        @media (max-width: 992px) { 
            .profile-container { grid-template-columns: 1fr; height: auto; }
            .pro-sidebar { height: auto; margin-bottom: 20px; }
            .pro-content { height: auto; min-height: 500px; overflow: visible; }
        }
    </style>
</head>
<body>
    @include('layouts.navbar.header')

    <div class="profile-container">
        <aside class="pro-sidebar">
            <div class="user-box">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=D4AF37&color=000' }}" class="avatar-img">
                <h3 style="font-weight: 600; color: #fff; font-size: 18px; margin: 0;">{{ $user->name }}</h3>
                <p style="font-size: 12px; color: #666; margin-top: 5px;">{{ $user->role == 'admin' ? 'Quản Trị Viên' : 'Thành Viên' }}</p>
            </div>
            <ul class="menu-list">
                <li class="menu-item active" onclick="switchTab('info', this)">
                    <i class="fa-regular fa-user" style="width: 20px;"></i> Thông tin tài khoản
                </li>
                <li class="menu-item" onclick="switchTab('address', this)">
                    <i class="fa-solid fa-map-location-dot" style="width: 20px;"></i> Sổ địa chỉ
                </li>
                <li class="menu-item" onclick="switchTab('orders', this)">
                    <i class="fa-solid fa-box-open" style="width: 20px;"></i> Lịch sử đơn hàng
                </li>
            </ul>
        </aside>

        <main class="pro-content">
            @if(session('success'))
                <div style="background: rgba(74, 222, 128, 0.1); color: #4ade80; padding: 12px; border: 1px solid #4ade80; border-radius: 4px; margin-bottom: 20px;">
                    <i class="fa-solid fa-check"></i> {{ session('success') }}
                </div>
            @endif

            <div id="tab-info" class="tab-pane active">
                @php
                    $defaultAddress = $addresses->where('is_default', true)->first();
                    $realPhone = $defaultAddress ? $defaultAddress->phone : $user->so_dien_thoai;
                    $realAddress = $defaultAddress ? $defaultAddress->address : $user->dia_chi;
                    $maskedPhone = (strlen($realPhone) > 6) ? substr($realPhone, 0, 3) . '*****' . substr($realPhone, -3) : ($realPhone ?? 'Chưa cập nhật');
                @endphp

                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-header">
                        <h2 class="form-title">Thông Tin Cá Nhân</h2>
                        <button type="button" class="btn-edit" id="btnEdit" onclick="enableEdit()">
                            <i class="fa-regular fa-pen-to-square"></i> Chỉnh Sửa
                        </button>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Họ và Tên</label>
                        <input type="text" name="name" class="form-input editable" value="{{ old('name', $user->name) }}" disabled required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <small style="font-weight: 400;">(Không thể thay đổi)</small></label>
                        <input type="text" class="form-input" value="{{ $user->email }}" disabled style="color:#666">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" id="phoneDisplay" class="form-input editable" value="{{ $maskedPhone }}" disabled>
                            <input type="hidden" id="phoneReal" value="{{ $realPhone }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-input editable" value="{{ old('ngay_sinh', $user->ngay_sinh) }}" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Giới tính</label>
                        <div class="radio-group">
                            <label class="custom-radio">
                                <input type="radio" name="gioi_tinh" value="nam" class="editable-radio" {{ $user->gioi_tinh == 'nam' ? 'checked' : '' }} disabled>
                                <span class="checkmark"></span> Nam
                            </label>
                            <label class="custom-radio">
                                <input type="radio" name="gioi_tinh" value="nu" class="editable-radio" {{ $user->gioi_tinh == 'nu' ? 'checked' : '' }} disabled>
                                <span class="checkmark"></span> Nữ
                            </label>
                            <label class="custom-radio">
                                <input type="radio" name="gioi_tinh" value="khac" class="editable-radio" {{ $user->gioi_tinh == 'khac' ? 'checked' : '' }} disabled>
                                <span class="checkmark"></span> Khác
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ mặc định</label>
                        <input type="text" name="dia_chi" class="form-input editable" value="{{ old('dia_chi', $realAddress) }}" placeholder="Chưa cập nhật" disabled>
                    </div>

                    <div class="form-group" id="avatarUploadGroup" style="display: none; margin-top: 30px; border-top: 1px solid #333; padding-top: 20px;">
                        <label class="form-label">Thay đổi ảnh đại diện</label>
                        <input type="file" name="avatar" class="form-input" style="padding: 10px;">
                    </div>
                    
                    <div id="password-section" style="display: none; margin-top: 30px; border-top: 1px solid #333; padding-top: 20px;">
                        <h3 style="font-size: 14px; color: #D4AF37; margin-bottom: 20px; text-transform: uppercase; font-weight: 700;">Đổi Mật Khẩu</h3>
                        <div class="form-group"><label class="form-label">Mật khẩu hiện tại</label><input type="password" name="current_password" class="form-input editable" disabled></div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group"><label class="form-label">Mật khẩu mới</label><input type="password" name="new_password" class="form-input editable" disabled></div>
                            <div class="form-group"><label class="form-label">Xác nhận mật khẩu</label><input type="password" name="new_password_confirmation" class="form-input editable" disabled></div>
                        </div>
                    </div>

                    <div class="action-buttons" id="actionButtons">
                        <button type="button" class="btn-cancel" onclick="cancelEdit()">Hủy Bỏ</button>
                        <button type="submit" class="btn-save">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>

            <div id="tab-address" class="tab-pane">
                <div class="form-header"><h2 class="form-title">Sổ Địa Chỉ</h2></div>
                <div style="background: #161616; padding: 25px; border-radius: 6px; margin-bottom: 40px; border: 1px dashed #444;">
                    <h4 style="color: #fff; margin: 0 0 20px 0; font-size: 14px; text-transform: uppercase; font-weight: 600;">+ Thêm địa chỉ mới</h4>
                    <form action="{{ route('profile.address.add') }}" method="POST">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                            <input type="text" name="name" class="form-input" style="background: #121212; border-color: #333;" placeholder="Tên người nhận" required>
                            <input type="text" name="phone" class="form-input" style="background: #121212; border-color: #333;" placeholder="Số điện thoại" required>
                        </div>
                        <input type="text" name="address" class="form-input" style="background: #121212; border-color: #333;" placeholder="Địa chỉ chi tiết" required>
                        <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" name="is_default" id="def_addr" style="accent-color: #D4AF37; width: 16px; height: 16px;">
                                <label for="def_addr" style="font-size: 13px; color: #aaa; cursor: pointer;">Đặt làm mặc định</label>
                            </div>
                            <button type="submit" class="btn-save" style="padding: 10px 25px; font-size: 12px;">Lưu</button>
                        </div>
                    </form>
                </div>

                @foreach($addresses as $addr)
                <div class="address-item {{ $addr->is_default ? 'default' : '' }}">
                    @if($addr->is_default) <span class="badge-default">Mặc định</span> @endif
                    <p style="color: #fff; font-weight: 600; margin-bottom: 5px; font-size: 16px;">{{ $addr->name }} <span style="color: #666; font-weight: 400; font-size: 14px;">| {{ $addr->phone }}</span></p>
                    <p style="color: #aaa; font-size: 14px; margin-bottom: 10px;">{{ $addr->address }}</p>
                    <form action="{{ route('profile.address.delete', $addr->id) }}" method="POST" onsubmit="return confirm('Xóa địa chỉ này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">Xóa</button>
                    </form>
                </div>
                @endforeach
            </div>

            <div id="tab-orders" class="tab-pane">
                <div class="form-header"><h2 class="form-title">Lịch Sử Đơn Hàng</h2></div>
                @if(isset($orders) && $orders->count() > 0)
                    <table class="order-table">
                        <thead><tr><th>Mã Đơn</th><th>Ngày Đặt</th><th>Tổng Tiền</th><th>Trạng Thái</th><th style="text-align: right;">Chi tiết</th></tr></thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td style="color: #fff; font-weight: 600;">#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td style="color: #D4AF37; font-weight: 700;">{{ number_format($order->total_price) }}₫</td>
                                <td><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></td>
                                <td style="text-align: right;"><a href="{{ route('profile.order.show', $order->id) }}" style="color: #aaa; font-size: 13px; text-decoration: underline;">Xem</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center; padding: 40px 0;">
                        <p style="color: #666; margin-bottom: 30px;">Bạn chưa có đơn hàng nào.</p>
                        <a href="{{ route('sanpham') }}" class="btn-save" style="text-decoration: none;">Mua Sắm Ngay</a>
                    </div>
                @endif
            </div>
        </main>
    </div>

    @include('layouts.navbar.footer')

    <script>
        function switchTab(tabName, element) {
            document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            document.getElementById('tab-' + tabName).classList.add('active');
            element.classList.add('active');
        }

        function enableEdit() {
            document.querySelectorAll('.editable').forEach(el => {
                el.disabled = false;
                el.style.borderColor = '#333'; el.style.background = '#121212'; el.style.paddingLeft = '15px';
            });
            document.querySelectorAll('.editable-radio').forEach(el => el.disabled = false);

            const phoneDisplay = document.getElementById('phoneDisplay');
            const phoneReal = document.getElementById('phoneReal');
            if(phoneDisplay && phoneReal) {
                phoneDisplay.value = phoneReal.value;
                phoneDisplay.setAttribute('name', 'so_dien_thoai');
            }

            document.getElementById('avatarUploadGroup').style.display = 'block';
            document.getElementById('password-section').style.display = 'block';
            document.getElementById('actionButtons').style.display = 'flex';
            document.getElementById('btnEdit').style.display = 'none';
        }

        function cancelEdit() { location.reload(); }
    </script>
</body>
</html>