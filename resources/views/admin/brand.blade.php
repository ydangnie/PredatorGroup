<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thương Hiệu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    {{-- Tận dụng luôn CSS của banner vì cấu trúc giống hệt --}}
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
                <h5><i class="fas fa-tags"></i> Danh sách Thương hiệu</h5>
                
                {{-- Nút mở Modal thêm mới --}}
                <button type="button" class="btn-action btn-add-new" onclick="openModal()">
                    <i class="fas fa-plus-circle"></i> Thêm Mới
                </button>
            </div>

            <div class="card-body-custom p-0">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Tên Thương hiệu</th>
                            <th width="15%">Logo</th>
                            <th width="15%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div style="font-weight: 700; color: #f4f4f5; font-size: 1rem;">{{ $item->ten_thuonghieu }}</div>
                            </td>
                            <td>
                                <div class="thumb-box" id="brandlogo">
                                    <img src="{{ asset('storage/'.$item->logo) }}" class="thumb-img" alt="Logo">
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.brand.edit', $item->id) }}" class="btn-action btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.brand.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa thương hiệu này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($brands->isEmpty())
                <div style="padding: 40px; text-align: center; color: #8898aa;">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px; display:block"></i>
                    Chưa có thương hiệu nào. Hãy nhấn "Thêm Mới".
                </div>
                @endif
            </div>
        </div>
    </div>

    <div id="brandModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box">
            <div class="card-header-custom">
                <h5><i class="fas fa-plus-circle"></i> Thêm Thương Hiệu Mới</h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="card-body-custom">
                <form action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Tên Thương hiệu <span style="color:red">*</span></label>
                        <input type="text" name="ten_thuonghieu" class="form-input" required placeholder="Nhập tên thương hiệu...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Logo <span style="color:red">*</span></label>
                        <input type="file" name="logo" class="form-input" onchange="previewFile(this)" required>

                        <div class="preview-area">
                            <img id="preview" src="#" style="display: none; max-width: 100%; max-height: 200px; border-radius: 6px; margin: 0 auto;">
                            <p style="font-size: 13px; color: #52525b; margin:0;" id="placeholder-text">
                                <i class="fas fa-image" style="font-size: 24px; margin-bottom:8px; display:block"></i>
                                Chưa chọn ảnh
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn-action btn-save" style="flex: 1;">Thêm Mới</button>
                        <button type="button" class="btn-action btn-back" onclick="closeModal()" style="flex: 1; margin:0">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('brandModal');

        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
             modal.style.display = 'none';
             document.body.style.overflow = 'auto';
        }

        function previewFile(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    var preview = document.getElementById('preview');
                    var placeholder = document.getElementById('placeholder-text');
                    preview.src = reader.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }
        
        // Hiển thị lại modal nếu có lỗi validate
        @if($errors->any())
            openModal();
        @endif

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>