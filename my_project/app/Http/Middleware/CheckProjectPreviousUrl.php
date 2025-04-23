<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProjectPreviousUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $previousUrl = url()->previous();
        $currentUrl = $request->fullUrl();

        if (str_ends_with($currentUrl, '/management/project/add')) {
            $isFromConfirm = str_contains($previousUrl, '/project/add-confirm');
            $isFromValidationFail = $request->old();
            if (!$isFromConfirm && !$isFromValidationFail) {
                session()->forget('dataCreateProject');
            }
        }

        if (str_contains($currentUrl, '/management/project/edit')) {
            $isFromConfirm = str_contains($previousUrl, '/project/edit-confirm');
            $isFromValidationFail = $request->old();
            if (!$isFromConfirm && !$isFromValidationFail) {
                session()->forget('dataEditProject');
            }
        }

        return $next($request);
    }
}
