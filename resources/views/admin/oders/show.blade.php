@extends('layouts.admin') 
{{-- Sử dụng layout admin của bạn --}}

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Chi tiết đơn hàng #{{ $order->id }}</h6>
            <div>
                <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-sm btn-secondary">In Hóa Đơn</a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Quay lại</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Thông tin khách hàng</h5>
                    <p><strong>Tên:</strong> {{ $order->name }}</p>
                    <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                    <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <h5>Thông tin đơn hàng</h5>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Thanh toán:</strong> {{ strtoupper($order->payment_method) }}</p>
                    <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        <select name="status" class="form-control d-inline-block w-auto" onchange="this.form.submit()">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </form>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ number_format($item->price) }}đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total) }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Tổng tiền:</th>
                        <th>{{ number_format($order->total_price) }}đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection