@extends('auth.layouts.auth')
@section('page_title', 'Create Account')

@section('content')
<div class="auth-card-header">
  <h2>Create account</h2>
  <p>Fill in the details below to get started</p>
</div>

<div class="auth-card-body">

  <form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf

    {{-- Name --}}
    <div class="auth-input-group">
      <i class="bi bi-person auth-input-icon"></i>
      <input
        id="name" type="text" name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name') }}"
        placeholder="Full name"
        required autocomplete="name" autofocus>
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Email --}}
    <div class="auth-input-group">
      <i class="bi bi-envelope auth-input-icon"></i>
      <input
        id="email" type="email" name="email"
        class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email') }}"
        placeholder="Email address"
        required autocomplete="email">
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
        placeholder="Password (min 8 characters)"
        required autocomplete="new-password">
      <i class="bi bi-eye-slash auth-input-toggle" onclick="togglePwd('password', this)"></i>
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Confirm Password --}}
    <div class="auth-input-group">
      <i class="bi bi-lock-fill auth-input-icon"></i>
      <input
        id="password_confirmation" type="password" name="password_confirmation"
        class="form-control"
        placeholder="Confirm password"
        required autocomplete="new-password">
      <i class="bi bi-eye-slash auth-input-toggle" onclick="togglePwd('password_confirmation', this)"></i>
    </div>

    {{-- Agree checkbox --}}
    <div class="p-3 mb-3 rounded-3" style="background:#f8f9ff; border:1.5px solid #e3e8ff;">
      <div class="form-check mb-0">
        <input
          class="form-check-input" type="checkbox"
          id="agreeTerms" onchange="handleAgree(this)">
        <label class="form-check-label" for="agreeTerms" style="font-size:.875rem;">
          I agree to the
          <a href="#" class="auth-link">Terms of Service</a>
          and
          <a href="#" class="auth-link">Privacy Policy</a>
        </label>
      </div>
    </div>

    <button type="submit" class="btn btn-auth" id="registerBtn" disabled>
      <i class="bi bi-person-plus me-2"></i>Create Account
    </button>
  </form>

  <div class="auth-footer-link">
    Already have an account?
    <a href="{{ route('login') }}" class="auth-link">Sign in</a>
  </div>

</div>

<script>
function handleAgree(checkbox) {
  const btn = document.getElementById('registerBtn');
  btn.disabled = !checkbox.checked;
}

function togglePwd(id, icon) {
  const field = document.getElementById(id);
  const show = field.type === 'password';
  field.type = show ? 'text' : 'password';
  icon.classList.toggle('bi-eye-slash', !show);
  icon.classList.toggle('bi-eye', show);
}
</script>
@endsection
