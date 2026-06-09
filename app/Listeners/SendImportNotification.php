<?php

namespace App\Listeners;

use App\Events\ImportCompleted;
use App\Events\ImportFailed;
use App\Models\User;
use App\Notifications\ImportCompletedNotification;
use App\Notifications\ImportFailedNotification;

class SendImportNotification
{
    public function handleCompleted(ImportCompleted $event): void
    {
        $user = User::find($event->userId);
        $user?->notify(new ImportCompletedNotification($event->batch, $event->rowCount));
    }

    public function handleFailed(ImportFailed $event): void
    {
        $user = User::find($event->userId);
        $user?->notify(new ImportFailedNotification($event->batch, $event->errorMessage));
    }
}
