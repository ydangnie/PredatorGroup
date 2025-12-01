<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Danh Mục</title>
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
                <h5><i class="fas fa-list"></i> Danh sách Danh mục</h5>
                
                @if(isset($categoryEdit))
                <a href="{{ route('admin.category.index') }}" class="btn-action btn-add-new">
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
                            <th>Tên Danh mục</th>
                            <th>Slug (URL)</th>
                            <th>Mô tả</th>
                            <th width="15%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div style="font-weight: 700; color: #f4f4f5; font-size: 1rem;">
                                    {{ $item->ten_danhmuc }}
                                </div>
                            </td>
                            <td>
                                <span style="color: #a1a1aa; font-style: italic;">{{ $item->slug }}</span>
                            </td>
                            <td>
                                <div style="color: #d4d4d8; font-size: 0.9rem;">
                                    {{ Str::limit($item->mota, 50) }}
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.category.edit', $item->id) }}" class="btn-action btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.category.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa danh mục này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($categories->isEmpty())
                <div style="padding: 40px; text-align: center; color: #8898aa;">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px; display:block"></i>
                    Chưa có danh mục nào. Hãy nhấn "Thêm Mới".
                </div>
                @endif
            </div>
        </div>
    </div>

    <div id="categoryModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box">
            <div class="card-header-custom {{ isset($categoryEdit) ? 'edit-mode' : '' }}">
                <h5>
                    <i class="fas {{ isset($categoryEdit) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ isset($categoryEdit) ? 'Cập Nhật Danh Mục' : 'Thêm Danh Mục Mới' }}
                </h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="card-body-custom">
                <form action="{{ isset($categoryEdit) ? route('admin.category.update', $categoryEdit->id) : route('admin.category.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($categoryEdit))
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label class="form-label">Tên Danh mục <span style="color:red">*</span></label>
                        <input type="text" name="ten_danhmuc" class="form-input" required 
                               value="{{ isset($categoryEdit) ? $categoryEdit->ten_danhmuc : old('ten_danhmuc') }}"
                               placeholder="VD: Áo Khoác, Giày Sneaker...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mota" class="form-input" rows="4" placeholder="Nhập mô tả cho danh mục...">{{ isset($categoryEdit) ? $categoryEdit->mota : old('mota') }}</textarea>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn-action btn-save" style="flex: 1;">
                            {{ isset($categoryEdit) ? 'Lưu Thay Đổi' : 'Thêm Mới' }}
                        </button>
                        
                        @if(isset($categoryEdit))
                        <a href="{{ route('admin.category.index') }}" class="btn-action btn-back" style="flex: 1; margin:0">Hủy bỏ</a>
                        @else
                        <button type="button" class="btn-action btn-back" onclick="closeModal()" style="flex: 1; margin:0">Hủy</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('categoryModal');

        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            @if(!isset($categoryEdit))
             modal.style.display = 'none';
             document.body.style.overflow = 'auto';
            @endif
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            @if(isset($categoryEdit) || $errors->any())
            openModal();
            @endif
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                @if(!isset($categoryEdit))
                closeModal();
                @endif
            }
        }
    </script>
</body>
</html>