<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tin Tức - Chuẩn SEO</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-grid.min.css" rel="stylesheet">

    {{-- Tận dụng CSS của Brand/Banner để đồng bộ giao diện --}}
    @vite(['resources/css/admin/banner.css'])

    <style>
        /* CSS bổ sung cho form tin tức dài hơn form brand */
        .modal-content {
            max-width: 900px !important;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-col {
            flex: 1;
        }

        textarea.form-input {
            height: auto;
            line-height: 1.5;
        }

        .seo-box {
            background: #1e1e24;
            padding: 15px;
            border-radius: 8px;
            border: 1px dashed #444;
            margin-top: 10px;
        }

        .seo-title {
            color: #D4AF37;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
        }
    </style>
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
                <h5><i class="fas fa-newspaper"></i> Danh sách Tin Tức</h5>

                @if(isset($postEdit))
                {{-- Nếu đang sửa thì nút này reset về trang danh sách --}}
                <a href="{{ route('admin.posts.index') }}" class="btn-action btn-add-new">
                    <i class="fas fa-plus-circle"></i> Thêm Mới
                </a>
                @else
                {{-- Mở Modal --}}
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
                            <th width="10%">Ảnh</th>
                            <th>Tiêu đề bài viết</th>
                            <th width="15%">SEO Status</th>
                            <th width="15%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="thumb-box">
                                    <img src="{{ asset('storage/'.$item->thumbnail) }}" class="thumb-img" alt="img"
                                        onerror="this.src='https://placehold.co/50x50?text=No+Img'">
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #f4f4f5;">{{ $item->title }}</div>
                                <small style="color: #888;">Slug: {{ $item->slug }}</small>
                            </td>
                            <td>
                                {{-- Sửa meta_description thành meta_desc --}}
                                @if(!empty($item->meta_title) && !empty($item->meta_desc))
                                <span style="color: #4ade80; font-size: 12px; font-weight:bold;">
                                    <i class="fas fa-check-circle"></i> Đã tối ưu
                                </span>
                                @else
                                <span style="color: #f87171; font-size: 12px;">
                                    <i class="fas fa-exclamation-circle"></i> Chưa tối ưu
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.posts.edit', $item->id) }}" class="btn-action btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa bài viết này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div style="padding: 20px;">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL FORM (Create & Edit) --}}
    <div id="postModal" class="modal-overlay" style="display: none;">
        <div class="modal-content card-box">
            <div class="card-header-custom {{ isset($postEdit) ? 'edit-mode' : '' }}">
                <h5>
                    <i class="fas {{ isset($postEdit) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ isset($postEdit) ? 'Cập Nhật Tin Tức' : 'Thêm Tin Tức Mới' }}
                </h5>
                <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
            </div>

            <div class="card-body-custom">
                <form action="{{ isset($postEdit) ? route('admin.posts.update', $postEdit->id) : route('admin.posts.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($postEdit)) @method('PUT') @endif

                    <div class="form-row">
                        <div class="form-col">
                            {{-- Tiêu đề --}}
                            <div class="form-group">
                                <label class="form-label">Tiêu đề bài viết <span style="color:red">*</span></label>
                                <input type="text" name="title" id="title" class="form-input" required
                                    onkeyup="ChangeToSlug()"
                                    value="{{ isset($postEdit) ? $postEdit->title : old('title') }}"
                                    placeholder="Nhập tiêu đề...">
                            </div>

                            {{-- Slug --}}
                            <div class="form-group">
                                <label class="form-label">Slug (URL chuẩn SEO)</label>
                                <input type="text" name="slug" id="slug" class="form-input"
                                    value="{{ isset($postEdit) ? $postEdit->slug : old('slug') }}">
                            </div>

                            {{-- Ảnh đại diện --}}
                            <div class="form-group">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" name="thumbnail" class="form-input" onchange="previewFile(this)" {{ isset($postEdit) ? '' : 'required' }}>

                                <div class="preview-area" style="margin-top: 10px;">
                                    <img id="preview" src="#" style="display: none; max-width: 100px; border-radius: 4px;">
                                    @if(isset($postEdit) && $postEdit->thumbnail)
                                    <div id="old-img">
                                        <img src="{{ asset('storage/'.$postEdit->thumbnail) }}" style="max-width: 100px; border-radius: 4px;">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-col">
                            {{-- Cấu hình SEO --}}
                            <div class="seo-box">
                                <div class="seo-title"><i class="fas fa-search"></i> Cấu hình SEO (SEOquake)</div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size: 12px;">Meta Title (Tiêu đề Google)</label>
                                    <input type="text" name="meta_title" class="form-input"
                                        value="{{ isset($postEdit) ? $postEdit->meta_title : old('meta_title') }}"
                                        placeholder="Tự động lấy theo tiêu đề nếu trống">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size: 12px;">Meta Description (Mô tả tìm kiếm)</label>
                                    <textarea name="meta_description" class="form-input" rows="3"
                                        placeholder="Mô tả ngắn gọn nội dung (Nên dưới 160 ký tự)...">{{ isset($postEdit) ? $postEdit->meta_desc : old('meta_description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size: 12px;">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-input"
                                        value="{{ isset($postEdit) ? $postEdit->meta_keywords : old('meta_keywords') }}"
                                        placeholder="dong ho, thoi trang...">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Nội dung chi tiết --}}
                    <div class="form-group">
                        <label class="form-label">Nội dung chi tiết</label>
                        <textarea name="content" class="form-input" rows="10" required>{{ isset($postEdit) ? $postEdit->content : old('content') }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn-action btn-save" style="flex: 1;">
                            {{ isset($postEdit) ? 'Lưu Thay Đổi' : 'Đăng Bài' }}
                        </button>

                        @if(isset($postEdit))
                        <a href="{{ route('admin.posts.index') }}" class="btn-action btn-back" style="flex: 1; margin:0; text-align: center; text-decoration: none; line-height: 40px;">Hủy bỏ</a>
                        @else
                        <button type="button" class="btn-action btn-back" onclick="closeModal()" style="flex: 1; margin:0">Hủy</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('postModal');

        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            @if(!isset($postEdit))
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            @endif
        }

        // Preview ảnh
        function previewFile(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('preview');
                    var oldImg = document.getElementById('old-img');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (oldImg) oldImg.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        // Tạo Slug tự động
        function ChangeToSlug() {
            var title, slug;
            title = document.getElementById("title").value;
            slug = title.toLowerCase();
            slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
            slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
            slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
            slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
            slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
            slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
            slug = slug.replace(/đ/gi, 'd');
            slug = slug.replace(/\s+/g, '-');
            slug = slug.replace(/[^a-z0-9\-]/g, '');
            document.getElementById('slug').value = slug;
        }

        // Tự động mở modal nếu đang Edit hoặc có lỗi
        document.addEventListener("DOMContentLoaded", function() {
            @if(isset($postEdit) || $errors -> any())
            openModal();
            @endif
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                @if(!isset($postEdit))
                closeModal();
                @endif
            }
        }
        // Tìm textarea meta_description
        const metaDescInput = document.querySelector('textarea[name="meta_description"]');

        if (metaDescInput) {
            // Tạo thẻ hiển thị số ký tự
            const counter = document.createElement('small');
            counter.style.display = 'block';
            counter.style.marginTop = '5px';
            counter.style.color = '#888';
            metaDescInput.parentNode.appendChild(counter);

            function updateCounter() {
                const len = metaDescInput.value.length;
                counter.textContent = `${len}/160 ký tự`;
                if (len > 160) {
                    counter.style.color = 'red';
                } else {
                    counter.style.color = '#4ade80'; // Màu xanh
                }
            }

            // Lắng nghe sự kiện nhập liệu
            metaDescInput.addEventListener('input', updateCounter);
            // Chạy lần đầu
            updateCounter();
        }
    </script>
</body>

</html>