<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'city', 'country', 'status',
        'imported_by', 'import_batch',
    ];

    public function importer()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function scopeByBatch($query, string $batch)
    {
        return $query->where('import_batch', $batch);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
