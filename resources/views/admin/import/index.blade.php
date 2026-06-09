@extends('layouts.app')

@section('content')


<div class="container-fluid">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      @foreach($errors->all() as $error)
        <div><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</div>
      @endforeach
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-3">
    {{-- Upload card --}}
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bi bi-upload me-2"></i>Upload Excel File</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.import.upload') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Select File</label>
              <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required id="fileInput">
              <div class="form-text">Accepted: .xlsx, .xls, .csv — Max 10MB</div>
            </div>

            <div id="filePreview" class="d-none mb-3 p-3 bg-light rounded border">
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-spreadsheet fs-3 text-success"></i>
                <div>
                  <div id="fileName" class="fw-semibold"></div>
                  <div id="fileSize" class="small text-muted"></div>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" id="uploadBtn">
              <i class="bi bi-cloud-upload me-2"></i>Upload & Queue Import
            </button>
          </form>

          <hr>
          <div class="small text-muted">
            <p class="mb-1"><strong>Expected columns:</strong></p>
            <code>name, email, phone, city, country</code>
            <p class="mt-2 mb-0">
              The import runs as a background job. You'll receive a notification when it completes.
              Up to 1,000+ records are processed in chunks of 500 for optimal performance.
            </p>
          </div>
        </div>
      </div>

      {{-- Download sample --}}
      <div class="card mt-3">
        <div class="card-body">
          <h6><i class="bi bi-download me-2"></i>Download Sample Template</h6>
          <p class="small text-muted mb-3">Use this template to format your data correctly.</p>
          <a href="{{ route('admin.import.sample') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-file-earmark-excel me-2"></i>Download Sample CSV
          </a>
        </div>
      </div>
    </div>

    {{-- Past batches --}}
    <div class="col-md-7">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Import History</h5>
          <a href="{{ route('admin.import.records') }}" class="btn btn-sm btn-outline-primary">View All Records</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>Batch ID</th>
                  <th>Records</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($batches as $batch)
                <tr>
                  <td>
                    <span class="font-monospace small text-muted" title="{{ $batch->import_batch }}">
                      {{ substr($batch->import_batch, 0, 8) }}…
                    </span>
                  </td>
                  <td><span class="badge bg-primary">{{ number_format($batch->total) }}</span></td>
                  <td>{{ \Carbon\Carbon::parse($batch->imported_at)->format('d M Y H:i') }}</td>
                  <td>
                    <a href="{{ route('admin.import.records', ['batch' => $batch->import_batch]) }}"
                       class="btn btn-xs btn-outline-info btn-sm">
                      <i class="bi bi-eye"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.import.destroy', $batch->import_batch) }}"
                          class="d-inline" onsubmit="return confirm('Delete this batch?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No imports yet</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        {{-- Pagination in theme card-footer style --}}
        {{ $batches->appends(request()->query())->links('vendor.pagination.adminlte') }}
      </div>
    </div>
  </div>

</div>

<script>
document.getElementById('fileInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('filePreview').classList.remove('d-none');
});

document.getElementById('importForm').addEventListener('submit', function () {
    const btn = document.getElementById('uploadBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading…';
});
</script>
@endsection
