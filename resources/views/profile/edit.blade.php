@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">

  {{-- Page header --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class="mb-0 fw-bold">My Profile</h4>
      <small class="text-muted">Manage your account information and security settings</small>
    </div>
    <span class="badge bg-primary fs-6">{{ $user->role_name }}</span>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
      <i class="bi bi-check-circle-fill fs-5"></i>
      <span>{{ session('success') }}</span>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-4">

    {{-- Left: Avatar card --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center p-4">

          <div class="position-relative d-inline-block mb-3">
            <img id="avatarPreview"
                 src="{{ $user->avatar_url }}"
                 alt="{{ $user->name }}"
                 class="rounded-circle shadow"
                 style="width:120px;height:120px;object-fit:cover;border:4px solid var(--bs-primary);" />
            <label for="avatarInput"
                   class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                   style="width:34px;height:34px;cursor:pointer;border:2px solid var(--bs-body-bg);"
                   title="Change photo">
              <i class="bi bi-camera-fill" style="font-size:.85rem;"></i>
            </label>
          </div>

          <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
          <p class="text-muted mb-1 small">{{ $user->email }}</p>
          @if($user->phone)
            <p class="text-muted mb-2 small"><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</p>
          @endif

          <div class="d-flex gap-2 justify-content-center mt-3">
            @if($user->avatar)
              <form method="POST" action="{{ route('profile.remove-avatar') }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Remove profile photo?')">
                  <i class="bi bi-trash me-1"></i>Remove Photo
                </button>
              </form>
            @endif
          </div>

          <hr class="my-3">
          <div class="text-start small text-muted">
            <div class="d-flex justify-content-between py-1 border-bottom">
              <span><i class="bi bi-person-badge me-2"></i>Role</span>
              <strong class="text-body">{{ $user->role_name }}</strong>
            </div>
            <div class="d-flex justify-content-between py-1 border-bottom">
              <span><i class="bi bi-calendar-check me-2"></i>Joined</span>
              <strong class="text-body">{{ $user->created_at->format('M d, Y') }}</strong>
            </div>
            <div class="d-flex justify-content-between py-1">
              <span><i class="bi bi-envelope-check me-2"></i>Email status</span>
              <strong class="{{ $user->email_verified_at ? 'text-success' : 'text-warning' }}">
                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
              </strong>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Right: Edit form --}}
    <div class="col-lg-8">
      <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Hidden avatar input triggered by camera icon --}}
        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
        @error('avatar') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

        {{-- Personal info card --}}
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
            <i class="bi bi-person-fill text-primary fs-5"></i>
            <h6 class="mb-0 fw-semibold">Personal Information</h6>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person"></i></span>
                  <input type="text" name="name" value="{{ old('name', $user->name) }}"
                         class="form-control @error('name') is-invalid @enderror"
                         placeholder="Your full name" required>
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  <input type="email" name="email" value="{{ old('email', $user->email) }}"
                         class="form-control @error('email') is-invalid @enderror"
                         placeholder="your@email.com" required>
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Phone Number</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                  <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                         class="form-control @error('phone') is-invalid @enderror"
                         placeholder="+1 234 567 8900">
                  @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Change password card --}}
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 py-3">
            <i class="bi bi-shield-lock-fill text-warning fs-5"></i>
            <h6 class="mb-0 fw-semibold">Change Password</h6>
            <small class="text-muted ms-auto">Leave blank to keep current password</small>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-medium">Current Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock"></i></span>
                  <input type="password" name="current_password" id="currentPwd"
                         class="form-control @error('current_password') is-invalid @enderror"
                         placeholder="Enter current password">
                  <button class="btn btn-outline-secondary" type="button"
                          onclick="togglePwd('currentPwd', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                  @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">New Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="password" id="newPwd"
                         class="form-control @error('password') is-invalid @enderror"
                         placeholder="Min. 8 characters">
                  <button class="btn btn-outline-secondary" type="button"
                          onclick="togglePwd('newPwd', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                  @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Confirm New Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="password_confirmation" id="confirmPwd"
                         class="form-control"
                         placeholder="Repeat new password">
                  <button class="btn btn-outline-secondary" type="button"
                          onclick="togglePwd('confirmPwd', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i>Cancel
          </a>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>Save Changes
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
// Live avatar preview
document.getElementById('avatarInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('avatarPreview').src = e.target.result;
  };
  reader.readAsDataURL(file);
  // Auto-submit form after picking image — or let user click Save
});

// Password eye toggle
function togglePwd(id, btn) {
  const field = document.getElementById(id);
  const icon  = btn.querySelector('i');
  const show  = field.type === 'password';
  field.type  = show ? 'text' : 'password';
  icon.classList.toggle('bi-eye-slash', !show);
  icon.classList.toggle('bi-eye', show);
}
</script>
@endsection
