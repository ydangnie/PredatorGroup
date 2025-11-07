<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthDangNhap extends Controller
{
    public function dangnhap(){
        return view('auth.dangnhap');
    }
}
