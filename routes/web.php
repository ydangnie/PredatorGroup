<?php

use App\Http\Controllers\AuthDangKy;
use App\Http\Controllers\AuthDangNhap;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserConTroller;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserConTroller::class, 'index'])->middleware('access.time');



Route::controller(HomeController::class)-> group(function(){
    Route::get('/', 'index');
    Route::get('/about', 'about');

});
// Route::get('/login', function(){
//     return 'Login page';
// })->name('login');



Route::prefix('users')->controller(UsersController::class)->group(function () {
    Route::get('/', 'index')->name('users.index');
    Route::get('/create', 'create')->name('users.create'); // Tên đầy đủ là 'users.create'
    Route::post('/store', 'store')->name('users.store');
});

Route::prefix('posts')->controller(PostController::class)
->name('posts.')
->group(function(){
Route::get('/', 'index')->name('index');
Route::get('/create', 'create')->name('create');
Route::post('/', 'store')->name('store');
Route::get('/{id}', 'edit')->name('edit');
route::put('/{id}', 'update')->name('update');
Route::get('/{id}/delete', 'delete')->name('delete');
});
Route::get('dangky', [AuthDangKy::class, 'dangky']);
Route::post('dangky', [AuthDangKy::class, 'postdangky'])->name('postdangky');