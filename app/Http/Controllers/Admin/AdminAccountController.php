<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Requests\AdminRequestAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    // Danh sách admin
    public function index()
    {
        if (!check_admin()) {
            return redirect()->route('get.admin.index');
        }

        $admins = Admin::all(); // get() hoặc all() đều được
        return view('admin.admin.index', compact('admins'));
    }

    // Form tạo admin mới
    public function create()
    {
        return view('admin.admin.create');
    }

    // Lưu admin mới
    public function store(AdminRequestAccount $request)
    {
        $data = $request->except("_token");

        // Mã hóa password
        $data['password'] = Hash::make($data['password']);

        // Tạo bản ghi admin mới, Eloquent tự động xử lý created_at nếu bạn dùng timestamps = true
        Admin::create($data);

        // Redirect về trang tạo mới kèm flash message
       return redirect()->route('admin.account_admin.index')->with('success', 'Thêm tài khoản thành công!');
    }

    // Form chỉnh sửa admin
    public function edit($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            // Nếu không tìm thấy admin, trả về trang danh sách kèm thông báo
            return redirect()->route('admin.account_admin.index')->with('error', 'Tài khoản không tồn tại!');
        }

        return view('admin.admin.update', compact('admin'));
    }

    // Cập nhật admin
    public function update(AdminRequestAccount $request, $id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route('admin.account_admin.index')->with('error', 'Tài khoản không tồn tại!');
        }

        $data = $request->except("_token", "password");

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.account_admin.index')->with('success', 'Cập nhật tài khoản thành công!');
    }

    // Xóa admin
    public function delete($id)
    {
        // Chỉ user level 1 mới được quyền xóa admin
        if (get_data_user('admins', 'level') != 1) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa tài khoản!');
        }

        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->back()->with('error', 'Tài khoản không tồn tại!');
        }

        $admin->delete();

        return redirect()->back()->with('success', 'Xóa tài khoản thành công!');
    }
}
