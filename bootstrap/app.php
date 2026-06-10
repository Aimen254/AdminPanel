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
// Redirect storage AND bootstrap cache files to writable /tmp paths.
// Without this, ProviderRepository::writeManifest() fails writing services.php, which
// triggers an ErrorException (E_WARNING from file_put_contents) before ViewServiceProvider
// can register — causing the cascading "Target class [view] does not exist" error.
if (str_starts_with(__DIR__, '/var/task') || (bool) getenv('VERCEL')) {
    $app->useStoragePath('/tmp/laravel/storage');

    // Point all bootstrap cache writes to /tmp so they succeed on the read-only filesystem.
    foreach ([
        'APP_SERVICES_CACHE' => '/tmp/laravel/bootstrap/cache/services.php',
        'APP_PACKAGES_CACHE' => '/tmp/laravel/bootstrap/cache/packages.php',
        'APP_CONFIG_CACHE'   => '/tmp/laravel/bootstrap/cache/config.php',
        'APP_ROUTES_CACHE'   => '/tmp/laravel/bootstrap/cache/routes-v7.php',
        'APP_EVENTS_CACHE'   => '/tmp/laravel/bootstrap/cache/events.php',
    ] as $key => $path) {
        putenv("{$key}={$path}");
        $_ENV[$key] = $_SERVER[$key] = $path;
    }
}

return $app;
