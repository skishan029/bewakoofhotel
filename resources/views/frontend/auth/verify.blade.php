@extends('frontend.layout.layout')
@section('title', 'Verify')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white text-center py-4">
                        <h3 class="mb-0">Verify OTP</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" autocomplete="off" id="verifyOtpForm" data-parsley-validate>
                            <input type="hidden" name="key" value="{{ $customer->otp_key }}">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="otp" class="form-label">OTP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="otp" name="otp" required
                                        autofocus>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-1">
                                <button type="submit" name="submit" class="btn primary-btn6 btn-lg">Verify</button>
                            </div>
                        </form>

                        <button type="button" class="btn btn-outline-primary mt-2" id="resendOtp">Resend OTP</button>

                        @if (env('APP_ENV') == 'local')
                            <div class="mt-3">
                                <p>OTP: {{ $customer->otp }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($verifyType == 'register')
                        <div class="card-footer bg-white text-center py-3">
                            <p class="mb-0">Already have an account? <a href="{{ route('customer.login') }}">Login
                                    here</a>
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                $('#verifyOtpForm').submit(function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);
                    let formID = 'verifyOtpForm';
                    $.ajax({
                        url: "{{ route('customer.verify-otp') }}",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        cache: false,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            setProcessingButton(formID);
                        },
                        success: function(response) {
                            window.location.href = response.data.url;
                        },
                        error: function(xhr, status, error) {
                            handleAjaxError(xhr);
                        },
                        complete: function() {
                            resetButton(formID, 'Verify');
                        }
                    });
                });

                $('#resendOtp').click(function() {
                    let formData = new FormData();
                    formData.append('key', '{{ $customer->otp_key }}');
                    $.ajax({
                        url: "{{ route('customer.resend-otp') }}",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        cache: false,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#resendOtp').html(
                                '<i class="fa fa-spinner fa-spin"></i> Resending...');
                            $('#resendOtp').prop('disabled', true);
                        },
                        success: function(response) {
                            toastr.success(response.message);
                        },
                        error: function(xhr, status, error) {
                            handleAjaxError(xhr);
                        },
                        complete: function() {
                            $('#resendOtp').html('Resend OTP');
                            $('#resendOtp').prop('disabled', false);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
