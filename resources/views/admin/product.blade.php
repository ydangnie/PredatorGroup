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

        /* Nút xóa ảnh trong album */
        .btn-delete-img {
            position: absolute; 
            top: -8px; 
            right: -8px; 
            background: #ef4444; 
            color: white; 
            border-radius: 50%; 
            width: 20px; 
            height: 20px; 
            text-align: center; 
            line-height: 20px; 
            font-size: 12px; 
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 10;
        }
        .btn-delete-img:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    @include('admin.nav')
    
    <div class="banner-container">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        
        <div class="card-box">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-box"></i> Danh sách Sản phẩm</h5>
                @if(isset($productEdit))
                    <a href="{{ route('admin.product.index') }}" class="btn-action btn-add-new"><i class="fas fa-plus-circle"></i> Thêm Mới</a>
                @else
                    <button type="button" class="btn-action btn-add-new" onclick="openModal()"><i class="fas fa-plus-circle"></i> Thêm Mới</button>
                @endif
            </div>

            <div class="card-body-custom p-0">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th width="10%">Hình ảnh</th>
                            <th width="25%">Tên sản phẩm</th>
                            <th>Giới tính</th>
                            <th>Giá & Kho</th>
                            <th>Chi tiết</th>
                            <th>Biến thể</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <img src="{{ asset('storage/'.$item->hinh_anh) }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #f4f4f5;">{{ $item->tensp }}</div>
                                @if($item->sku) <small style="color:#aaa">{{ $item->sku }}</small> @endif
                                @if($item->images->count() > 0)
                                    <div style="font-size: 0.75rem; color: #3b82f6; margin-top: 3px;">+{{ $item->images->count() }} ảnh phụ</div>
                                @endif
                            </td>
                            <td>
                                @if($item->gender == 'male') <span class="badge bg-primary">Nam</span>
                                @elseif($item->gender == 'female') <span class="badge bg-danger">Nữ</span>
                                @else <span class="badge bg-secondary">Unisex</span>
                                @endif
                            </td>
                            <td>
                                <div style="color: #34d399; font-weight: bold;">{{ number_format($item->gia, 0, ',', '.') }} đ</div>
                                <div style="font-size: 0.85rem; color: #d4d4d8;">Kho: {{ $item->so_luong }}</div>
                            </td>
                            <td>
                                <small>DM: {{ $item->category->ten_danhmuc ?? '-' }}</small><br>
                                <small>TH: {{ $item->brand->ten_thuonghieu ?? '-' }}</small>
                            </td>
                            <td>
                                <div style="max-height: 60px; overflow-y: auto; font-size: 0.8rem; color: #a1a1aa;">
                                    @foreach($item->variants as $v)
                                        <div>{{ $v->size }} - {{ $v->color }} (SL: {{ $v->stock }})</div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.product.edit', $item->id) }}" class="btn-action btn-edit"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.product.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn-action btn-delete" onclick="return confirm('Xóa sản phẩm này?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($products->isEmpty())
                    <div style="padding: 30px; text-align: center; color: #888;">Chưa có dữ liệu sản phẩm.</div>
                @endif
            </div>
        </div>
    </div>

    <div id="productModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box" style="max-width: 900px; width: 95%; max-height: 90vh; overflow-y: auto;">
            <div class="card-header-custom {{ isset($productEdit) ? 'edit-mode' : '' }}">
                <h5><i class="fas {{ isset($productEdit) ? 'fa-edit' : 'fa-plus' }}"></i> {{ isset($productEdit) ? 'Cập Nhật Sản Phẩm' : 'Thêm Sản Phẩm Mới' }}</h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="card-body-custom">
                @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($productEdit) ? route('admin.product.update', $productEdit->id) : route('admin.product.store') }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($productEdit)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6" style="padding-right: 20px; border-right: 1px solid #3f3f46;">
                            <div class="form-group">
                                <label class="form-label">Tên sản phẩm <span style="color:red">*</span></label>
                                <input type="text" name="tensp" class="form-input" required 
                                       value="{{ isset($productEdit) ? $productEdit->tensp : old('tensp') }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Giới tính <span style="color:red">*</span></label>
                                <div style="display: flex; gap: 20px;">
                                    <label class="gender-option"><input type="radio" name="gender" value="male" {{ (isset($productEdit) && $productEdit->gender == 'male') ? 'checked' : '' }} required> Nam</label>
                                    <label class="gender-option"><input type="radio" name="gender" value="female" {{ (isset($productEdit) && $productEdit->gender == 'female') ? 'checked' : '' }}> Nữ</label>
                                    <label class="gender-option"><input type="radio" name="gender" value="unisex" {{ (!isset($productEdit) || (isset($productEdit) && $productEdit->gender == 'unisex')) ? 'checked' : '' }}> Unisex</label>
                                </div>
                            </div>

                            <div class="row" style="display: flex; gap: 10px;">
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Giá bán <span style="color:red">*</span></label>
                                    <input type="number" name="gia" class="form-input" required 
                                           value="{{ isset($productEdit) ? $productEdit->gia : old('gia') }}">
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Số lượng kho</label>
                                    <input type="number" name="so_luong" class="form-input" min="0"
                                           value="{{ isset($productEdit) ? $productEdit->so_luong : old('so_luong', 0) }}">
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="sku" class="form-input" 
                                           value="{{ isset($productEdit) ? $productEdit->sku : old('sku') }}">
                                </div>
                            </div>

                            <div class="row" style="display: flex; gap: 10px;">
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Danh mục</label>
                                    <select name="category_id" class="form-input">
                                        <option value="">-- Chọn --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ (isset($productEdit) && $productEdit->category_id == $cat->id) ? 'selected' : '' }}>{{ $cat->ten_danhmuc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label class="form-label">Thương hiệu</label>
                                    <select name="brand_id" class="form-input">
                                        <option value="">-- Chọn --</option>
                                        @foreach($brands as $br)
                                            <option value="{{ $br->id }}" {{ (isset($productEdit) && $productEdit->brand_id == $br->id) ? 'selected' : '' }}>{{ $br->ten_thuonghieu }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Hình ảnh chính <span style="color:red">*</span></label>
                                <input type="file" name="hinh_anh" class="form-input" onchange="previewFile(this)" {{ isset($productEdit) ? '' : 'required' }}>
                                <div style="margin-top: 10px;">
                                    <img id="preview" src="{{ isset($productEdit) && $productEdit->hinh_anh ? asset('storage/'.$productEdit->hinh_anh) : '' }}" 
                                         style="max-width: 120px; border-radius: 5px; border: 1px solid #555; display: {{ isset($productEdit) && $productEdit->hinh_anh ? 'block' : 'none' }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Album ảnh phụ</label>
                                <input type="file" name="album[]" class="form-input" multiple onchange="previewAlbum(this)">
                                <div id="album-preview" style="display: flex; gap: 5px; flex-wrap: wrap; margin-top: 5px;"></div>
                                @if(isset($productEdit) && $productEdit->images->count() > 0)
                                    <div style="margin-top: 10px; font-size: 0.85rem; color: #ccc;">Ảnh hiện tại:</div>
                                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 5px;">
                                        @foreach($productEdit->images as $img)
                                            <div style="position: relative; width: 60px; height: 60px;">
                                                <img src="{{ asset('storage/'.$img->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px; border: 1px solid #555;">
                                                <a href="{{ route('admin.product.image.delete', $img->id) }}" 
                                                   onclick="event.preventDefault(); if(confirm('Xóa ảnh này?')) document.getElementById('del-img-{{$img->id}}').submit();"
                                                   class="btn-delete-img">&times;</a>
                                                <form id="del-img-{{$img->id}}" action="{{ route('admin.product.image.delete', $img->id) }}" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6" style="padding-left: 20px;">
                            <div class="form-group">
                                <label class="form-label" style="display:flex; justify-content:space-between;">
                                    Biến thể (Size / Màu / Tồn kho) 
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="addVariant()"><i class="fas fa-plus"></i> Thêm</button>
                                </label>
                                <div id="variant-container" style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                                    @if(isset($productEdit) && $productEdit->variants->count() > 0)
                                        @foreach($productEdit->variants as $index => $v)
                                        <div class="variant-item">
                                            <input type="text" name="variants[{{$index}}][size]" placeholder="Size" value="{{ $v->size }}">
                                            <input type="text" name="variants[{{$index}}][color]" placeholder="Màu" value="{{ $v->color }}">
                                            <input type="number" name="variants[{{$index}}][stock]" placeholder="SL" value="{{ $v->stock }}" style="width: 60px;">
                                            <i class="fas fa-times btn-remove-variant" onclick="this.parentElement.remove()"></i>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="variant-item">
                                            <input type="text" name="variants[0][size]" placeholder="Size">
                                            <input type="text" name="variants[0][color]" placeholder="Màu">
                                            <input type="number" name="variants[0][stock]" placeholder="SL" value="0" style="width: 60px;">
                                            <i class="fas fa-times btn-remove-variant" onclick="this.parentElement.remove()"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea name="mota" class="form-input" rows="8">{{ isset($productEdit) ? $productEdit->mota : old('mota') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 20px; text-align: right; border-top: 1px solid #3f3f46; padding-top: 15px;">
                        @if(isset($productEdit))
                            <a href="{{ route('admin.product.index') }}" class="btn-action btn-back" style="text-decoration: none; margin-right: 10px;">Hủy</a>
                        @else
                            <button type="button" class="btn-action btn-back" onclick="closeModal()" style="margin-right: 10px;">Hủy</button>
                        @endif
                        <button type="submit" class="btn-action btn-save">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('productModal');
        let variantIndex = {{ isset($productEdit) ? $productEdit->variants->count() : 1 }};

        function openModal() { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
        function closeModal() {
            @if(!isset($productEdit)) modal.style.display = 'none'; document.body.style.overflow = 'auto'; @endif
        }
        function previewFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) { document.getElementById('preview').src = e.target.result; document.getElementById('preview').style.display = 'block'; }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function previewAlbum(input) {
            var container = document.getElementById('album-preview'); container.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = document.createElement('img'); img.src = e.target.result;
                        img.style.width = '50px'; img.style.height = '50px'; img.style.objectFit = 'cover'; img.style.borderRadius = '3px';
                        container.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
        function addVariant() {
            const html = `<div class="variant-item">
                <input type="text" name="variants[${variantIndex}][size]" placeholder="Size">
                <input type="text" name="variants[${variantIndex}][color]" placeholder="Màu">
                <input type="number" name="variants[${variantIndex}][stock]" placeholder="SL" value="0" style="width: 60px;">
                <i class="fas fa-times btn-remove-variant" onclick="this.parentElement.remove()"></i></div>`;
            document.getElementById('variant-container').insertAdjacentHTML('beforeend', html);
            variantIndex++;
        }
        document.addEventListener("DOMContentLoaded", function() {
            @if(isset($productEdit) || $errors->any()) openModal(); @endif
        });
        window.onclick = function(event) { if (event.target == modal) { @if(!isset($productEdit)) closeModal(); @endif } }
    </script>
</body>
</html>