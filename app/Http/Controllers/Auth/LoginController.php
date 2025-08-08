<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\RequestLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function getFormLogin()
    {
        $title_page = 'Đăng nhập';
        return view('auth.login',compact('title_page'));
    }

 public function postLogin(RequestLogin $request)
{
    $credentials = $request->only('email', 'password');

    // Thử lấy user theo email trước
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if (!$user) {
        // User không tồn tại
        return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');
    }

    // Kiểm tra is_verify
    if ($user->is_verify == 0) {
        // Chưa xác thực email
        return redirect()->back()->with('error', 'Bạn cần xác thực tài khoản để đăng nhập');
    }

    // Thử đăng nhập bình thường
    if (Auth::attempt($credentials)) {
        $this->logLogin();
        return redirect()->intended('/');
    }

    return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');
}

    protected function logLogin()
    {
        $log = get_agent();
        $historyLog = \Auth::user()->log_login;
        $historyLog = json_decode($historyLog,true) ?? [];
        $historyLog[] = $log;
        \DB::table('users')->where('id', \Auth::user()->id)
            ->update([
                'log_login' => json_encode($historyLog)
            ]);
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->to('/');
    }
}
