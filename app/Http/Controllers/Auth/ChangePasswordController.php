<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{

    public function showForm()
    {
        return view('auth.change_password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Validation dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'current_password'      => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'], // confirmed yêu cầu có trường password_confirmation
        ], [
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

        // Cập nhật mật khẩu mới (đã mã hóa)
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Thông báo thành công
        \Session::flash('toastr', [
            'type'    => 'success',
            'message' => 'Đổi mật khẩu thành công!',
        ]);

        // Nếu muốn, bạn có thể logout user khỏi tất cả các session khác, hoặc giữ đăng nhập hiện tại.

        return redirect()->route('get.user.update_info'); // thay bằng route bạn muốn sau thay đổi mật khẩu
    }
}
