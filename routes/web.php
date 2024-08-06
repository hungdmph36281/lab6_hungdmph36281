<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Admin\UserController;

// Đăng ký và đăng nhập
Route::get('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('register', [AuthenticationController::class, 'postRegister']);
Route::get('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('login', [AuthenticationController::class, 'postLogin']);
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

//Trang chính
Route::get('home', function () {
    return view('home');
})->name('home');

// Trang quản trị
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    // Các route khác cho quản trị viên có thể được thêm vào đây
});

// crud
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('user', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('user', [UserController::class, 'store'])->name('admin.user.store');
    Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('user/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});

