@extends('frontend.layout.layout')
@section('title', 'Login')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white text-center py-4">
                        <h3 class="mb-0">Change Password</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" id="loginForm" data-parsley-validate autocomplete="off">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required autofocus maxlength="10" minlength="10" pattern="[6-9]{1}[0-9]{9}" inputmode="numeric" placeholder="Enter 10 digit mobile number">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="submit" class="btn primary-btn6 btn-lg">Send Password</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white text-center py-3">
                        <p class="mb-0"><a href="{{ route('customer.login') }}">Login here</a></p>
                        
                    </div>
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
