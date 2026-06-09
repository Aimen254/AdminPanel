<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', config('app.name', 'AdminLTE')) — @yield('page_title', 'Authentication')</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">

  {{-- Apply saved theme before paint to avoid flash --}}
  <script>
    (function () {
      var stored = localStorage.getItem('lte-theme');
      var dark   = window.matchMedia('(prefers-color-scheme: dark)').matches;
      var theme  = stored ? stored : (dark ? 'dark' : 'light');
      if (theme === 'auto') theme = dark ? 'dark' : 'light';
      document.documentElement.setAttribute('data-bs-theme', theme);
    })();
  </script>

  <style>
    html, body { margin: 0; min-height: 100vh; }
    body { display: flex; }

    /* ── Wrapper ─────────────────────────────────── */
    .auth-wrapper {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    /* ── Left brand panel ────────────────────────── */
    .auth-brand {
      flex: 0 0 42%;
      background: linear-gradient(
        145deg,
        rgba(var(--bs-primary-rgb), 1)   0%,
        rgba(var(--bs-primary-rgb), .80) 60%,
        rgba(var(--bs-primary-rgb), .65) 100%
      );
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 2.5rem;
      position: relative;
      overflow: hidden;
    }
    .auth-brand::before {
      content: '';
      position: absolute;
      width: 350px; height: 350px;
      background: rgba(255,255,255,.07);
      border-radius: 50%;
      top: -80px; right: -80px;
    }
    .auth-brand::after {
      content: '';
      position: absolute;
      width: 250px; height: 250px;
      background: rgba(255,255,255,.05);
      border-radius: 50%;
      bottom: -60px; left: -60px;
    }

    .auth-brand-content { position: relative; z-index: 1; text-align: center; color: #fff; }

    .auth-brand-logo {
      width: 80px; height: 80px;
      background: rgba(255,255,255,.15);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2.2rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,.25);
    }
    .auth-brand-title { font-size: 2rem; font-weight: 700; margin-bottom: .5rem; }
    .auth-brand-sub   { font-size: .95rem; opacity: .85; max-width: 280px; line-height: 1.6; }

    .auth-feature-list { list-style: none; padding: 0; margin: 2.5rem 0 0; text-align: left; }
    .auth-feature-list li {
      display: flex; align-items: center; gap: .75rem;
      color: rgba(255,255,255,.9); font-size: .9rem; margin-bottom: 1rem;
    }
    .auth-feature-list li i { font-size: 1.1rem; opacity: .85; flex-shrink: 0; }

    /* ── Right form panel ────────────────────────── */
    .auth-form-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--bs-secondary-bg);
      padding: 2rem 1.5rem;
    }

    /* ── Card ────────────────────────────────────── */
    .auth-card {
      width: 100%;
      max-width: 420px;
      background-color: var(--bs-body-bg);
      border-radius: 16px;
      box-shadow: 0 8px 40px rgba(0,0,0,.12);
      overflow: hidden;
      border: 1px solid var(--bs-border-color);
    }

    .auth-card-header {
      padding: 2rem 2rem 1.25rem;
      border-bottom: 1px solid var(--bs-border-color);
    }
    .auth-card-header h2 {
      font-size: 1.5rem; font-weight: 700;
      color: var(--bs-emphasis-color);
      margin: 0 0 .3rem;
    }
    .auth-card-header p {
      font-size: .875rem;
      color: var(--bs-secondary-color, var(--bs-gray-600));
      margin: 0;
    }

    .auth-card-body { padding: 1.75rem 2rem 2rem; }

    /* ── Input groups ────────────────────────────── */
    .auth-input-group {
      position: relative;
      margin-bottom: 1.1rem;
    }
    .auth-input-group .form-control {
      padding-left: 2.8rem;
      padding-right: 2.8rem;
      border-radius: 10px;
      border: 1.5px solid var(--bs-border-color);
      background-color: var(--bs-body-bg);
      color: var(--bs-body-color);
      height: 46px;
      font-size: .9rem;
      transition: border-color .2s, box-shadow .2s;
    }
    .auth-input-group .form-control::placeholder { color: var(--bs-secondary-color, #adb5bd); }
    .auth-input-group .form-control:focus {
      border-color: var(--bs-primary);
      box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), .15);
      background-color: var(--bs-body-bg);
      color: var(--bs-body-color);
    }
    .auth-input-group .form-control.is-invalid { border-color: var(--bs-danger); }

    .auth-input-icon {
      position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
      color: var(--bs-secondary-color, #adb5bd); font-size: 1rem;
      pointer-events: none; z-index: 5;
    }
    .auth-input-toggle {
      position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
      color: var(--bs-secondary-color, #adb5bd); font-size: 1rem;
      cursor: pointer; z-index: 5; transition: color .2s;
    }
    .auth-input-toggle:hover { color: var(--bs-primary); }

    /* ── Submit button ───────────────────────────── */
    .btn-auth {
      background-color: var(--bs-primary);
      border: none;
      color: #fff;
      border-radius: 10px;
      height: 46px;
      font-size: .95rem;
      font-weight: 600;
      width: 100%;
      transition: filter .2s, transform .15s;
    }
    .btn-auth:hover:not(:disabled) {
      filter: brightness(1.1);
      transform: translateY(-1px);
      color: #fff;
    }
    .btn-auth:disabled { opacity: .5; cursor: not-allowed; }

    /* ── Links & extras ──────────────────────────── */
    .auth-link { color: var(--bs-primary); text-decoration: none; font-weight: 500; }
    .auth-link:hover { text-decoration: underline; filter: brightness(.85); color: var(--bs-primary); }

    .auth-divider {
      display: flex; align-items: center; gap: 1rem; margin: 1.25rem 0;
      color: var(--bs-secondary-color, #adb5bd); font-size: .8rem;
    }
    .auth-divider::before, .auth-divider::after {
      content: ''; flex: 1; height: 1px; background: var(--bs-border-color);
    }

    .auth-check-row {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 1.25rem;
    }
    .form-check-label { color: var(--bs-body-color); }

    .auth-footer-link {
      text-align: center; margin-top: 1.25rem;
      font-size: .875rem; color: var(--bs-secondary-color, #6c757d);
    }

    /* ── Agree checkbox area ─────────────────────── */
    .auth-agree-box {
      background-color: var(--bs-tertiary-bg);
      border: 1.5px solid var(--bs-border-color);
      border-radius: .75rem;
      padding: .85rem 1rem;
      margin-bottom: 1rem;
    }

    /* ── Responsive ──────────────────────────────── */
    @media (max-width: 768px) {
      .auth-brand { display: none; }
      .auth-form-panel { background-color: var(--bs-secondary-bg); padding: 1.5rem 1rem; }
    }
  </style>
</head>
<body>
<div class="auth-wrapper">

  {{-- Left branding panel --}}
  <div class="auth-brand">
    <div class="auth-brand-content">
      <div class="auth-brand-logo">
        <i class="bi bi-shield-lock-fill"></i>
      </div>
      <div class="auth-brand-title">{{ config('app.name', 'Admin Panel') }}</div>
      <p class="auth-brand-sub">A powerful admin panel with role-based access control and data management tools.</p>

      <ul class="auth-feature-list">
        <li><i class="bi bi-person-check-fill"></i> Role-based dashboards (Admin · Manager · User)</li>
        <li><i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel import with background job processing</li>
        <li><i class="bi bi-bell-fill"></i> Real-time notifications on job completion</li>
        <li><i class="bi bi-lock-fill"></i> Gates &amp; Policies for fine-grained access control</li>
      </ul>
    </div>
  </div>

  {{-- Right form panel --}}
  <div class="auth-form-panel">
    <div class="auth-card">
      @yield('content')
    </div>
  </div>

</div>

{{-- Theme toggle script (minimal — no dashboard extras) --}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/adminlte.js') }}"></script>
</body>
</html>
