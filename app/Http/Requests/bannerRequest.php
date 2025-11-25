<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class bannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'mota' => 'required|string|max:255',
            'thuonghieu' => 'required|string|max:255',
            'hinhanh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Bắt buộc khi thêm mới
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'hinhanh.required' => 'Vui lòng chọn hình ảnh',
            // ... thêm các thông báo khác tùy ý
        ];
    }
}