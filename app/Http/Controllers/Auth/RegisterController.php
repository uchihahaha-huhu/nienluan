<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestRegister;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\RegisterSuccess; // Mail gửi xác thực (bạn cần tạo hoặc thay thế)
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function getFormRegister()
    {
        $title_page = 'Đăng ký';
        return view('auth.register', compact('title_page'));
    }

    public function postRegister(RequestRegister $request)
    {
        $data = $request->except("_token");
        $data['password'] = Hash::make($data['password']);
        $data['created_at'] = Carbon::now();
        $data['is_verify'] = 0;

        $id = User::insertGetId($data);

        if ($id) {
            try {
                Mail::to($request->email)->send(new RegisterSuccess($request->name, $request->email));
                \Log::info('RegisterSuccess email sent to: ' . $request->email);
            } catch (\Exception $e) {
                \Log::error('Error sending RegisterSuccess email to: ' . $request->email . '. Message: ' . $e->getMessage());
            }

            // Sử dụng redirect with key 'success' cho flash message
            return redirect()->route('get.login')
                ->with('success', 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản.');
        }

        return redirect()->back()->withInput();
    }
}
