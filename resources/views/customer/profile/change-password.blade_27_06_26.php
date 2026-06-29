@extends('customer.layout.app')

@section('title', 'Change Password')

@section('content')
    <div class="row" style="max-width: 600px; margin: 0 auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0;">Update Password</h2>
            <a href="{{ route('customer.profile.update') }}" class="action-btn" style="padding: 0.5rem 1.2rem; font-size: 0.9rem; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border); color: var(--text-light); text-decoration: none;">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
        
        <div class="card mt-2">
            <form action="{{ route('customer.profile.changepassword') }}" method="POST" autocomplete="off" data-parsley-validate>
                @csrf
                <div class="form-group">
                    <label for="new_password" class="form-label">New Password <strong style="color: red;">*</strong></label>
                    <div class="position-relative">
                        <input type="password" name="new_password" id="new_password" class="form-control" required 
                            data-parsley-minlength="6" placeholder="Enter new password" style="padding-right: 2.5rem;">
                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7;" onclick="togglePasswordVisibility('new_password', this)">
                            <i class="fa-solid fa-eye text-muted"></i>
                        </span>
                    </div>

                    @error('new_password')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="new_password_confirmation" class="form-label">Confirm Password <strong style="color: red;">*</strong></label>
                    <div class="position-relative">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required 
                            data-parsley-equalto="#new_password" placeholder="Confirm new password" style="padding-right: 2.5rem;">
                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7;" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                            <i class="fa-solid fa-eye text-muted"></i>
                        </span>
                    </div>

                    @error('new_password_confirmation')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="action-btn w-100" style="text-align: center;">Change Password</button>
            </form>
        </div>
    </div>

    @push('script')
        <script>
            function togglePasswordVisibility(fieldId, iconElement) {
                const passwordField = document.getElementById(fieldId);
                const icon = iconElement.querySelector('i');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        </script>
    @endpush
@endsection
