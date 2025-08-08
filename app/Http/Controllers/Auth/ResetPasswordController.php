<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\ResetPasswordEmail;
use App\Http\Requests\UserRequestNewPassword;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function getEmailReset()
    {
        return view('auth.passwords.email');
    }

    public function checkEmailResetPassword(Request $request)
    {
        try {
            $account = \DB::table('users')->where('email', $request->email)->first();
            if ($account) {
                // Tạo token reset bằng chuỗi ngẫu nhiên
                $token = Str::random(60);

                // Xóa các token cũ trước khi insert token mới
                \DB::table('password_resets')->where('email', $account->email)->delete();

                \DB::table('password_resets')->insert([
                    'email' => $account->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);

                $link = route('get.new_password', ['email' => $account->email, '_token' => $token]);

                // Gửi mail reset password với link
                Mail::to($account->email)->send(new ResetPasswordEmail($link));

                \Session::flash('toastr', [
                    'type' => 'success',
                    'message' => 'Đường dẫn thay đổi mật khẩu đã được gửi tới email của bạn. Vui lòng kiểm tra email',
                ]);

                return redirect()->to('/');
            }

            \Session::flash('toastr', [
                'type' => 'error',
                'message' => 'Email không tồn tại trong hệ thống',
            ]);
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('checkEmailResetPassword error: ' . $e->getMessage(), ['exception' => $e]);
            \Session::flash('toastr', [
                'type' => 'error',
                'message' => 'Lỗi hệ thống, vui lòng thử lại sau.',
            ]);
            return redirect()->back();
        }
    }

    public function newPassword(Request $request)
    {
        try {
            $token = $request->_token;
            $email = $request->email;

            if (!$token || !$email) {
                return redirect()->to('/');
            }

            $checkToken = \DB::table('password_resets')
                ->where('email', $email)
                ->where('token', $token)
                ->first();

            if (!$checkToken) {
                return redirect()->to('/');
            }

            // Kiểm tra token còn hiệu lực trong 3 phút
            $now = Carbon::now();
            if ($now->diffInMinutes($checkToken->created_at) > 3) {
                \DB::table('password_resets')->where('email', $email)->delete();
                return redirect()->to('/');
            }

            return view('auth.passwords.reset', ['email' => $email, '_token' => $token]);

        } catch (\Exception $e) {
            Log::error('newPassword error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->to('/');
        }
    }

public function savePassword(UserRequestNewPassword $request)
{
    try {
        $password = $request->password;
        $email = $request->email;
        $token = $request->_token;

        \Log::info("Reset password attempt", compact('email', 'password', 'token'));

        if (!$email || !$token) {
            \Log::warning('Missing email or token');
            return redirect()->to('/');
        }

        // $checkToken = \DB::table('password_resets')
        //     ->where('email', $email)
        //     ->where('token', $token)
        //     ->first();

        // if (!$checkToken) {
        //     \Log::warning('Invalid reset token for email: ' . $email);
        //     return redirect()->to('/');
        // }

        // Cập nhật mật khẩu
        $affected = \DB::table('users')->where('email', $email)
            ->update(['password' => Hash::make($password)]);

        \Log::info("Password update affected rows: " . $affected);

        if ($affected === 0) {
            \Log::error("Failed to update password for email: " . $email);
            \Session::flash('toastr', [
                'type' => 'error',
                'message' => 'Cập nhật mật khẩu không thành công. Vui lòng thử lại.',
            ]);
            return redirect()->back();
        }

        // Xóa token sau cập nhật
        \DB::table('password_resets')->where('email', $email)->delete();

        \Session::flash('toastr', [
            'type' => 'success',
            'message' => 'Cập nhật mật khẩu thành công. Vui lòng đăng nhập lại',
        ]);

        return redirect()->route('get.login');

    } catch (\Exception $e) {
        \Log::error('Error in savePassword: ' . $e->getMessage(), ['exception' => $e]);
        \Session::flash('toastr', [
            'type' => 'error',
            'message' => 'Lỗi hệ thống, vui lòng thử lại sau.',
        ]);
        return redirect()->back();
    }
}

}
