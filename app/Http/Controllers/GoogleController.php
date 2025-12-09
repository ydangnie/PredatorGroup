<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// [1] Thêm 2 dòng này để sử dụng tính năng gửi mail
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class GoogleController extends Controller
{
    // 1. Chuyển hướng người dùng sang trang đăng nhập Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Xử lý thông tin Google trả về
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Tìm user trong DB bằng google_id hoặc email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Nếu user đã tồn tại -> Cập nhật google_id nếu chưa có
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                // Đăng nhập
                Auth::login($user);
                return redirect('/'); // Chuyển về trang chủ
            } else {
                // Nếu chưa có -> Tạo mới user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make('123456dummy'), // Mật khẩu giả
                    'role' => 'user'
                ]);

                // [2] Gửi email chào mừng (Sử dụng hàng đợi queue giống AuthDangKy)
                Mail::to($newUser->email)->queue(new WelcomeMail($newUser));

                Auth::login($newUser);
                return redirect('/');
            }

        } catch (\Exception $e) {
            return redirect('/dangnhap')->with('error', 'Lỗi đăng nhập Google: ' . $e->getMessage());
        }
    }
}