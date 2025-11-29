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

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card-box">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-list-ul"></i> Danh sách Banner</h5>
                    </div>
                    <div class="card-body-custom p-0">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Tiêu đề</th>
                                    <th>Thương hiệu</th>
                                    <th>Mô tả</th>
                                    <th width="20%">Hình ảnh</th>


                                    <th width="20%" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($banners as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: #32325d; font-size: 1rem;">{{ $item->title }}</div>
                                        <div style="color: #8898aa; font-size: 0.9rem; margin-top: 4px;">
                                         
                                        </div>

                                        @if($item->link)
                                        <div style="margin-top: 4px;">
                                            <a href="{{ $item->link }}" target="_blank" style="color: var(--primary-color); font-size: 0.85rem; text-decoration: none;">
                                                <i class="fas fa-link"></i> {{ Str::limit($item->link, 30) }}
                                            </a>
                                        </div>
                                        @endif
                                    </td>
                                      <td>
                                        <div style="font-weight: 700; color: #32325d; font-size: 1rem;">{{ $item->thuonghieu }}</div>
                                        

                                        @if($item->link)
                                        <div style="margin-top: 4px;">
                                            <a href="{{ $item->link }}" target="_blank" style="color: var(--primary-color); font-size: 0.85rem; text-decoration: none;">
                                                <i class="fas fa-link"></i> {{ Str::limit($item->link, 30) }}
                                            </a>
                                        </div>
                                        @endif
                                    </td>

                                     <td>
                                        <div style="color: #8898aa; font-size: 0.9rem; margin-top: 4px;">
                                           {{ $item->mota }}
                                        </div>
                                    </td>


                                    <td>
                                        <div class="thumb-box">
                                            <img src="{{ asset('storage/'.$item->hinhanh) }}" class="thumb-img" alt="Banner">
                                        </div>
                                    </td>
                                   

                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.banner.edit', $item->id) }}" class="btn-action btn-edit mb-2">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.banner.destroy', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa banner này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($banners->isEmpty())
                        <div style="padding: 20px; text-align: center; color: #8898aa;">
                            Chưa có banner nào. Hãy thêm mới bên phải.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-box">
                    <div class="card-header-custom {{ isset($bannerEdit) ? 'edit-mode' : '' }}">
                        <h5>
                            <i class="fas {{ isset($bannerEdit) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                            {{ isset($bannerEdit) ? 'Cập Nhật Banner' : 'Thêm Mới' }}
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <form action="{{ isset($bannerEdit) ? route('admin.banner.update', $bannerEdit->id) : route('admin.banner.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="form-label">Tiêu đề Banner <span style="color:red">*</span></label>
                                <input type="text" name="title" class="form-input" required
                                    value="{{ isset($bannerEdit) ? $bannerEdit->title : old('title') }}"
                                    placeholder="VD: Khuyến mãi mùa hè...">
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
                                    <img id="preview" src="#" style="display: none; max-width: 100%; max-height: 150px; border-radius: 5px; margin: 0 auto;">

                                    @if(isset($bannerEdit) && $bannerEdit->hinhanh)
                                    <div id="old-img">
                                        <p style="font-size: 12px; color: #888; margin-bottom: 5px;">Ảnh hiện tại:</p>
                                        <img src="{{ asset('storage/'.$bannerEdit->hinhanh) }}" style="max-width: 100%; max-height: 150px; border-radius: 5px;">
                                    </div>
                                    @else
                                    <p style="font-size: 13px; color: #aaa; margin:0;" id="placeholder-text">Chưa chọn ảnh</p>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn-action btn-save">
                                {{ isset($bannerEdit) ? 'Lưu Thay Đổi' : 'Thêm Mới' }}
                            </button>

                            @if(isset($bannerEdit))
                            <a href="{{ route('admin.banner.index') }}" class="btn-action btn-back">Hủy bỏ</a>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>

</body>

</html>