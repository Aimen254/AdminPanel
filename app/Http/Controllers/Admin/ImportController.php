<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImportJob;
use App\Models\ImportRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $batches = ImportRecord::select('import_batch')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('MAX(created_at) as imported_at')
            ->groupBy('import_batch')
            ->latest('imported_at')
            ->paginate(10);

        return view('admin.import.index', compact('batches'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('imports', 'local');
        $batch = Str::uuid()->toString();

        ProcessImportJob::dispatch($path, auth()->user()->id, $batch)
            ->onQueue('imports');

        return redirect()->route('admin.import.index')
            ->with('success', 'Import queued. You will be notified when it completes.');
    }

    public function records(Request $request)
    {
        $query = ImportRecord::with('importer')
            ->when($request->search, fn($q) => $q
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
                ->orWhere('city', 'like', "%{$request->search}%")
            )
            ->when($request->batch, fn($q) => $q->where('import_batch', $request->batch))
            ->when($request->status, fn($q) => $q->where('status', $request->status));

        $records = $query->latest()->paginate(50)->withQueryString();

        return view('admin.import.records', compact('records'));
    }

    public function sample()
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="sample_import.csv"'];
        $rows = [
            ['name', 'email', 'phone', 'city', 'country'],
            ['John Doe', 'john@example.com', '+1234567890', 'New York', 'USA'],
            ['Jane Smith', 'jane@example.com', '+0987654321', 'London', 'UK'],
        ];
        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function destroy(string $batch)
    {
        ImportRecord::where('import_batch', $batch)->delete();

        return redirect()->route('admin.import.index')
            ->with('success', 'Import batch deleted.');
    }
}
