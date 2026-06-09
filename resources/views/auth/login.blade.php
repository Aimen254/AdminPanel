@extends('auth.layouts.auth')
@section('page_title', 'Sign In')

@section('content')
<div class="auth-card-header">
  <h2>Welcome back</h2>
  <p>Sign in to your account to continue</p>
</div>

<div class="auth-card-body">

  @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
      {{ session('status') }}
      <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Email --}}
    <div class="auth-input-group">
      <i class="bi bi-envelope auth-input-icon"></i>
      <input
        id="email" type="email" name="email"
        class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email') }}"
        placeholder="Email address"
        required autocomplete="email" autofocus>
      <i class="bi bi-eye-slash auth-input-toggle d-none"></i>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Password --}}
    <div class="auth-input-group">
      <i class="bi bi-lock auth-input-icon"></i>
      <input
        id="password" type="password" name="password"
        class="form-control @error('password') is-invalid @enderror"
        placeholder="Password"
        required autocomplete="current-password">
      <i class="bi bi-eye-slash auth-input-toggle" onclick="togglePwd('password', this)" title="Show/hide password"></i>
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Remember + Forgot --}}
    <div class="auth-check-row">
      <div class="form-check mb-0">
        <input
          class="form-check-input" type="checkbox"
          name="remember" id="remember"
          {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label text-muted" for="remember" style="font-size:.875rem;">
          Remember me
        </label>
      </div>
      @if(Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="auth-link" style="font-size:.875rem;">
          Forgot password?
        </a>
      @endif
    </div>

    <button type="submit" class="btn btn-auth">
      <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
  </form>

  @if(Route::has('register'))
    <div class="auth-footer-link">
      Don't have an account?
      <a href="{{ route('register') }}" class="auth-link">Create one</a>
    </div>
  @endif

</div>

<script>
function togglePwd(id, icon) {
  const field = document.getElementById(id);
  const show = field.type === 'password';
  field.type = show ? 'text' : 'password';
  icon.classList.toggle('bi-eye-slash', !show);
  icon.classList.toggle('bi-eye', show);
}
</script>
@endsection
