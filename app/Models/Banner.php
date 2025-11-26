<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banner';

    // THÊM DÒNG NÀY ĐỂ SỬA LỖI
    public $timestamps = false; 

    protected $fillable = [
        'title',
        'mota',
        'hinhanh',
        'thuonghieu',
        'link'
    ];
}