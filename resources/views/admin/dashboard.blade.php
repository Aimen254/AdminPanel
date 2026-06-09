@extends('layouts.app')

@section('content')

<div class="container-fluid">

  {{-- Flash messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Stats row --}}
  <div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
      <div class="card bg-primary text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['total_users'] }}</div>
            <div class="small">Total Users</div>
          </div>
          <i class="bi bi-people-fill fs-1 opacity-50"></i>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between small">
          <span>{{ $stats['admin_count'] }} admins · {{ $stats['manager_count'] }} managers · {{ $stats['user_count'] }} users</span>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card bg-success text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['total_imports'] }}</div>
            <div class="small">Imported Records</div>
          </div>
          <i class="bi bi-file-earmark-spreadsheet fs-1 opacity-50"></i>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between small">
          <a href="{{ route('admin.import.records') }}" class="text-white text-decoration-none">View all records <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card bg-warning text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['manager_count'] }}</div>
            <div class="small">Managers</div>
          </div>
          <i class="bi bi-person-gear fs-1 opacity-50"></i>
        </div>
        <div class="card-footer small text-white">
          <span>Active management accounts</span>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card bg-info text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="fs-5 fw-bold">{{ $stats['user_count'] }}</div>
            <div class="small">Regular Users</div>
          </div>
          <i class="bi bi-person-fill fs-1 opacity-50"></i>
        </div>
        <div class="card-footer small text-white">
          <span>Active user accounts</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    {{-- Quick actions --}}
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bi bi-lightning-fill text-warning me-2"></i>Quick Actions</h5>
        </div>
        <div class="card-body d-grid gap-2">
          @can('import-data')
          <a href="{{ route('admin.import.index') }}" class="btn btn-primary">
            <i class="bi bi-upload me-2"></i>Import Excel File
          </a>
          @endcan
          @can('view-import-records')
          <a href="{{ route('admin.import.records') }}" class="btn btn-outline-secondary">
            <i class="bi bi-table me-2"></i>View All Records
          </a>
          @endcan
          @can('manage-users')
          <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-info">
            <i class="bi bi-bar-chart me-2"></i>Manager Reports
          </a>
          @endcan
        </div>
      </div>
    </div>

    {{-- Recent imports --}}
    <div class="col-md-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Recent Imports</h5>
          <a href="{{ route('admin.import.records') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>City</th>
                  <th>Imported By</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($stats['recent_imports'] as $record)
                <tr>
                  <td>{{ $record->name }}</td>
                  <td>{{ $record->email ?? '—' }}</td>
                  <td>{{ $record->city ?? '—' }}</td>
                  <td>{{ $record->importer->name ?? '—' }}</td>
                  <td>{{ $record->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-3">No records imported yet</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
