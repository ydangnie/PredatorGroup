<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tồn Kho</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/admin/banner.css'])
    
    <style>
        .inventory-table input {
            background: #18181b;
            border: 1px solid #3f3f46;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            width: 80px;
            text-align: center;
        }
        .inventory-table input:focus { outline: none; border-color: #D4AF37; }
        .variant-row td { background-color: #202022 !important; color: #aaa; padding-left: 40px; }
        .variant-label { font-size: 0.85rem; display: flex; align-items: center; gap: 10px; }
        .arrow-icon { color: #D4AF37; font-size: 12px; }
    </style>
</head>
<body>
    @include('admin.nav')
    
    <div class="banner-container">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.inventory.update') }}" method="POST">
            @csrf
            <div class="card-box">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-warehouse"></i> Quản Lý Tồn Kho</h5>
                    <button type="submit" class="btn-action btn-save" style="padding: 8px 20px;">
                        <i class="fas fa-sync-alt"></i> Cập Nhật & Đồng Bộ
                    </button>
                </div>

                <div class="card-body-custom p-0">
                    <table class="custom-table inventory-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>SKU</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $item)
                                {{-- Dòng sản phẩm chính --}}
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="{{ asset('storage/'.$item->hinh_anh) }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            <span style="font-weight: 600; color: #fff;">{{ $item->tensp }}</span>
                                        </div>
                                    </td>
                                    <td style="color: #888;">{{ $item->sku }}</td>
                                    
                                    {{-- Nếu có biến thể --}}
                                    @if($item->variants->count() > 0)
                                        <td class="text-center"><span class="badge bg-primary">{{ $item->variants->count() }} biến thể</span></td>
                                        <td class="text-center">
                                            <input type="text" value="{{ $item->so_luong }}" disabled style="opacity: 0.5; cursor: not-allowed;" title="Tổng tự động từ biến thể">
                                        </td>
                                    @else
                                        {{-- Nếu không có biến thể --}}
                                        <td class="text-center"><span class="badge bg-secondary">Đơn thể</span></td>
                                        <td class="text-center">
                                            <input type="number" name="products[{{ $item->id }}]" value="{{ $item->so_luong }}" min="0">
                                        </td>
                                    @endif
                                </tr>

                                {{-- Dòng các biến thể (nếu có) --}}
                                @foreach($item->variants as $variant)
                                <tr class="variant-row">
                                    <td colspan="2" style="border-top: none;">
                                        <div class="variant-label">
                                            <i class="fas fa-level-up-alt fa-rotate-90 arrow-icon"></i>
                                            Size: <b style="color: #D4AF37;">{{ $variant->size }}</b> - 
                                            Màu: <b style="color: #D4AF37;">{{ $variant->color }}</b>
                                        </div>
                                    </td>
                                    <td class="text-center" style="border-top: none;">--</td>
                                    <td class="text-center" style="border-top: none;">
                                        <input type="number" name="variants[{{ $variant->id }}]" value="{{ $variant->stock }}" min="0">
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</body>
</html>