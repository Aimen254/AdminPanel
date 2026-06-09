@extends('layouts.app')

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body text-center py-5">
          <img src="{{ auth()->user()->avatar_url }}"
               class="rounded-circle mb-3 shadow"
               style="width:80px;height:80px;object-fit:cover;"
               alt="{{ auth()->user()->name }}" />
          <h4>Welcome, {{ auth()->user()->name }}!</h4>
          <p class="text-muted mb-1">{{ auth()->user()->email }}</p>
          <span class="badge bg-secondary">{{ auth()->user()->role_name }}</span>
          <hr>
          <p class="text-muted">You are logged in. Contact your administrator if you need elevated access.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
