<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $begin = microtime(true);
        $response = $next($request);
        $end = microtime(true);

        $durationInSeconds = $end - $begin;

        if ($durationInSeconds > 5) {
            return response()->view('layouts.err', ['msgErr' => 'Không tìm thấy trang do hết thời gian chờ']);
        }

        return $response;
    }
}
