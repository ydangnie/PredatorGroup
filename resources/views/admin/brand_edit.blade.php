<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thương Hiệu</title>
    {{-- Tận dụng CSS admin dashboard --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #18181b; color: #fff;">
    @include('admin.nav')
    <div class="container mt-5">
        <div class="card bg-dark text-white border-secondary">
            <div class="card-header">
                <h3>Chỉnh sửa Thương Hiệu</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tên Thương hiệu</label>
                        <input type="text" name="ten_thuonghieu" class="form-control bg-secondary text-white border-0" value="{{ $brand->ten_thuonghieu }}" required>
                    </div>
            
                    <div class="mb-3">
                        <label class="form-label">Logo hiện tại</label><br>
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" width="150" class="rounded border border-secondary" alt="Logo cũ">
                        @else
                            <span class="text-muted">Chưa có logo</span>
                        @endif
                    </div>
            
                    <div class="mb-3">
                        <label class="form-label">Chọn Logo mới (nếu muốn thay đổi)</label>
                        <input type="file" name="logo" class="form-control bg-secondary text-white border-0">
                    </div>
            
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.brand.index') }}" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
