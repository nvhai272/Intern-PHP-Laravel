<?php

namespace App\Http\Controllers\Management;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function __construct()
    {
    }

    public function showLoginForm()
    {
        return view('layouts.login');
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            Log::error(ERR_LOGIN . " with email: ", [$request->email]);

            return back()->withErrors([
                'password' => 'Invalid email or password',
            ])->withInput();
        }

        if(Auth::check()){
            $user = Auth::user();

            // XÓA SESSION CỦ của user trừ cái hiện tại
            DB::table('sessions')->where('user_id', $user->id)->delete();

        }

        // ĐĂNG NHẬP lại để tạo session mới (bắt buộc vì session cũ bị xoá mất)
        Auth::login($user);

        // Ghi nhớ email nếu người dùng chọn
        if ($request->has('remember_username')) {
            cookie()->queue('remembered_email', $request->email, 60 * 24 * 30); // 30 ngày
        } else {
            cookie()->queue(cookie()->forget('remembered_email'));
        }

        Log::info(LOGIN_SUCCEED . " with email: ", [$request->email]);
        session(['accountLogin' => $user]);

        return redirect()->route('home')->with('msg', LOGIN_SUCCEED);
    }


    public function logout(Request $request)
    {
        //        if (auth()->check()) {
        //            \Log::info(LOGOUT . " with infomation ", [
        //                'user_id' => auth()->id(),
        //                'email' => auth()->user()->email
        //            ]);

        if ($request->user()) {
            Log::info(LOGOUT . " with information ", [
                'user_id' => $request->id,
                'email' => $request->email
            ]);

            // Xóa session & đăng xuất user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.form')->with('msg', LOGOUT);
        } else {

            return view('layouts.home');
        }
    }
}
