<?php

use App\Http\Middleware\AuthMiddleware;
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
//        $middleware->use([
//            \App\Http\Middleware\AuthMiddleware::class,
//            // các middleware khác
//        ]);

        // tạo nhóm middleware
        $middleware->group('team.middleware', [
            AuthMiddleware::class,
            // Thêm middleware khác nếu cần
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
