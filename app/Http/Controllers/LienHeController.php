<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Thêm thư viện Mail
use App\Mail\ContactMail;            // Thêm lớp Mailable vừa tạo

class LienHeController extends Controller
{
    public function lienhe(){
        return view('lienhe');
    }

    // Phương thức mới để xử lý form POST
    public function postLienhe(Request $request)
    {
        // 1. Xác thực dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
        ]);

        try {
            // 2. Chuẩn bị dữ liệu
            $data = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'] ?? 'Không cung cấp',
                'message_content' => $validatedData['message'],
            ];

            // 3. Địa chỉ email nhận (admin/hỗ trợ) - Lấy từ thông tin trong view là cskh@predator.vn
            $toEmail = 'cskh@predator.vn'; 

            // 4. Gửi mail
            Mail::to($toEmail)->send(new ContactMail($data));

            // 5. Thông báo thành công và chuyển hướng
            return redirect()->route('lienhe')->with('success', 'Đã gửi yêu cầu liên hệ thành công. Chúng tôi sẽ phản hồi trong 24h.');

        } catch (\Exception $e) {
            // Xử lý lỗi gửi mail
            // dd($e); // Dùng để debug nếu cần
            return back()->withInput()->with('error', 'Có lỗi xảy ra trong quá trình gửi. Vui lòng kiểm tra cấu hình Mail và thử lại.');
        }
    }
}