<?php

namespace App\Jobs;

use App\Events\ImportCompleted;
use App\Events\ImportFailed;
use App\Imports\RecordsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 300;
    public int $backoff = 60;

    public function __construct(
        private string $filePath,
        private int    $userId,
        private string $batch,
    ) {}

    public function handle(): void
    {
        Log::info('[Import] Job started', [
            'batch'   => $this->batch,
            'user_id' => $this->userId,
            'file'    => $this->filePath,
            'attempt' => $this->attempts(),
        ]);

        $import = new RecordsImport($this->userId, $this->batch);

        Excel::import($import, Storage::path($this->filePath), null, \Maatwebsite\Excel\Excel::XLSX);

        Storage::delete($this->filePath);

        $rowCount = $import->getRowCount();

        Log::info('[Import] Job completed', [
            'batch'   => $this->batch,
            'user_id' => $this->userId,
            'rows'    => $rowCount,
        ]);

        ImportCompleted::dispatch($this->userId, $this->batch, $rowCount);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('[Import] Job failed', [
            'batch'   => $this->batch,
            'user_id' => $this->userId,
            'error'   => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
        ]);

        Storage::delete($this->filePath);

        ImportFailed::dispatch($this->userId, $this->batch, $exception->getMessage());
    }
}
