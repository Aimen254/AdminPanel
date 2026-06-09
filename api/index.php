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

// Diagnostic endpoint — hit /?__vercel_debug=1 to see raw environment info
if (isset($_GET['__vercel_debug'])) {
    header('Content-Type: application/json');
    $dirInfo = [];
    foreach ($tmpDirs as $dir) {
        $dirInfo[$dir] = ['exists' => is_dir($dir), 'writable' => is_writable($dir)];
    }
    echo json_encode([
        '__DIR__'              => __DIR__,
        'php_version'          => PHP_VERSION,
        'VERCEL_env'           => getenv('VERCEL'),
        'APP_KEY_set'          => (bool) getenv('APP_KEY'),
        'str_starts_with_test' => str_starts_with(__DIR__, '/var/task'),
        'tmp_dirs'             => $dirInfo,
        'original_storage'     => dirname(__DIR__) . '/storage',
        'original_writable'    => is_writable(dirname(__DIR__) . '/storage'),
    ], JSON_PRETTY_PRINT);
    exit;
}

// Wrap bootstrap in a try/catch so fatal boot errors show their true cause
// instead of the cascading "Target class [view] does not exist" red herring.
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    $out  = get_class($e) . ': ' . $e->getMessage() . "\n";
    $out .= 'at ' . $e->getFile() . ':' . $e->getLine() . "\n\n";
    $prev = $e->getPrevious();
    while ($prev) {
        $out .= 'Caused by: ' . get_class($prev) . ': ' . $prev->getMessage() . "\n";
        $out .= 'at ' . $prev->getFile() . ':' . $prev->getLine() . "\n\n";
        $prev = $prev->getPrevious();
    }
    echo $out;
}
