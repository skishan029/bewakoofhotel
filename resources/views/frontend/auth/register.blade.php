@extends('frontend.layout.layout')
@section('title', $title)

@section('content')

@include('frontend.layout.breadcrumb')
    <!-- <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white text-center py-4">
                        <h3 class="mb-0">Customer Registration</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" autocomplete="off" id="registerForm" autocomplete="off"
                            data-parsley-validate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        autofocus>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="username" name="username" required
                                        maxlength="10" minlength="10" pattern="[6-9]{1}[0-9]{9}" inputmode="numeric" placeholder="Enter 10 digit mobile number">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Password<span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="password" name="password" required autofocus>
                                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7; z-index: 10;" onclick="togglePasswordVisibility('password', this)"><i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                                            minlength="6" data-parsley-equalto="#password">
                                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7; z-index: 10;" onclick="togglePasswordVisibility('confirm_password', this)"><i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" name="submit" class="btn primary-btn6 btn-lg">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white text-center py-3">
                        <p class="mb-0">Already have an account? <a href="{{ route('customer.login') }}">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="container">
        <div class="reservation-2 mb-20 mt-40">
            <div class="row d-flex align-items-center justify-content-center mb-15">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <h2>For Online Registration</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="" method="POST" autocomplete="off" id="registerForm" autocomplete="off" data-parsley-validate>
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <input type="text" id="name" name="name" placeholder="Full Name*" required  autofocus>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <input type="tel" id="username" name="username" required maxlength="10" minlength="10" pattern="[6-9]{1}[0-9]{9}" inputmode="numeric" placeholder="Enter 10 digit mobile number*" autofocus>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <input type="email" id="email" name="email" placeholder="Email*" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <div class="position-relative">
                                        <input type="password" id="password" name="password" placeholder="Enter Password*" required autofocus>
                                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7; z-index: 10;" onclick="togglePasswordVisibility('password', this)"><i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" data-parsley-equalto="#password" placeholder="Confirm Password*">
                                        <span class="position-absolute end-0 top-50 translate-middle-y me-3" style="cursor: pointer; opacity: 0.7; z-index: 10;" onclick="togglePasswordVisibility('confirm_password', this)"><i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                    <button type="submit" name="submit">Register Now</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="text-center login-link-area">
                                    <p class="mb-0">Already have an account?<a href="{{ route('customer.login') }}" class="login-link">Login Here</a></p>
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
            $(function() {
                $('#registerForm').submit(function(e) {
                    e.preventDefault();
                    if ($('#registerForm').parsley().isValid()) {
                        formID = 'registerForm';
                        $.ajax({
                            type: "POST",
                            url: "{{ route('customer.register') }}",
                            data: new FormData(this),
                            dataType: "JSON",
                            cache: false,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                setProcessingButton(formID);
                            },
                            success: function(response) {
                                alert(response.message);
                                window.location.href = response.data.url;
                            },
                            error: function(xhr, status, error) {
                                handleAjaxError(xhr);
                            },
                            complete: function() {
                                resetButton(formID, 'Register');
                            }
                        });
                    }
                });
            });
            
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
