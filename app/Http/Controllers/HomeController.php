<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner; //

class HomeController extends Controller
{
    public function index(){
        // Lấy danh sách banner từ database
        $banners = Banner::all(); 
        
        // Truyền biến $banners sang view 'index'
        return view('index', compact('banners'));
    }

    public function about(){
        return view('about');
    }
}