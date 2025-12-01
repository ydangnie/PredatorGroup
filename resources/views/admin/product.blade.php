<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Sử dụng CSS chung của Admin --}}
    @vite(['resources/css/admin/banner.css'])
    
    <style>
        /* CSS riêng cho phần variants */
        .variant-item {
            background: #27272a;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            border: 1px solid #3f3f46;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .variant-item input {
            background: #18181b;
            border: 1px solid #52525b;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            width: 100%;
            font-size: 0.9rem;
        }
        .variant-item input::placeholder {
            color: #71717a;
        }
        .btn-remove-variant {
            color: #ef4444;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0 5px;
        }
        .btn-remove-variant:hover {
            color: #dc2626;
        }
        
        /* CSS cho radio button giới tính */
        .gender-option {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            color: #d4d4d8;
            font-weight: normal;
        }
        .gender-option input[type="radio"] {
            accent-color: #3b82f6;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    @include('admin.nav')
    
    <div class="banner-container">
        {{-- Thông báo thành công/lỗi --}}
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-bottom: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card-box">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-box"></i> Danh sách Sản phẩm</h5>
                
                @if(isset($productEdit))
                    {{-- Nút Quay lại nếu đang ở chế độ Edit --}}
                    <a href="{{ route('admin.product.index') }}" class="btn-action btn-add-new">
                        <i class="fas fa-plus-circle"></i> Thêm Mới
                    </a>
                @else
                    {{-- Nút Mở Modal thêm mới --}}
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
                            <th width="10%">Hình ảnh</th>
                            <th width="25%">Tên sản phẩm</th>
                            <th width="10%">Giới tính</th>
                            <th width="15%">Giá</th>
                            <th width="15%">Chi tiết</th>
                            <th width="20%">Biến thể (Size/Màu)</th>
                            <th width="10%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $key => $item)
                        <tr>
                            {{-- Sử dụng bộ đếm STT đơn giản --}}
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="thumb-box" style="width: 60px; height: 60px;">
                                    <img src="{{ asset('storage/'.$item->hinh_anh) }}" class="thumb-img" style="object-fit: cover; width: 100%; height: 100%; border-radius: 4px;" alt="Ảnh SP">
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #f4f4f5; font-size: 0.95rem;">{{ $item->tensp }}</div>
                                @if($item->sku)
                                    <div style="font-size: 0.8rem; color: #a1a1aa; margin-top: 2px;">SKU: {{ $item->sku }}</div>
                                @endif
                            </td>
                            <td>
                                @if($item->gender == 'male')
                                    <span class="badge bg-primary">Nam</span>
                                @elseif($item->gender == 'female')
                                    <span class="badge bg-danger">Nữ</span>
                                @else
                                    <span class="badge bg-info text-dark">Unisex</span>
                                @endif
                            </td>
                            <td>
                                <div style="color: #34d399; font-weight: bold;">{{ number_format($item->gia, 0, ',', '.') }} đ</div>
                            </td>
                            <td style="font-size: 0.85rem; color: #d4d4d8;">
                                <div><i class="fas fa-folder" style="width: 16px;"></i> {{ $item->category->ten_danhmuc ?? '---' }}</div>
                                <div style="margin-top: 4px;"><i class="fas fa-tag" style="width: 16px;"></i> {{ $item->brand->ten_thuonghieu ?? '---' }}</div>
                            </td>
                            <td>
                                @if($item->variants->count() > 0)
                                    <div style="max-height: 80px; overflow-y: auto;">
                                        @foreach($item->variants as $variant)
                                            <div style="font-size: 0.8rem; color:#a1a1aa; margin-bottom: 2px; white-space: nowrap;">
                                                • <b>{{ $variant->size }}</b> - {{ $variant->color }} 
                                                <span style="color: #71717a;">(SL: {{ $variant->stock }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="font-size: 0.8rem; color: #71717a;">Chưa có biến thể</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.product.edit', $item->id) }}" class="btn-action btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.product.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa sản phẩm này và toàn bộ biến thể?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- NẾU KHÔNG CÓ SẢN PHẨM --}}
                @if($products->isEmpty())
                <div style="padding: 40px; text-align: center; color: #8898aa;">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px; display:block"></i>
                    Chưa có sản phẩm nào. Hãy nhấn "Thêm Mới".
                </div>
                @endif
                
                {{-- ĐÃ BỎ THANH PHÂN TRANG TẠI ĐÂY --}}

            </div>
        </div>
    </div>

    <div id="productModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box" style="max-width: 900px; width: 95%;">
            <div class="card-header-custom {{ isset($productEdit) ? 'edit-mode' : '' }}">
                <h5>
                    <i class="fas {{ isset($productEdit) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ isset($productEdit) ? 'Cập Nhật Sản Phẩm' : 'Thêm Sản Phẩm Mới' }}
                </h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="card-body-custom">
                <form action="{{ isset($productEdit) ? route('admin.product.update', $productEdit->id) : route('admin.product.store') }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($productEdit)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6" style="border-right: 1px solid #3f3f46; padding-right: 20px;">
                            
                            {{-- Tên sản phẩm --}}
                            <div class="form-group">
                                <label class="form-label">Tên sản phẩm <span style="color:red">*</span></label>
                                <input type="text" name="tensp" class="form-input" required 
                                       value="{{ isset($productEdit) ? $productEdit->tensp : old('tensp') }}"
                                       placeholder="Nhập tên sản phẩm...">
                            </div>

                            {{-- Giới tính --}}
                            <div class="form-group">
                                <label class="form-label">Giới tính <span style="color:red">*</span></label>
                                <div style="display: flex; gap: 20px; padding-top: 5px;">
                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="male" 
                                            {{ (isset($productEdit) && $productEdit->gender == 'male') ? 'checked' : '' }} required> 
                                        Nam
                                    </label>
                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="female" 
                                            {{ (isset($productEdit) && $productEdit->gender == 'female') ? 'checked' : '' }}> 
                                        Nữ
                                    </label>
                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="unisex" 
                                            {{ (!isset($productEdit) || (isset($productEdit) && $productEdit->gender == 'unisex')) ? 'checked' : '' }}> 
                                        Unisex
                                    </label>
                                </div>
                            </div>

                            {{-- Giá & SKU --}}
                            <div class="row" style="display: flex; gap: 15px;">
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Giá bán (VNĐ) <span style="color:red">*</span></label>
                                    <input type="number" name="gia" class="form-input" required min="0" 
                                           value="{{ isset($productEdit) ? $productEdit->gia : old('gia') }}">
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Mã SKU</label>
                                    <input type="text" name="sku" class="form-input" 
                                           value="{{ isset($productEdit) ? $productEdit->sku : old('sku') }}"
                                           placeholder="VD: SP001">
                                </div>
                            </div>

                            {{-- Danh mục & Thương hiệu --}}
                            <div class="row" style="display: flex; gap: 15px;">
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Danh mục</label>
                                    <select name="category_id" class="form-input">
                                        <option value="">-- Chọn --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" 
                                                {{ (isset($productEdit) && $productEdit->category_id == $cat->id) ? 'selected' : '' }}>
                                                {{ $cat->ten_danhmuc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Thương hiệu</label>
                                    <select name="brand_id" class="form-input">
                                        <option value="">-- Chọn --</option>
                                        @foreach($brands as $br)
                                            <option value="{{ $br->id }}" 
                                                {{ (isset($productEdit) && $productEdit->brand_id == $br->id) ? 'selected' : '' }}>
                                                {{ $br->ten_thuonghieu }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Hình ảnh --}}
                            <div class="form-group">
                                <label class="form-label">Hình ảnh chính <span style="color:red">*</span></label>
                                <input type="file" name="hinh_anh" class="form-input" onchange="previewFile(this)" {{ isset($productEdit) ? '' : 'required' }}>
                                
                                <div class="preview-area" style="margin-top: 10px;">
                                    <img id="preview" src="{{ isset($productEdit) && $productEdit->hinh_anh ? asset('storage/'.$productEdit->hinh_anh) : '#' }}" 
                                         style="display: {{ isset($productEdit) && $productEdit->hinh_anh ? 'block' : 'none' }}; max-width: 150px; max-height: 150px; border-radius: 6px; border: 1px solid #52525b;">
                                    @if(!isset($productEdit))
                                        <p id="placeholder-text" style="color: #71717a; font-size: 0.9rem; margin-top:5px;">Chưa chọn ảnh</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" style="padding-left: 20px;">
                            
                            {{-- Quản lý Biến thể --}}
                            <div class="form-group">
                                <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                                    <span>Biến thể (Size / Màu / Tồn kho)</span>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="addVariant()" style="font-size: 0.8rem; padding: 2px 8px;">
                                        <i class="fas fa-plus"></i> Thêm dòng
                                    </button>
                                </label>
                                
                                <div id="variant-container" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                                    {{-- Nếu đang Edit thì load variants cũ --}}
                                    @if(isset($productEdit) && $productEdit->variants->count() > 0)
                                        @foreach($productEdit->variants as $index => $v)
                                        <div class="variant-item">
                                            <input type="text" name="variants[{{$index}}][size]" placeholder="Size (VD: L)" value="{{ $v->size }}" style="flex: 1;">
                                            <input type="text" name="variants[{{$index}}][color]" placeholder="Màu (VD: Đen)" value="{{ $v->color }}" style="flex: 1;">
                                            <input type="number" name="variants[{{$index}}][stock]" placeholder="SL" value="{{ $v->stock }}" style="width: 70px;">
                                            <i class="fas fa-times btn-remove-variant" onclick="this.parentElement.remove()" title="Xóa dòng"></i>
                                        </div>
                                        @endforeach
                                    @else
                                        {{-- Dòng mặc định cho Thêm mới --}}
                                        <div class="variant-item">
                                            <input type="text" name="variants[0][size]" placeholder="Size (VD: L)" style="flex: 1;">
                                            <input type="text" name="variants[0][color]" placeholder="Màu (VD: Đen)" style="flex: 1;">
                                            <input type="number" name="variants[0][stock]" placeholder="SL" value="0" style="width: 70px;">
                                            <i class="fas fa-times btn-remove-variant" onclick="this.parentElement.remove()" title="Xóa dòng"></i>
                                        </div>
                                    @endif
                                </div>
                                <small style="color: #71717a; font-style: italic;">Để trống Size hoặc Màu nếu không áp dụng.</small>
                            </div>

                            {{-- Mô tả --}}
                            <div class="form-group mt-3">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea name="mota" class="form-input" rows="8" placeholder="Nhập mô tả sản phẩm...">{{ isset($productEdit) ? $productEdit->mota : old('mota') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Nút Submit --}}
                    <div style="display: flex; gap: 10px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #3f3f46;">
                        <button type="submit" class="btn-action btn-save" style="flex: 1; height: 45px; font-size: 1rem;">
                            {{ isset($productEdit) ? 'Lưu Thay Đổi' : 'Thêm Sản Phẩm' }}
                        </button>
                        
                        @if(isset($productEdit))
                            <a href="{{ route('admin.product.index') }}" class="btn-action btn-back" style="flex: 1; margin:0; height: 45px; line-height: 45px; text-align: center; text-decoration: none;">Hủy bỏ</a>
                        @else
                            <button type="button" class="btn-action btn-back" onclick="closeModal()" style="flex: 1; margin:0; height: 45px;">Hủy</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script xử lý --}}
    <script>
        const modal = document.getElementById('productModal');
        // Tính chỉ số index tiếp theo cho variant để tránh trùng name
        let variantIndex = {{ isset($productEdit) ? $productEdit->variants->count() : 1 }};

        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            // Chỉ đóng modal JS nếu không phải đang ở trang Edit (để tránh mất dữ liệu form khi user bấm nhầm)
            @if(!isset($productEdit))
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            @endif
        }

        // Preview ảnh khi chọn file
        function previewFile(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('preview');
                    var placeholder = document.getElementById('placeholder-text');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        // Thêm dòng biến thể mới
        function addVariant() {
            const container = document.getElementById('variant-container');
            const html = `
                <div class="variant-item">
                    <input type="text" name="variants[${variantIndex}][size]" placeholder="Size (VD: XL)" style="flex: 1;">
                    <input type="text" name="variants[${variantIndex}][color]" placeholder="Màu (VD: Trắng)" style="flex: 1;">
                    <input type="number" name="variants[${variantIndex}][stock]" placeholder="SL" value="0" style="width: 70px;">
                    <i class="fas fa-times btn-remove-variant" onclick="this.parentElement.remove()" title="Xóa dòng"></i>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            variantIndex++;
            
            // Auto scroll xuống dưới cùng
            container.scrollTop = container.scrollHeight;
        }

        // Tự động mở modal nếu đang Edit hoặc có lỗi Validate
        document.addEventListener("DOMContentLoaded", function() {
            @if(isset($productEdit) || $errors->any())
                openModal();
            @endif
        });

        // Click ra ngoài modal để đóng (chỉ áp dụng khi Thêm mới)
        window.onclick = function(event) {
            if (event.target == modal) {
                @if(!isset($productEdit))
                    closeModal();
                @endif
            }
        }
    </script>
</body>
</html>