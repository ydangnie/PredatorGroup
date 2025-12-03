<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Dùng chung CSS Admin --}}
    @vite(['resources/css/admin/banner.css'])
    
    <style>
        /* Một số style riêng cho bảng user nếu cần */
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .role-admin { background: rgba(74, 222, 128, 0.2); color: #4ade80; }
        .role-user { background: rgba(148, 163, 184, 0.2); color: #94a3b8; }
    </style>
</head>
<body>
    @include('admin.nav')
    
    <div class="banner-container">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif
        
        <div class="card-box">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-users"></i> Danh sách Người Dùng</h5>
                
                @if(isset($userEdit))
                    <a href="{{ route('admin.users.index') }}" class="btn-action btn-add-new">
                        <i class="fas fa-plus-circle"></i> Thêm Mới
                    </a>
                @else
                    <button type="button" class="btn-action btn-add-new" onclick="openModal()">
                        <i class="fas fa-plus-circle"></i> Thêm Mới
                    </button>
                @endif
            </div>

            <div class="card-body-custom p-0">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th width="15%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div style="font-weight: 700; color: #f4f4f5;">{{ $item->name }}</div>
                            </td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone ?? '---' }}</td>
                            <td>
                                @if($item->role == 'admin')
                                    <span class="role-badge role-admin">Admin</span>
                                @else
                                    <span class="role-badge role-user">Khách hàng</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', $item->id) }}" class="btn-action btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa người dùng này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="userModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box" style="max-width: 600px;">
            <div class="card-header-custom {{ isset($userEdit) ? 'edit-mode' : '' }}">
                <h5>
                    <i class="fas {{ isset($userEdit) ? 'fa-user-edit' : 'fa-user-plus' }}"></i>
                    {{ isset($userEdit) ? 'Cập Nhật Người Dùng' : 'Thêm Người Dùng Mới' }}
                </h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="card-body-custom">
                {{-- Hiển thị lỗi trong modal --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($userEdit) ? route('admin.users.update', $userEdit->id) : route('admin.users.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($userEdit)) @method('PUT') @endif

                    <div class="form-group">
                        <label class="form-label">Họ tên <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-input" required 
                               value="{{ isset($userEdit) ? $userEdit->name : old('name') }}" placeholder="Nhập tên...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span style="color:red">*</span></label>
                        <input type="email" name="email" class="form-input" required 
                               value="{{ isset($userEdit) ? $userEdit->email : old('email') }}" placeholder="Nhập email...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Mật khẩu {{ isset($userEdit) ? '(Để trống nếu không đổi)' : '<span style="color:red">*</span>' }}
                        </label>
                        <input type="password" name="password" class="form-input" 
                               {{ isset($userEdit) ? '' : 'required' }} placeholder="******">
                    </div>

                    <div class="row" style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Vai trò</label>
                            <select name="role" class="form-input">
                                <option value="user" {{ (isset($userEdit) && $userEdit->role == 'user') ? 'selected' : '' }}>Khách hàng</option>
                                <option value="admin" {{ (isset($userEdit) && $userEdit->role == 'admin') ? 'selected' : '' }}>Admin (Quản trị)</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-input" 
                                   value="{{ isset($userEdit) ? $userEdit->phone : old('phone') }}" placeholder="09...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="address" class="form-input" 
                               value="{{ isset($userEdit) ? $userEdit->address : old('address') }}" placeholder="Nhập địa chỉ...">
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn-action btn-save" style="flex: 1;">
                            {{ isset($userEdit) ? 'Lưu Thay Đổi' : 'Thêm Mới' }}
                        </button>
                        
                        @if(isset($userEdit))
                            <a href="{{ route('admin.users.index') }}" class="btn-action btn-back" style="flex: 1; margin:0; text-align:center; text-decoration: none; line-height: 40px;">Hủy</a>
                        @else
                            <button type="button" class="btn-action btn-back" onclick="closeModal()" style="flex: 1; margin:0">Hủy</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('userModal');

        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            @if(!isset($userEdit))
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            @endif
        }

        // Tự động mở modal khi sửa hoặc có lỗi validate
        document.addEventListener("DOMContentLoaded", function() {
            @if(isset($userEdit) || $errors->any())
                openModal();
            @endif
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                @if(!isset($userEdit))
                    closeModal();
                @endif
            }
        }
    </script>
</body>
</html>