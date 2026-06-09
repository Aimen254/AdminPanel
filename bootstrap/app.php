<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $_exceptions): void {
        //
    })->create();

// Vercel Lambda deploys to /var/task which is read-only (is_writable() lies on SquashFS).
// Detect Vercel via deployment path or VERCEL env var and redirect storage to writable /tmp.
if (str_starts_with(__DIR__, '/var/task') || (bool) getenv('VERCEL')) {
    $app->useStoragePath('/tmp/laravel/storage');
}

return $app;
