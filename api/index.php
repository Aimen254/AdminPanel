<?php

/**
 * Vercel Serverless Entry Point
 *
 * Vercel's filesystem is read-only except for /tmp.
 * Laravel needs writable directories for views, sessions, cache, and logs.
 * We create them in /tmp before bootstrapping the app.
 */

$tmpDirs = [
    '/tmp/laravel/storage/app/public/avatars',
    '/tmp/laravel/storage/framework/cache/data',
    '/tmp/laravel/storage/framework/sessions',
    '/tmp/laravel/storage/framework/views',
    '/tmp/laravel/storage/logs',
    '/tmp/laravel/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

require __DIR__ . '/../public/index.php';
