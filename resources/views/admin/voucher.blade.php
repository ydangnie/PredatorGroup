<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Mã Giảm Giá</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    {{-- Tận dụng CSS của banner --}}
    @vite(['resources/css/admin/banner.css'])
</head>
<body>
    @include('admin.nav')
    
    <div class="banner-container">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-box">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-ticket-alt"></i> Danh sách Mã Giảm Giá</h5>
                
                @if(isset($voucherEdit))
                <a href="{{ route('admin.voucher.index') }}" class="btn-action btn-add-new">
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
                            <th>Mã Code</th>
                            <th>Loại & Giá trị</th>
                            <th>Số lượng</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th width="15%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <span style="font-weight: 700; color: #f4f4f5; background: #27272a; padding: 4px 8px; border-radius: 4px;">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td>
                                @if($item->type == 'percent')
                                    <span style="color: #fbbf24;">Giảm {{ number_format($item->value) }}%</span>
                                @else
                                    <span style="color: #34d399;">Giảm {{ number_format($item->value, 0, ',', '.') }} đ</span>
                                @endif
                            </td>
                            <td>{{ number_format($item->quantity) }}</td>
                            <td style="font-size: 0.85rem; color: #a1a1aa;">
                                @if($item->start_date)
                                    <div>BĐ: {{ $item->start_date->format('d/m/Y') }}</div>
                                @endif
                                @if($item->end_date)
                                    <div>KT: {{ $item->end_date->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td>
                                @if($item->status)
                                    <span style="color: #4ade80;">Hoạt động</span>
                                @else
                                    <span style="color: #ef4444;">Đã khóa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.voucher.edit', $item->id) }}" class="btn-action btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.voucher.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa mã này?')" title="Xóa">
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

    <div id="voucherModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box" style="max-width: 600px;">
            <div class="card-header-custom {{ isset($voucherEdit) ? 'edit-mode' : '' }}">
                <h5>
                    <i class="fas {{ isset($voucherEdit) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ isset($voucherEdit) ? 'Cập Nhật Mã Giảm Giá' : 'Thêm Mã Mới' }}
                </h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="card-body-custom">
                <form action="{{ isset($voucherEdit) ? route('admin.voucher.update', $voucherEdit->id) : route('admin.voucher.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($voucherEdit))
                        @method('PUT')
                    @endif

                    <div class="row" style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Mã Code <span style="color:red">*</span></label>
                            <input type="text" name="code" class="form-input" required style="text-transform: uppercase;"
                                   value="{{ isset($voucherEdit) ? $voucherEdit->code : old('code') }}"
                                   placeholder="VD: SALE2024">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Số lượng <span style="color:red">*</span></label>
                            <input type="number" name="quantity" class="form-input" required min="1"
                                   value="{{ isset($voucherEdit) ? $voucherEdit->quantity : old('quantity') }}">
                        </div>
                    </div>

                    <div class="row" style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Loại giảm giá</label>
                            <select name="type" class="form-input">
                                <option value="fixed" {{ (isset($voucherEdit) && $voucherEdit->type == 'fixed') ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                                <option value="percent" {{ (isset($voucherEdit) && $voucherEdit->type == 'percent') ? 'selected' : '' }}>Phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Giá trị giảm <span style="color:red">*</span></label>
                            <input type="number" name="value" class="form-input" required min="0" step="0.01"
                                   value="{{ isset($voucherEdit) ? $voucherEdit->value : old('value') }}"
                                   placeholder="VD: 50000 hoặc 10">
                        </div>
                    </div>

                    <div class="row" style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" class="form-input"
                                   value="{{ isset($voucherEdit) && $voucherEdit->start_date ? $voucherEdit->start_date->format('Y-m-d\TH:i') : old('start_date') }}">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" class="form-input"
                                   value="{{ isset($voucherEdit) && $voucherEdit->end_date ? $voucherEdit->end_date->format('Y-m-d\TH:i') : old('end_date') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-input">
                            <option value="1" {{ (isset($voucherEdit) && $voucherEdit->status == 1) ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ (isset($voucherEdit) && $voucherEdit->status == 0) ? 'selected' : '' }}>Tạm khóa</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn-action btn-save" style="flex: 1;">
                            {{ isset($voucherEdit) ? 'Lưu Thay Đổi' : 'Thêm Mới' }}
                        </button>
                        
                        @if(isset($voucherEdit))
                        <a href="{{ route('admin.voucher.index') }}" class="btn-action btn-back" style="flex: 1; margin:0">Hủy bỏ</a>
                        @else
                        <button type="button" class="btn-action btn-back" onclick="closeModal()" style="flex: 1; margin:0">Hủy</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('voucherModal');

        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            @if(!isset($voucherEdit))
             modal.style.display = 'none';
             document.body.style.overflow = 'auto';
            @endif
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            @if(isset($voucherEdit) || $errors->any())
            openModal();
            @endif
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                @if(!isset($voucherEdit))
                closeModal();
                @endif
            }
        }
    </script>
</body>
</html>