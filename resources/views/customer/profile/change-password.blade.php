@extends('frontend.layout.layout')
@section('title', $title)

@section('content')

@include('frontend.layout.breadcrumb')
    <div class="container">
        <div class="reservation-2 mb-20 mt-40">
            <div class="row d-flex align-items-center justify-content-center mb-20">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        @if (session('success'))
                            <span>
                                {{ session('success') }}
                            </span>
                        @endif
                        @if (session('error'))
                            <span class="alert alert-error">
                                {{ session('error') }}
                            </span>
                        @endif
                        <h2>Update Password</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('customer.profile.changepassword') }}" method="POST" autocomplete="off" data-parsley-validate>
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <div class="position-relative">
                                        <input type="password" id="new_password" name="new_password" data-parsley-minlength="6" placeholder="New Password*" required>
                                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7; z-index: 10;" onclick="togglePasswordVisibility('new_password', this)">
                                            <i class="bi-eye"></i>
                                        </span>
                                    </div>
                                    @error('new_password')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                    <div class="position-relative">
                                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" data-parsley-equalto="#new_password" placeholder="Confirm new password*" required>
                                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7; z-index: 10;" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                                            <i class="bi-eye"></i>
                                        </span>
                                    </div>
                                    @error('new_password_confirmation')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                    <button type="submit" name="submit" class="btn primary-btn6 btn-lg">Change Password</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="text-center login-link-area">
                                    <p class="mb-0"><a href="{{ route('customer.profile.update') }}" class="login-link">Back to Profile</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function togglePasswordVisibility(fieldId, iconElement) {
                const passwordField = document.getElementById(fieldId);
                const icon = iconElement.querySelector('i');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        </script>
    @endpush
@endsection
