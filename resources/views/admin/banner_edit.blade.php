@section('content1')
<div class="container">
    <h2>Chỉnh sửa Banner</h2>

    {{-- Hàm route trỏ đến route update mà ta vừa khai báo --}}
    <form action="{{ route('admin.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ $banner->title }}">
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="mota" class="form-control">{{ $banner->mota }}</textarea>
        </div>

        <div class="mb-3">
            <label>Thương hiệu</label>
            {{-- Thay input bằng select --}}
            <select name="thuonghieu" class="form-control">
                <option value="">-- Chọn thương hiệu --</option>
                {{-- Lưu ý: Bạn cần truyền $listBrands từ Controller vào view edit riêng này nếu chưa có --}}
                @foreach($listBrands as $brand)
                <option value="{{ $brand->ten_thuonghieu }}"
                    {{ $banner->thuonghieu == $brand->ten_thuonghieu ? 'selected' : '' }}>
                    {{ $brand->ten_thuonghieu }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Hình ảnh hiện tại</label><br>
            <img src="{{ asset('storage/' . $banner->hinhanh) }}" width="150" alt="Ảnh cũ">
        </div>

        <div class="mb-3">
            <label>Chọn ảnh mới (nếu muốn thay đổi)</label>
            <input type="file" name="hinhanh" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection