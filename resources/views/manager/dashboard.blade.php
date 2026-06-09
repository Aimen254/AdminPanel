@extends('layouts.app')

@section('content')

<div class="container-fluid">

  <div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
      <div class="card bg-primary text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['total_records'] }}</div>
            <div class="small">Total Records</div>
          </div>
          <i class="bi bi-database fs-1 opacity-50"></i>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card bg-success text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['active_records'] }}</div>
            <div class="small">Active Records</div>
          </div>
          <i class="bi bi-check-circle fs-1 opacity-50"></i>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card bg-info text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['total_users'] }}</div>
            <div class="small">Users</div>
          </div>
          <i class="bi bi-people fs-1 opacity-50"></i>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card bg-warning text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['total_records'] - $stats['active_records'] }}</div>
            <div class="small">Inactive Records</div>
          </div>
          <i class="bi bi-x-circle fs-1 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Recent Records</h5>
      @can('view-import-records')
      <a href="{{ route('admin.import.records') }}" class="btn btn-sm btn-outline-primary">View All</a>
      @endcan
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Country</th><th>Status</th><th>Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($stats['recent_records'] as $record)
            <tr>
              <td>{{ $record->id }}</td>
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
              <td>{{ $record->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-3">No records found</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
