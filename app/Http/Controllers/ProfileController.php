<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAddress;
use App\Models\Order;

class ProfileController extends Controller
{
    // Hiển thị trang hồ sơ
    public function index()
    {
        $user = Auth::user();
        // Lấy danh sách địa chỉ (Mặc định lên đầu)
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        // Lấy lịch sử đơn hàng
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('profile.index', compact('user', 'addresses', 'orders'));
    }

    // Cập nhật thông tin chính
public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi' => 'nullable|string|max:255',
            'gioi_tinh' => 'nullable|in:nam,nu,khac', // Validate giới tính
            'ngay_sinh' => 'nullable|date|before:today', // Validate ngày sinh
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // 1. Avatar (Giữ nguyên)
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // 2. Cập nhật thông tin
        $user->name = $request->name;
        $user->so_dien_thoai = $request->so_dien_thoai;
        $user->dia_chi = $request->dia_chi;
        $user->gioi_tinh = $request->gioi_tinh; // Lưu giới tính
        $user->ngay_sinh = $request->ngay_sinh; // Lưu ngày sinh

        // [Logic đồng bộ địa chỉ mặc định cũ - Giữ nguyên nếu muốn]
        $defaultAddress = $user->addresses()->where('is_default', true)->first();
        if ($defaultAddress) {
            $defaultAddress->update([
                'name' => $request->name,
                'phone' => $request->so_dien_thoai,
                'address' => $request->dia_chi
                
                

            ]);
        }

        // 3. Đổi mật khẩu (Giữ nguyên)
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    // Thêm địa chỉ mới
    public function addAddress(Request $request) {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // Nếu chọn là mặc định hoặc chưa có địa chỉ nào -> Set mặc định
        if ($request->has('is_default') || Auth::user()->addresses()->count() == 0) {
            // Bỏ mặc định cũ
            Auth::user()->addresses()->update(['is_default' => false]);
            $request->merge(['is_default' => true]);
            
            // [Tùy chọn] Cập nhật ngược lại bảng users để đồng bộ
            $user = Auth::user();
            $user->update([
                'so_dien_thoai' => $request->phone,
                'dia_chi' => $request->address
            ]);
        }

        UserAddress::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_default' => $request->boolean('is_default', false)
        ]);

        return back()->with('success', 'Thêm địa chỉ thành công');
    }

    // Xóa địa chỉ
    public function deleteAddress($id) {
        $address = UserAddress::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        
        // Nếu xóa địa chỉ mặc định, hãy cảnh báo hoặc xử lý (ở đây cho xóa luôn)
        $address->delete();
        
        return back()->with('success', 'Đã xóa địa chỉ');
    }
    
    // Xem chi tiết đơn hàng
    public function showOrder($id)
    {
        $order = Order::where('user_id', Auth::id())
                      ->with('items.product')
                      ->findOrFail($id);

        return view('profile.order_detail', compact('order'));
    }
}