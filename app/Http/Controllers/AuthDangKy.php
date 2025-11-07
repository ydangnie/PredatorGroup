<?php

namespace App\Http\Controllers;

use App\Http\Requests\DangKyRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthDangKy extends Controller
{
    public function dangky()
    {
        return view('auth.dangnhap');
    }
    public function postdangky(DangKyRequest $request)
    {
        User::created([
            'name'=> $request->get('name'),
            'email'=> $request->get('email'),
            'password'=> Hash::make($request->get('password'))
        ]);
        return back()->with('message', 'Đăng ký thành công');
    }
}
