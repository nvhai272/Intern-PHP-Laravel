<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\Employee;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('layouts.login');
    }

    public function login(LoginRequest $request)
    {
        // TH1 -> chỉ check pass -> email check trong FormRequest
        if (!Auth::attempt($request->only(['email', 'password']))) {
//            \Log::info(ERR_LOGIN . " with email: " . $request->email);
            \Log::error(ERR_LOGIN . " with email: ", [$request->email]);

            return back()->withErrors([
                'password' => 'Invalid email or password',
            ])->withInput();
        }

        \Log::info(LOGIN_SUCCESSED . " with email: ", [$request->email]);
        session(['accountLogin' => Auth::user()]);
        return redirect()->route('home')->with('success', LOGIN_SUCCESSED);
//        return redirect('/')->with('success', 'Login successful');

        // TH2 -> check cả pass and mail
    }

    public function logout(Request $request)
    {
//        if (auth()->check()) {
//            \Log::info(LOGOUT . " with infomation ", [
//                'user_id' => auth()->id(),
//                'email' => auth()->user()->email
//            ]);

            if ($request->user()) {
                \Log::info(LOGOUT . " with infomation ", [
                    'user_id' => $request->id,
                    'email' => $request->email
                ]);
            // Xóa session & đăng xuất user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Chuyển hướng về trang login
            return redirect()->route('login.form')->with('msg', LOGOUT);
        } else {

            return view('layouts.home');
        }
    }
}
