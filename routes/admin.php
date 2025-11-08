<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function(){
Route::get('dasboard',[AdminController::class, 'index'])
->name('dasboard');


});
