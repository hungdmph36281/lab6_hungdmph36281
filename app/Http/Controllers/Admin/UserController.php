<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }
     // Hiển thị trang thêm người dùng
     public function create()
     {
         return view('admin.user.create');
     }
 
     // Xử lý yêu cầu thêm người dùng
     public function store(Request $request)
     {
         $request->validate([
             'fullname' => 'required|string|max:255',
             'username' => 'required|string|max:255|unique:users',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8|confirmed',
             'role' => 'required|in:admin,user',
             'active' => 'required|boolean',
         ]);
 
         User::create([
             'fullname' => $request->fullname,
             'username' => $request->username,
             'email' => $request->email,
             'password' => Hash::make($request->password),
             'role' => $request->role,
             'active' => $request->active,
         ]);
 
         return redirect()->route('admin.user.index')->with('success', 'Thêm người dùng thành công');
     }
 
     // Hiển thị trang sửa người dùng
     public function edit($id)
     {
         $user = User::findOrFail($id);
         return view('admin.user.edit', compact('user'));
     }
 
     // Xử lý yêu cầu sửa người dùng
     public function update(Request $request, $id)
     {
         $user = User::findOrFail($id);
 
         $request->validate([
             'fullname' => 'required|string|max:255',
             'username' => 'required|string|max:255|unique:users,username,' . $id,
             'email' => 'required|string|email|max:255|unique:users,email,' . $id,
             'role' => 'required|in:admin,user',
             'active' => 'required|boolean',
             'password' => 'nullable|string|min:8|confirmed',
         ]);
 
         $user->fullname = $request->fullname;
         $user->username = $request->username;
         $user->email = $request->email;
         $user->role = $request->role;
         $user->active = $request->active;
 
         if ($request->filled('password')) {
             $user->password = Hash::make($request->password);
         }
 
         $user->save();
 
         return redirect()->route('admin.user.index')->with('success', 'Cập nhật người dùng thành công');
     }
 
     // Xóa người dùng
     public function destroy($id)
     {
         $user = User::findOrFail($id);
         $user->delete();
 
         return redirect()->route('admin.user.index')->with('success', 'Xóa người dùng thành công');
     }
}
