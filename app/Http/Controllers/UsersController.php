<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    // 1. Hiển thị danh sách
    public function index()
    {
        // Lấy danh sách user mới nhất
        $users = User::latest()->get();
        $userEdit = null;
        return view('admin.user', compact('users', 'userEdit'));
    }

    // 2. Hiển thị form sửa (load lại trang với modal sửa)
    public function edit($id)
    {
        $users = User::latest()->get();
        $userEdit = User::findOrFail($id);
        return view('admin.user', compact('users', 'userEdit'));
    }

    // 3. Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user', // 0: Khách hàng, 1: Admin
        ], [
            'email.unique' => 'Email này đã tồn tại.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
            'role' => $request->role,
            'phone' => $request->phone ?? null,
            'address' => $request->address ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }

    // 4. Cập nhật
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone ?? $user->phone,
            'address' => $request->address ?? $user->address,
        ];

        // Chỉ cập nhật mật khẩu nếu có nhập
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
    }

    // 5. Xóa
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Không cho phép tự xóa chính mình
        if (auth()->id() == $id) {
            return back()->with('error', 'Bạn không thể xóa chính tài khoản đang đăng nhập!');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng!');
    }
}