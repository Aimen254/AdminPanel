<?php

namespace App\Imports;

use App\Models\ImportRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RecordsImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation
{
    private int $rowCount = 0;

    public function __construct(
        private int $userId,
        private string $batch,
    ) {}

    public function collection(Collection $rows): void
    {
        $now = now();
        $inserts = $rows->map(fn($row) => [
            'name'         => $row['name'] ?? '',
            'email'        => $row['email'] ?? null,
            'phone'        => $row['phone'] ?? null,
            'city'         => $row['city'] ?? null,
            'country'      => $row['country'] ?? null,
            'status'       => 'active',
            'imported_by'  => $this->userId,
            'import_batch' => $this->batch,
            'created_at'   => $now,
            'updated_at'   => $now,
        ])->filter(fn($row) => !empty($row['name']))->toArray();

        if (!empty($inserts)) {
            ImportRecord::insert($inserts);
            $this->rowCount += count($inserts);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
