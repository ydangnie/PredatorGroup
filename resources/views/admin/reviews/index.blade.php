<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đánh Giá & Bình Luận</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Tận dụng CSS đã có --}}
    @vite(['resources/css/admin/banner.css'])
</head>

<body>
    @include('admin.nav')

    <div class="banner-container">
        @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <div class="card-box">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-star"></i> Danh Sách Đánh Giá Khách Hàng</h5>
            </div>

            <div class="card-body-custom p-0">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Khách hàng</th>
                            <th width="20%">Sản phẩm</th>
                            <th width="10%">Đánh giá</th>
                            <th width="35%">Nội dung bình luận</th>
                            <th width="10%">Ngày gửi</th>
                            <th class="text-center" width="5%">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div style="font-weight: bold; color: #f4f4f5;">
                                    {{ $item->user ? $item->user->name : 'Người dùng ẩn' }}
                                </div>
                                <small style="color: #aaa;">ID: {{ $item->user_id }}</small>
                            </td>
                            <td>
                                @if($item->product)
                                    <a href="{{ route('chitietsanpham', $item->product->id) }}" target="_blank" style="color: #3b82f6; text-decoration: none;">
                                        {{ Str::limit($item->product->tensp, 30) }}
                                    </a>
                                @else
                                    <span style="color: #ef4444;">Sản phẩm đã xóa</span>
                                @endif
                            </td>
                            <td>
<span style="color: #FFD700; font-weight: bold;">
                                    {{ $item->so_sao }} <i class="fas fa-star"></i>
                                </span>
                            </td>
                            <td>
                                <div style="color: #d4d4d8; font-style: italic;">
                                    "{{ Str::limit($item->binh_luan, 100) }}"
                                </div>
                            </td>
                            <td style="color: #aaa; font-size: 0.9rem;">
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.reviews.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn-action btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa đánh giá này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($reviews->isEmpty())
                <div style="padding: 30px; text-align: center; color: #888;">
                    Chưa có đánh giá nào.
                </div>
                @endif
                
                <div style="padding: 15px;">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
