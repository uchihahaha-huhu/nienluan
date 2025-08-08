<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterSuccess;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $token = $request->token;

            if (!$token) {
                // dùng with() đặt flash khóa success khi redirect login
                return redirect()->route('get.login')->with('success', 'Token xác thực không hợp lệ.');
            }

            $decoded = base64_decode($token);
            if (!$decoded) {
                return redirect()->route('get.login')->with('success', 'Token xác thực không hợp lệ.');
            }

            $parts = explode('|', $decoded);
            if (count($parts) != 2) {
                return redirect()->route('get.login')->with('success', 'Token xác thực không hợp lệ.');
            }

            $email = $parts[0];

            $user = User::where('email', $email)->first();
            if (!$user) {
                return redirect()->route('get.login')->with('success', 'Tài khoản không tồn tại.');
            }

            if ($user->is_verify) {
                return redirect()->route('get.login')->with('success', 'Tài khoản đã được xác thực trước đó.');
            }

            $user->is_verify = 1;
            $user->email_verified_at = Carbon::now();
            $user->save();

            return redirect()->route('get.login')->with('success', 'Xác thực tài khoản thành công. Bạn có thể đăng nhập.');

        } catch (\Exception $e) {
            Log::error('VerificationController@verify error: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('get.login')->with('success', 'Có lỗi xảy ra trong quá trình xác thực tài khoản. Vui lòng thử lại sau.');
        }
    }

    public function showVerifyEmailForm()
    {
        return view('auth.verify_account');
    }

    public function sendVerifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $email = $request->email;

            $user = User::where('email', $email)->first();

            if (!$user) {
                return redirect()->back()->withInput()->with('success', 'Email chưa đăng ký trong hệ thống.');
            }

            if ($user->is_verify) {
                return redirect()->back()->with('success', 'Tài khoản của bạn đã được xác thực.');
            }

            $verificationToken = base64_encode($email . '|' . now()->timestamp);

            Mail::to($email)->send(new RegisterSuccess($user->name, $email, $verificationToken));

            return redirect()->back()->with('success', 'Đường dẫn xác thực đã được gửi tới email của bạn. Vui lòng kiểm tra email.');

        } catch (\Exception $e) {
            Log::error('VerificationController@sendVerifyEmail error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('success', 'Có lỗi xảy ra khi gửi email xác thực. Vui lòng thử lại sau.');
        }
    }
}
