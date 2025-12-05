<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers'; // Định nghĩa đúng tên bảng

    protected $fillable = [
        'code',
        'type',       // 'fixed' (tiền) hoặc 'percent' (%)
        'value',      // Giá trị giảm
        'quantity',   // Số lượng mã
        'start_date', // Ngày bắt đầu
        'end_date',   // Ngày kết thúc
        'status',     // 1: Hoạt động, 0: Khóa
    ];

    // Ép kiểu ngày tháng để xử lý dễ hơn trong View
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
    ];
}