<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Khai báo đúng tên bảng và các cột tiếng Anh
    protected $table = 'orders';

    protected $fillable = [
        'user_id', 
        'name', 
        'phone', 
        'address', 
        'note', 
        'payment_method', 
        'total_price', // Khớp với database
        'status'       // Khớp với database
    ];

    // Quan hệ với chi tiết đơn hàng
    public function items() {
        // Lưu ý: Class Model chi tiết là OrderItem, bảng là order_items
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}