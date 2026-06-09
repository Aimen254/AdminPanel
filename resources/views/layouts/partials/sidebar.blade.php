<!--begin::Navbar-->
<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">
      {{-- Notifications --}}
      @auth
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#" id="notifBell">
          <i class="bi bi-bell-fill"></i>
          @if(auth()->user()->unreadNotifications->count())
            <span class="navbar-badge badge text-bg-warning" id="notifBadge">{{ auth()->user()->unreadNotifications->count() }}</span>
          @else
            <span class="navbar-badge badge text-bg-warning d-none" id="notifBadge">0</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" id="notifDropdown">
          <span class="dropdown-item dropdown-header" id="notifHeader">
            {{ auth()->user()->unreadNotifications->count() }} Notifications
          </span>
          <div class="dropdown-divider"></div>
          @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
            <a href="#" class="dropdown-item">
              @php $notifType = $notification->data['type'] ?? ''; @endphp
              @if($notifType === 'import_completed')
                <i class="bi bi-check-circle-fill text-success me-2"></i>
              @else
                <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>
              @endif
              {{ $notification->data['message'] ?? '' }}
              <span class="float-end text-secondary fs-7">{{ $notification->created_at->diffForHumans() }}</span>
            </a>
            <div class="dropdown-divider"></div>
          @empty
            <a href="#" class="dropdown-item text-center text-muted">No new notifications</a>
            <div class="dropdown-divider"></div>
          @endforelse
          <a href="#" class="dropdown-item dropdown-footer"
             onclick="event.preventDefault(); markAllRead()">
            Mark all as read
          </a>
        </div>
      </li>
      @endauth

      {{-- Dark mode toggle --}}
      <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="bd-theme" aria-label="Toggle color scheme"
           data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
          <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
          <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme" style="--bs-dropdown-min-width:8rem">
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light">
              <i class="bi bi-sun-fill me-2"></i> Light <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
              <i class="bi bi-moon-fill me-2"></i> Dark <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto">
              <i class="bi bi-circle-half me-2"></i> Auto <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
        </ul>
      </li>

      {{-- User menu --}}
      @auth
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <img src="{{ auth()->user()->avatar_url }}"
               class="user-image rounded-circle shadow" alt="{{ auth()->user()->name }}"
               style="width:32px;height:32px;object-fit:cover;" />
          <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header text-bg-primary">
            <img src="{{ auth()->user()->avatar_url }}"
                 class="rounded-circle shadow" alt="{{ auth()->user()->name }}"
                 style="width:90px;height:90px;object-fit:cover;" />
            <p>
              {{ auth()->user()->name }}
              <small>{{ auth()->user()->role_name }}</small>
              <small>{{ auth()->user()->email }}</small>
            </p>
          </li>
          <li class="user-footer">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-person-gear me-1"></i>Profile
            </a>
            <button type="button" class="btn btn-outline-danger btn-sm float-end" onclick="confirmLogout()">
              <i class="bi bi-box-arrow-right me-1"></i>Sign out
            </button>
          </li>
        </ul>
      </li>
      @endauth
    </ul>
  </div>
</nav>
<!--end::Navbar-->

<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="{{ auth()->check() ? route('home') : '/' }}" class="brand-link">
      <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image opacity-75 shadow" />
      <span class="brand-text fw-light">Admin Panel</span>
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">

        {{-- Dashboard --}}
        <li class="nav-header">MAIN</li>
        <li class="nav-item">
          @auth
            @if(auth()->user()->hasRole('admin'))
              <a href="{{ route('admin.dashboard') }}"
                 class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            @elseif(auth()->user()->hasRole('manager'))
              <a href="{{ route('manager.dashboard') }}"
                 class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
            @else
              <a href="{{ route('user.dashboard') }}"
                 class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            @endif
          @endauth
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- Admin-only section --}}
        @can('manage-users')
        <li class="nav-header">ADMIN</li>

        <li class="nav-item {{ request()->routeIs('admin.import.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('admin.import.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-file-earmark-spreadsheet"></i>
            <p>Import Data <i class="nav-arrow bi bi-chevron-right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.import.index') }}"
                 class="nav-link {{ request()->routeIs('admin.import.index') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Upload Excel</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.import.records') }}"
                 class="nav-link {{ request()->routeIs('admin.import.records') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>View Records</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan

        {{-- Manager + Admin section --}}
        @can('view-reports')
        <li class="nav-header">MANAGEMENT</li>
        <li class="nav-item">
          <a href="{{ route('manager.dashboard') }}"
             class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-bar-chart-line"></i>
            <p>Reports</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.import.records') }}"
             class="nav-link {{ request()->routeIs('admin.import.records') ? 'active' : '' }}">
            <i class="nav-icon bi bi-table"></i>
            <p>Records</p>
          </a>
        </li>
        @endcan

        {{-- All users --}}
        <li class="nav-header">ACCOUNT</li>
        <li class="nav-item">
          <a href="{{ route('profile.edit') }}"
             class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="nav-icon bi bi-person-circle"></i>
            <p>My Profile</p>
          </a>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link w-100 text-start border-0 bg-transparent" onclick="confirmLogout()">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p>Logout</p>
          </button>
        </li>

      </ul>
    </nav>
  </div>
</aside>
<!--end::Sidebar-->

{{-- Shared logout form --}}
<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
  @csrf
</form>

<script>
// ── Logout confirmation ──────────────────────────────────────────────────────
function confirmLogout() {
  Swal.fire({
    title: 'Sign out?',
    text: 'You will be logged out of your account.',
    icon: 'question',
    iconColor: getComputedStyle(document.documentElement).getPropertyValue('--bs-primary').trim() || '#0d6efd',
    showCancelButton: true,
    confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Yes, sign out',
    cancelButtonText: 'Cancel',
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    reverseButtons: true,
    focusCancel: true,
    customClass: { popup: 'rounded-3 shadow', title: 'fw-bold fs-5', htmlContainer: 'text-muted' },
  }).then((result) => {
    if (result.isConfirmed) document.getElementById('logout-form').submit();
  });
}

// ── Mark all notifications read ──────────────────────────────────────────────
function markAllRead() {
  fetch('{{ route("notifications.read") }}', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
  }).then(() => {
    document.getElementById('notifBadge').classList.add('d-none');
    document.getElementById('notifHeader').textContent = '0 Notifications';
  });
}

// ── Frontend polling: show SweetAlert2 toast when new notification arrives ───
@auth
(function () {
  let knownCount = {{ auth()->user()->unreadNotifications->count() }};

  async function pollNotifications() {
    try {
      const res  = await fetch('{{ route("notifications.poll") }}', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      });
      const data = await res.json();

      if (data.count > knownCount) {
        const newOnes = data.latest.slice(0, data.count - knownCount);
        newOnes.forEach(n => {
          const isOk = n.type === 'import_completed';
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: isOk ? 'success' : 'error',
            title: isOk ? 'Import Completed' : 'Import Failed',
            text: n.message,
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
          });
        });

        // Update bell badge
        const badge = document.getElementById('notifBadge');
        badge.textContent = data.count;
        badge.classList.remove('d-none');
        document.getElementById('notifHeader').textContent = data.count + ' Notifications';

        knownCount = data.count;
      }
    } catch (_) {}
  }

  // Start polling every 15 seconds after page load
  setTimeout(() => setInterval(pollNotifications, 15000), 5000);
})();
@endauth
</script>
