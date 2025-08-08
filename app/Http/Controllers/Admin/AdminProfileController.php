<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequestUpdateProfile;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function index()
    {
        $adminId = get_data_user('admins');
        $admin = Admin::find($adminId);

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Người dùng không tồn tại hoặc đã bị xóa!');
            // Hoặc xử lý tùy theo logic bạn muốn
        }

        return view('admin.profile.index', compact('admin'));
    }

    public function update(AdminRequestUpdateProfile $request, $id)
    {
        $adminId = get_data_user('admins');

        if ($id != $adminId) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa hồ sơ này!');
        }

        $admin = Admin::find($adminId);
        if (!$admin) {
            return redirect()->back()->with('error', 'Người dùng không tồn tại!');
        }

        $data = $request->except(['_token', 'avatar']);

        if ($request->hasFile('avatar')) {
            $image = upload_image('avatar');
            if ($image['code'] == 1) {
                $data['avatar'] = $image['name'];
            } else {
                return redirect()->back()->with('error', 'Upload ảnh không thành công, vui lòng thử lại!');
            }
        }

        try {
            $admin->update($data);
            return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật hồ sơ thất bại, vui lòng thử lại!');
        }
    }
}
