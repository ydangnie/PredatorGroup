<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Banner</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    @vite(['resources/css/admin/banner.css'])
</head>

<body>
    @include('admin.nav')
    
    <div class="banner-container">
        @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        <div class="card-box">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-list-ul"></i> Danh sách Banner</h5>
                
                @if(isset($bannerEdit))
                    {{-- Nếu đang sửa thì nút này sẽ reset về trang danh sách --}}
                    <a href="{{ route('admin.banner.index') }}" class="btn-action btn-add-new">
                        <i class="fas fa-plus-circle"></i> Thêm Mới
                    </a>
                @else
                    {{-- Nếu đang ở trang chủ thì mở Modal JS --}}
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
                            <th>Tiêu đề</th>
                            <th>Thương hiệu</th>
                            <th>Mô tả</th>
                            <th width="15%">Hình ảnh</th>
                            <th width="15%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div style="font-weight: 700; color: #f4f4f5; font-size: 1rem;">{{ $item->title }}</div>
                                @if($item->link)
                                <div style="margin-top: 4px;">
                                    <a href="{{ $item->link }}" target="_blank" style="color: var(--text-secondary); font-size: 0.85rem; text-decoration: none;">
                                        <i class="fas fa-link"></i> Link
                                    </a>
                                </div>
                                @endif
                            </td>
                            <td>{{ $item->thuonghieu }}</td>
                            <td><div style="color: #8898aa; font-size: 0.9rem;">{{ Str::limit($item->mota, 50) }}</div></td>
                            <td>
                                <div class="thumb-box">
                                    <img src="{{ asset('storage/'.$item->hinhanh) }}" class="thumb-img" alt="Banner">
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.banner.edit', $item->id) }}" class="btn-action btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.banner.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa banner này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($banners->isEmpty())
                <div style="padding: 40px; text-align: center; color: #8898aa;">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px; display:block"></i>
                    Chưa có banner nào. Hãy nhấn "Thêm Mới".
                </div>
                @endif
            </div>
        </div>
    </div>

    <div id="bannerModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box">
            <div class="card-header-custom {{ isset($bannerEdit) ? 'edit-mode' : '' }}">
                <h5>
                    <i class="fas {{ isset($bannerEdit) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ isset($bannerEdit) ? 'Cập Nhật Banner' : 'Thêm Banner Mới' }}
                </h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="card-body-custom">
                <form action="{{ isset($bannerEdit) ? route('admin.banner.update', $bannerEdit->id) : route('admin.banner.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Tiêu đề Banner <span style="color:red">*</span></label>
                        <input type="text" name="title" class="form-input" required
                            value="{{ isset($bannerEdit) ? $bannerEdit->title : old('title') }}"
                            placeholder="Nhập tiêu đề...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Thương hiệu</label>
                        <input type="text" name="thuonghieu" class="form-input"
                            value="{{ isset($bannerEdit) ? $bannerEdit->thuonghieu : old('thuonghieu') }}"
                            placeholder="VD: Nike, Adidas...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea name="mota" class="form-input" rows="3">{{ isset($bannerEdit) ? $bannerEdit->mota : old('mota') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hình ảnh <span style="color:red">*</span></label>
                        <input type="file" name="hinhanh" class="form-input" onchange="previewFile(this)">

                        <div class="preview-area">
                            <img id="preview" src="#" style="display: none; max-width: 100%; max-height: 200px; border-radius: 6px; margin: 0 auto;">

                            @if(isset($bannerEdit) && $bannerEdit->hinhanh)
                            <div id="old-img">
                                <p style="font-size: 12px; color: #888; margin-bottom: 5px;">Ảnh hiện tại:</p>
                                <img src="{{ asset('storage/'.$bannerEdit->hinhanh) }}" style="max-width: 100%; max-height: 200px; border-radius: 6px;">
                            </div>
                            @else
                            <p style="font-size: 13px; color: #52525b; margin:0;" id="placeholder-text">
                                <i class="fas fa-image" style="font-size: 24px; margin-bottom:8px; display:block"></i>
                                Chưa chọn ảnh
                            </p>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn-action btn-save" style="flex: 1;">
                            {{ isset($bannerEdit) ? 'Lưu Thay Đổi' : 'Thêm Mới' }}
                        </button>
                        @if(isset($bannerEdit))
                        <a href="{{ route('admin.banner.index') }}" class="btn-action btn-back" style="flex: 1; margin:0">Hủy bỏ</a>
                        @else
                        <button type="button" class="btn-action btn-back" onclick="closeModal()" style="flex: 1; margin:0">Hủy</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Các biến DOM
        const modal = document.getElementById('bannerModal');

        // Hàm mở modal
        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Chặn cuộn trang chính
        }

        // Hàm đóng modal
        function closeModal() {
            // Nếu đang ở chế độ edit mà đóng thì nên chuyển về index để reset form
            // Nhưng ở đây chỉ ẩn đi cho đơn giản (với nút Thêm Mới)
             modal.style.display = 'none';
             document.body.style.overflow = 'auto';
        }

        // Logic Preview ảnh
        function previewFile(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    var preview = document.getElementById('preview');
                    var oldImg = document.getElementById('old-img');
                    var placeholder = document.getElementById('placeholder-text');

                    preview.src = reader.result;
                    preview.style.display = 'block';

                    if (oldImg) oldImg.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        // Tự động mở Modal nếu có biến $bannerEdit (đang ở chế độ sửa)
        // Hoặc nếu có lỗi validate form (Laravel redirect back with errors)
        document.addEventListener("DOMContentLoaded", function() {
            @if(isset($bannerEdit) || $errors->any())
                openModal();
            @endif
        });

        // Đóng modal khi click ra ngoài vùng content
        window.onclick = function(event) {
            if (event.target == modal) {
                // Chỉ đóng nếu không phải đang Edit (để tránh mất dữ liệu lỡ tay click ra ngoài)
                @if(!isset($bannerEdit))
                    closeModal();
                @endif
            }
        }
    </script>
</body>
</html>