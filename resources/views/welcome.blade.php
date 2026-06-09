<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Admin Panel') }}</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">

  <style>
    * { box-sizing: border-box; }
    body { margin: 0; min-height: 100vh; font-family: 'Source Sans 3', system-ui, sans-serif; }

    /* Hero */
    .hero {
      min-height: 100vh;
      background: linear-gradient(145deg, #0d1b4b 0%, #0d47a1 45%, #0277bd 80%, #01579b 100%);
      display: flex; flex-direction: column;
    }

    /* Navbar */
    .top-nav {
      display: flex; align-items: center; justify-content: space-between;
      padding: 1.25rem 2.5rem;
    }
    .top-nav .brand {
      display: flex; align-items: center; gap: .75rem;
      color: #fff; text-decoration: none; font-size: 1.25rem; font-weight: 700;
    }
    .top-nav .brand-icon {
      width: 40px; height: 40px; background: rgba(255,255,255,.15);
      border-radius: 10px; display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem; backdrop-filter: blur(6px);
      border: 1px solid rgba(255,255,255,.2);
    }
    .top-nav .nav-links { display: flex; gap: .75rem; align-items: center; }
    .btn-nav-ghost {
      color: rgba(255,255,255,.85); border: 1.5px solid rgba(255,255,255,.3);
      background: transparent; border-radius: 8px;
      padding: .45rem 1.2rem; font-size: .875rem; font-weight: 500;
      text-decoration: none; transition: all .2s;
    }
    .btn-nav-ghost:hover { background: rgba(255,255,255,.12); color: #fff; border-color: rgba(255,255,255,.5); }
    .btn-nav-solid {
      color: #0d47a1; background: #fff; border: 1.5px solid #fff;
      border-radius: 8px; padding: .45rem 1.2rem; font-size: .875rem; font-weight: 600;
      text-decoration: none; transition: all .2s;
    }
    .btn-nav-solid:hover { background: #e3f2fd; color: #0d47a1; }

    /* Hero content */
    .hero-body {
      flex: 1; display: flex; align-items: center;
      padding: 3rem 2.5rem 4rem;
      position: relative; overflow: hidden;
    }
    .hero-body::before {
      content: ''; position: absolute;
      width: 600px; height: 600px; border-radius: 50%;
      background: rgba(255,255,255,.04);
      right: -150px; top: -150px;
    }
    .hero-body::after {
      content: ''; position: absolute;
      width: 400px; height: 400px; border-radius: 50%;
      background: rgba(255,255,255,.04);
      left: -100px; bottom: -100px;
    }

    .hero-content { max-width: 620px; position: relative; z-index: 1; color: #fff; }
    .hero-badge {
      display: inline-flex; align-items: center; gap: .5rem;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
      border-radius: 50px; padding: .35rem 1rem; font-size: .8rem;
      color: #90caf9; margin-bottom: 1.5rem;
    }
    .hero-title {
      font-size: clamp(2.2rem, 4vw, 3.2rem);
      font-weight: 800; line-height: 1.15; margin-bottom: 1.25rem;
    }
    .hero-title span { color: #64b5f6; }
    .hero-desc {
      font-size: 1.1rem; opacity: .85; line-height: 1.7; margin-bottom: 2.5rem;
      max-width: 520px;
    }
    .hero-cta { display: flex; gap: 1rem; flex-wrap: wrap; }
    .btn-cta-primary {
      display: inline-flex; align-items: center; gap: .6rem;
      background: #fff; color: #0d47a1;
      border-radius: 10px; padding: .75rem 2rem; font-weight: 700; font-size: 1rem;
      text-decoration: none; transition: all .2s; box-shadow: 0 4px 20px rgba(0,0,0,.2);
    }
    .btn-cta-primary:hover { background: #e3f2fd; transform: translateY(-2px); color: #0d47a1; box-shadow: 0 8px 30px rgba(0,0,0,.25); }
    .btn-cta-secondary {
      display: inline-flex; align-items: center; gap: .6rem;
      border: 2px solid rgba(255,255,255,.4); color: #fff;
      border-radius: 10px; padding: .75rem 2rem; font-weight: 600; font-size: 1rem;
      text-decoration: none; transition: all .2s;
    }
    .btn-cta-secondary:hover { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.7); transform: translateY(-2px); }

    /* Stats strip */
    .stats-strip {
      background: rgba(255,255,255,.08); backdrop-filter: blur(10px);
      border-top: 1px solid rgba(255,255,255,.1);
      padding: 1.5rem 2.5rem;
      display: flex; gap: 2.5rem; flex-wrap: wrap;
    }
    .stat-item { color: #fff; }
    .stat-item .stat-num { font-size: 1.6rem; font-weight: 800; color: #64b5f6; }
    .stat-item .stat-label { font-size: .8rem; opacity: .7; margin-top: .1rem; }

    /* Features section */
    .features-section {
      padding: 5rem 2.5rem;
      background: #f4f6f9;
    }
    .section-label { color: #1565c0; font-weight: 700; font-size: .85rem; letter-spacing: .1em; text-transform: uppercase; }
    .section-title { font-size: 2rem; font-weight: 800; color: #1a237e; margin: .5rem 0 1rem; }
    .section-desc  { color: #6c757d; max-width: 520px; }

    .feature-card {
      background: #fff; border-radius: 16px; padding: 2rem;
      box-shadow: 0 2px 20px rgba(0,0,0,.06);
      height: 100%; border: 1px solid #e9ecef;
      transition: transform .2s, box-shadow .2s;
    }
    .feature-card:hover { transform: translateY(-4px); box-shadow: 0 8px 35px rgba(0,0,0,.1); }
    .feature-icon {
      width: 52px; height: 52px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; margin-bottom: 1.25rem;
    }
    .feature-card h5 { font-weight: 700; font-size: 1rem; color: #1a237e; margin-bottom: .5rem; }
    .feature-card p  { font-size: .875rem; color: #6c757d; line-height: 1.6; margin: 0; }

    /* Roles section */
    .roles-section { padding: 5rem 2.5rem; background: #fff; }
    .role-card {
      border-radius: 16px; padding: 2rem 1.75rem;
      border: 2px solid transparent; text-align: center;
      transition: all .2s; cursor: default;
    }
    .role-card.admin   { background: linear-gradient(135deg,#e8eaf6,#fff); border-color: #9fa8da; }
    .role-card.manager { background: linear-gradient(135deg,#e0f2f1,#fff); border-color: #80cbc4; }
    .role-card.user    { background: linear-gradient(135deg,#fce4ec,#fff); border-color: #f48fb1; }
    .role-card:hover   { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,.08); }
    .role-icon {
      width: 64px; height: 64px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.6rem; margin: 0 auto 1rem;
    }
    .role-card.admin   .role-icon { background: #3f51b5; color: #fff; }
    .role-card.manager .role-icon { background: #009688; color: #fff; }
    .role-card.user    .role-icon { background: #e91e63; color: #fff; }
    .role-card h5 { font-weight: 700; font-size: 1.05rem; margin-bottom: .6rem; }
    .role-card ul { list-style: none; padding: 0; margin: 0; }
    .role-card ul li { font-size: .85rem; color: #6c757d; padding: .25rem 0; display: flex; align-items: center; gap: .5rem; justify-content: center; }
    .role-card ul li i { color: #4caf50; font-size: .8rem; }

    /* CTA section */
    .cta-section {
      padding: 5rem 2.5rem; text-align: center;
      background: linear-gradient(145deg,#0d47a1,#0277bd);
      color: #fff;
    }
    .cta-section h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 1rem; }
    .cta-section p  { opacity: .85; font-size: 1rem; margin-bottom: 2rem; }

    /* Footer */
    footer {
      background: #0d1b4b; color: rgba(255,255,255,.6);
      text-align: center; padding: 1.5rem; font-size: .85rem;
    }
    footer a { color: #64b5f6; text-decoration: none; }

    @media (max-width: 576px) {
      .top-nav { padding: 1rem 1.25rem; }
      .hero-body { padding: 2rem 1.25rem 3rem; }
      .features-section, .roles-section, .cta-section { padding: 3rem 1.25rem; }
      .stats-strip { gap: 1.25rem; padding: 1.25rem; }
    }
  </style>
</head>
<body>

<div class="hero">
  {{-- Top nav --}}
  <nav class="top-nav">
    <a href="/" class="brand">
      <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
      {{ config('app.name', 'Admin Panel') }}
    </a>
    <div class="nav-links">
      @auth
        <a href="{{ route('home') }}" class="btn-nav-ghost">
          <i class="bi bi-speedometer2 me-1"></i>Dashboard
        </a>
      @else
        <a href="{{ route('login') }}" class="btn-nav-ghost">Sign In</a>
        @if(Route::has('register'))
          <a href="{{ route('register') }}" class="btn-nav-solid">Get Started</a>
        @endif
      @endauth
    </div>
  </nav>

  {{-- Hero body --}}
  <div class="hero-body">
    <div class="hero-content">
      <div class="hero-badge">
        <i class="bi bi-stars"></i> Role-Based Admin Panel
      </div>
      <h1 class="hero-title">
        Manage your team<br>with <span>full control</span>
      </h1>
      <p class="hero-desc">
        A feature-rich admin panel with role-based dashboards, Excel bulk import,
        background job processing, and real-time notifications — all in one place.
      </p>
      <div class="hero-cta">
        @auth
          <a href="{{ route('home') }}" class="btn-cta-primary">
            <i class="bi bi-speedometer2"></i> Go to Dashboard
          </a>
        @else
          <a href="{{ route('register') }}" class="btn-cta-primary">
            <i class="bi bi-person-plus"></i> Get Started Free
          </a>
          <a href="{{ route('login') }}" class="btn-cta-secondary">
            <i class="bi bi-box-arrow-in-right"></i> Sign In
          </a>
        @endauth
      </div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="stats-strip">
    <div class="stat-item">
      <div class="stat-num">3</div>
      <div class="stat-label">User Roles</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">1k+</div>
      <div class="stat-label">Records per Import</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">500</div>
      <div class="stat-label">Chunk Size</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">100%</div>
      <div class="stat-label">Queue-Driven</div>
    </div>
  </div>
</div>

{{-- Features --}}
<section class="features-section">
  <div style="max-width:1100px; margin:0 auto;">
    <div class="text-center mb-5">
      <div class="section-label">Features</div>
      <h2 class="section-title">Everything you need, built in</h2>
      <p class="section-desc mx-auto">From access control to bulk imports, the panel handles complex operations efficiently.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon" style="background:#e8eaf6; color:#3f51b5;">
            <i class="bi bi-person-check-fill"></i>
          </div>
          <h5>Role-Based Access</h5>
          <p>Admin, Manager, and User roles each get their own tailored dashboard with gates and policies enforced throughout.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon" style="background:#e0f7fa; color:#00838f;">
            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
          </div>
          <h5>Excel Bulk Import</h5>
          <p>Upload .xlsx/.csv files with thousands of records. Processed in background chunks of 500 for optimal performance.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon" style="background:#fce4ec; color:#c62828;">
            <i class="bi bi-bell-fill"></i>
          </div>
          <h5>Live Notifications</h5>
          <p>Get notified instantly in the panel when an import job succeeds or fails, with details stored in the database.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon" style="background:#fff3e0; color:#e65100;">
            <i class="bi bi-cpu-fill"></i>
          </div>
          <h5>Queue & Scheduler</h5>
          <p>Laravel queue with retry, backoff, and failure handling ensures every import is processed reliably.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon" style="background:#f3e5f5; color:#6a1b9a;">
            <i class="bi bi-shield-check-fill"></i>
          </div>
          <h5>Gates &amp; Policies</h5>
          <p>Fine-grained authorization using Laravel Gates and Model Policies, keeping every action secure.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon" style="background:#e8f5e9; color:#2e7d32;">
            <i class="bi bi-table"></i>
          </div>
          <h5>Optimized Records View</h5>
          <p>Paginated, searchable, filterable record list with batch management — handles thousands of rows smoothly.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Roles --}}
<section class="roles-section">
  <div style="max-width:1100px; margin:0 auto;">
    <div class="text-center mb-5">
      <div class="section-label">Access Levels</div>
      <h2 class="section-title">Three role tiers, one panel</h2>
      <p class="section-desc mx-auto">Each role gets a dedicated experience with the right tools and nothing more.</p>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4">
        <div class="role-card admin">
          <div class="role-icon"><i class="bi bi-person-gear"></i></div>
          <h5>Admin</h5>
          <ul>
            <li><i class="bi bi-check-circle-fill"></i> Full dashboard access</li>
            <li><i class="bi bi-check-circle-fill"></i> Import Excel files</li>
            <li><i class="bi bi-check-circle-fill"></i> Manage users &amp; roles</li>
            <li><i class="bi bi-check-circle-fill"></i> Delete import batches</li>
          </ul>
        </div>
      </div>
      <div class="col-md-4">
        <div class="role-card manager">
          <div class="role-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
          <h5>Manager</h5>
          <ul>
            <li><i class="bi bi-check-circle-fill"></i> Reports dashboard</li>
            <li><i class="bi bi-check-circle-fill"></i> View all records</li>
            <li><i class="bi bi-check-circle-fill"></i> Monitor team users</li>
            <li><i class="bi bi-check-circle-fill"></i> Filter &amp; search data</li>
          </ul>
        </div>
      </div>
      <div class="col-md-4">
        <div class="role-card user">
          <div class="role-icon"><i class="bi bi-person-fill"></i></div>
          <h5>User</h5>
          <ul>
            <li><i class="bi bi-check-circle-fill"></i> Personal dashboard</li>
            <li><i class="bi bi-check-circle-fill"></i> Account profile</li>
            <li><i class="bi bi-check-circle-fill"></i> Secure access only</li>
            <li><i class="bi bi-check-circle-fill"></i> Request elevated access</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div style="max-width:600px; margin:0 auto;">
    <h2>Ready to get started?</h2>
    <p>Create your account in seconds and explore the full admin panel experience.</p>
    @auth
      <a href="{{ route('home') }}" class="btn-cta-primary d-inline-flex">
        <i class="bi bi-speedometer2"></i> Open Dashboard
      </a>
    @else
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="{{ route('register') }}" class="btn-cta-primary">
          <i class="bi bi-person-plus"></i> Create Account
        </a>
        <a href="{{ route('login') }}" class="btn-cta-secondary" style="border-color:rgba(255,255,255,.5);">
          <i class="bi bi-box-arrow-in-right"></i> Sign In
        </a>
      </div>
    @endauth
  </div>
</section>

<footer>
  <p style="margin:0;">
    &copy; {{ date('Y') }} {{ config('app.name', 'Admin Panel') }} &mdash;
    Built with <a href="https://adminlte.io">AdminLTE 4</a> &amp; Laravel
  </p>
</footer>

</body>
</html>
