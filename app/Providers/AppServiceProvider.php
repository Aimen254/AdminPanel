<?php

namespace App\Providers;

use App\Events\ImportCompleted;
use App\Events\ImportFailed;
use App\Listeners\LogImportActivity;
use App\Listeners\SendImportNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Import job events → logging
        Event::listen(ImportCompleted::class, [LogImportActivity::class, 'handleCompleted']);
        Event::listen(ImportFailed::class,    [LogImportActivity::class, 'handleFailed']);

        // Import job events → database + mail notifications
        Event::listen(ImportCompleted::class, [SendImportNotification::class, 'handleCompleted']);
        Event::listen(ImportFailed::class,    [SendImportNotification::class, 'handleFailed']);
    }
}
