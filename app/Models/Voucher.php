<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'quantity',
        'start_date',
        'end_date',
        'status',
    ];
    
    // Cast ngày tháng để format dễ hơn trong view
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}