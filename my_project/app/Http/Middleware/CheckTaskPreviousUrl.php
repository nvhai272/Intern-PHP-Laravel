<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTaskPreviousUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $previousUrl = url()->previous();
        $currentUrl = $request->fullUrl();

        if (str_ends_with($currentUrl, '/management/task/add')) {
            $isFromConfirm = str_contains($previousUrl, '/task/add-confirm');
            $isFromValidationFail = $request->old();
            if (!$isFromConfirm && !$isFromValidationFail) {
                session()->forget('dataCreateTask');
            }
        }

        if (str_contains($currentUrl, '/management/task/edit')) {
            $isFromConfirm = str_contains($previousUrl, '/task/edit-confirm');
            $isFromValidationFail = $request->old();

            if (!$isFromConfirm && !$isFromValidationFail) {
                session()->forget('dataEditTask');
            }
        }
        return $next($request);
    }
}
