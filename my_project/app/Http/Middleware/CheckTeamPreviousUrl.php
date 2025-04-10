<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTeamPreviousUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $previousUrl = url()->previous();
        $currentUrl = $request->fullUrl();

        //  dd('Old:', $request->old(), 'Prev:', url()->previous(), 'Now:', $request->fullUrl());

        if (str_ends_with($currentUrl, '/management/team/add')) {
            $isFromConfirm = str_contains($previousUrl, '/team/add-confirm');
            $isFromValidationFail = $request->old(); // nếu fail form thì có dữ liệu old()

            if (!$isFromConfirm && !$isFromValidationFail) {
                session()->forget('dataCreateTeam');
            }
        }

        return $next($request);
    }
}
