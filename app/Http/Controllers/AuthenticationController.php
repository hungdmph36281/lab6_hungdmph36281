<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    // Hiển thị trang đăng ký
    public function register()
    {
        return view('auth.register');
    }

    // Xử lý yêu cầu đăng ký
    public function postRegister(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'active' => 1,
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Trở về trang đăng nhập');
    }

    // Hiển thị trang đăng nhập
    public function login()
    {
        return view('auth.login');
    }

    // Xử lý yêu cầu đăng nhập
    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
    
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
    
            if ($user->role == 'admin') {
                return redirect()->route('admin.users.index')->with('success', 'Đăng nhập thành công với quyền admin');
            } else {
                return redirect()->route('home')->with('success', 'Đăng nhập thành công với quyền user');
            }
        } else {
            return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');
        }
    }

    // Xử lý yêu cầu đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công');
    }
}
