<?php

namespace App\Listeners;

use App\Events\ImportCompleted;
use App\Events\ImportFailed;
use Illuminate\Support\Facades\Log;

class LogImportActivity
{
    public function handleCompleted(ImportCompleted $event): void
    {
        Log::channel('stack')->info('[Import] Job completed', [
            'batch'    => $event->batch,
            'rows'     => $event->rowCount,
            'user_id'  => $event->userId,
            'status'   => 'success',
        ]);
    }

    public function handleFailed(ImportFailed $event): void
    {
        Log::channel('stack')->error('[Import] Job failed', [
            'batch'   => $event->batch,
            'error'   => $event->errorMessage,
            'user_id' => $event->userId,
            'status'  => 'failed',
        ]);
    }
}
