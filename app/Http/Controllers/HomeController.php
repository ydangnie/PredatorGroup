<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner; //
use App\Models\Brand; 
class HomeController extends Controller
{
    public function index(){
        // Lấy danh sách banner từ database
        $banners = Banner::all(); 
        $brands = Brand::limit(3)->get();
        // Truyền biến $banners sang view 'index'
        return view('index', compact('banners', 'brands'));
    }

    public function about(){
        return view('about');
    }
}