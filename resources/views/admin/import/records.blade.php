@extends('layouts.app')

@section('content')


<div class="container-fluid">

  {{-- Filters --}}
  <div class="card mb-3">
    <div class="card-body">
      <form method="GET" action="{{ route('admin.import.records') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="form-label small">Search</label>
          <input type="text" name="search" class="form-control form-control-sm"
                 placeholder="Name, email, city…" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small">Status</label>
          <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small">Batch ID</label>
          <input type="text" name="batch" class="form-control form-control-sm"
                 placeholder="Filter by batch UUID" value="{{ request('batch') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm flex-fill">
            <i class="bi bi-search"></i> Filter
          </button>
          <a href="{{ route('admin.import.records') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-x"></i>
          </a>
        </div>
      </form>
    </div>
  </div>

  {{-- Records table --}}
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">
        <i class="bi bi-table me-2"></i>Records
        <span class="badge bg-secondary ms-2">{{ $records->total() }}</span>
      </h5>
      @can('import-data')
      <a href="{{ route('admin.import.index') }}" class="btn btn-sm btn-success">
        <i class="bi bi-upload me-1"></i>New Import
      </a>
      @endcan
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City</th>
              <th>Country</th>
              <th>Status</th>
              <th>Batch</th>
              <th>Imported By</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($records as $record)
            <tr>
              <td class="text-muted small">{{ $record->id }}</td>
              <td>{{ $record->name }}</td>
              <td>{{ $record->email ?? '—' }}</td>
              <td>{{ $record->phone ?? '—' }}</td>
              <td>{{ $record->city ?? '—' }}</td>
              <td>{{ $record->country ?? '—' }}</td>
              <td>
                <span class="badge {{ $record->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                  {{ ucfirst($record->status) }}
                </span>
              </td>
              <td>
                <span class="font-monospace small text-muted" title="{{ $record->import_batch }}">
                  {{ $record->import_batch ? substr($record->import_batch, 0, 8).'…' : '—' }}
                </span>
              </td>
              <td>{{ $record->importer->name ?? '—' }}</td>
              <td class="small text-muted">{{ $record->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="10" class="text-center text-muted py-4">No records found</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    {{-- Pagination in theme card-footer style --}}
    {{ $records->appends(request()->query())->links('vendor.pagination.adminlte') }}
  </div>

</div>
@endsection
