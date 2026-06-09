<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int    $userId,
        public readonly string $batch,
        public readonly int    $rowCount,
    ) {}
}
