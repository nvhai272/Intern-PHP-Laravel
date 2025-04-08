<?php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\TimeoutMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // xử lí trước khi gọi request router
    ->withMiddleware(function (Middleware $middleware) {
        // danh sách khai báo - đăng kí các middleware toàn cục cho toàn bộ router trong hệ thống của bạn
       $middleware->use([
           TimeoutMiddleware::class
       ]);

        // tạo nhóm middleware
        $middleware->group('team.middleware', [
            AuthMiddleware::class,
            // TimeoutMiddleware::class
        ]);

        $middleware->group('emp.middleware', [
            AuthMiddleware::class,
            // TimeoutMiddleware::class

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
