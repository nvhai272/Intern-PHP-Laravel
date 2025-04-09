<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployeePreviousUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $previousUrl = url()->previous();
        $currentUrl = $request->fullUrl();

        //  dd('Old:', $request->old(), 'Prev:', url()->previous(), 'Now:', $request->fullUrl());

        if (str_ends_with($currentUrl, '/management/employee/add')) {

            $isFromConfirm = str_contains($previousUrl, '/employee/add-confirm');

            $isFromValidationFail = $request->old(); // nếu fail form thì có dữ liệu old()

            if (!$isFromConfirm && !$isFromValidationFail) {
                // Không phải từ add-confirm, không phải từ validation fail => Xóa session
                session()->forget('dataCreateEmp');

                // xóa session và xóa ảnh tạm
                if (session()->has('temp_avatar')) {
                    Storage::delete(session('temp_avatar'));
                    session()->forget('temp_avatar');
                }
            }
        }


        if (str_contains($currentUrl, '/management/employee/edit')) {

            // dd(session('new_avatar'));
            $isFromConfirm = str_contains($previousUrl, '/employee/edit-confirm');
            $isFromValidationFail = $request->old();
            if (!$isFromConfirm && !$isFromValidationFail) {
                session()->forget('dataEditEmp');
                session()->forget('current_avatar');

                if (session()->has('new_avatar')) {
                    Storage::delete(session('new_avatar'));
                    session()->forget('new_avatar');
                }
            }
        }

        return $next($request);
    }
}
