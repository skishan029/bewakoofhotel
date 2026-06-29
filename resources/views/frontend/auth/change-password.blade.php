@extends('frontend.layout.layout')
@section('title', $title)

@section('content')

@include('frontend.layout.breadcrumb')

    <div class="container">
        <div class="reservation-2 mb-20 mt-40">
            <div class="row d-flex align-items-center justify-content-center mb-15">
                <div class="col-lg-8">
                    <div class="section-title text-center"><h2>Forget Password</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="" method="POST" id="loginForm" data-parsley-validate autocomplete="off">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                    <input type="text" id="username" name="username" required autofocus maxlength="10" minlength="10" pattern="[6-9]{1}[0-9]{9}" inputmode="numeric" placeholder="Enter 10 digit mobile number*">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                    <button type="submit" name="submit" class="btn primary-btn6 btn-lg">Send Password</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="text-center login-link-area">
                                    <p class="mb-0">Do you have an account? <a href="{{ route('customer.login') }}" class="login-link">Login Here</a></p>
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
                $('#loginForm').submit(function(e) {
                    e.preventDefault();
                    if ($('#loginForm').parsley().isValid()) {
                        formID = 'loginForm';
                        $.ajax({
                            type: "POST",
                            url: "{{ route('customer.change-password') }}",
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
                            },
                            error: function(xhr, status, error) {
                                handleAjaxError(xhr);
                            },
                            complete: function() {
                                resetButton(formID, 'Login');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
