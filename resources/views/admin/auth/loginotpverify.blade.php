<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env("APP_NAME") .' | Login OTP Verify' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- favicon ============================================ -->		
    <link rel="shortcut icon" type="image/x-icon" href="{{ Helper::faviconLogo() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- admin style -->
    <link rel="stylesheet" href="{{ asset('assests/admin/dist/css/adminlte.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/toastr/toastr.min.css') }}">

    <!-- Parsley -->
    <link rel="stylesheet" href="{{ asset('assests/parsley/parsley.css') }}">


    <style type="text/css">
        input.is-invalid{
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-repeat: no-repeat;
            background-position: center right calc(0.375em + 0.1875rem);
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        input.is-invalid:focus{
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        input.is-valid{
            border-color: #28a745;
            padding-right: calc(1.5em + 0.75rem);
            background-repeat: no-repeat;
            background-position: center right calc(0.375em + 0.1875rem);
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        input.is-valid:focus{
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .remove-bottom-border{
            border-bottom: none;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
    <!-- /.login-logo -->       
    
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="{{ Helper::siteLogo() }}" alt="" >
            </div>
            <div class="card-header text-center remove-bottom-border">
                <a href="{{ route('admin.index') }}" class="h4"><b>OTP</b></a>
            </div>
            <div class="card-body">
                <form action="" method="post" id="form_login_otp">
                    {{-- <p>OTP - {{$user->otp}}</p> --}}
                    <div class="input-group">
                        <input type="text" class="form-control rounded-0" placeholder="Enter OTP" required="" name="otp" data-parsley-errors-container="#otp_message">
                        <div class="input-group-append">
                            <div class="input-group-text rounded-0">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div id="otp_message" class="mb-3"></div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-flat btn-block">Submit</button>
                        </div>
                    </div>
                </form>
                {{-- <p class="mb-1">
                    <a href="#" class="text-primary">I forgot my password</a>
                </p> --}}
            </div>
            <!-- /.card-body -->
        </div>
    <!-- /.card -->
    </div>
    <!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('assests/admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assests/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assests/admin/dist/js/adminlte.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('assests/admin/plugins/toastr/toastr.min.js') }}"></script>
<!-- Parsley JS -->
<script src="{{ asset('assests/parsley/parsley.min.js') }}"></script>

<!-- Form script -->
<script src="{{ asset('assests/from/formscript.js')}}"></script>

<script>

    $(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#form_login_otp').parsley({
      
            trigger: "change",
            successClass: "is-valid", //is-valid
            errorClass: "is-invalid", //is-invalid *
            classHandler:function( el ){
                return el.$element.closest('.form-control');
            }, 
        }); 

        $('#form_login_otp').submit(function (e) { 
            e.preventDefault();
            if ( $('#form_login_otp').parsley().isValid()  ) {
                
                formID = 'form_login_otp';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.verify',['otp_key'=> $user->otp_key]) }}",
                    data: new FormData(this),
                    dataType: "JSON",
                    cache:false,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        setProcessingButton(formID);
                    },
                    success: function (response) {
                        if (response.type == 'success') {
                            window.location.href = response.url;
                        } else {
                            resetButton(formID, 'Submit');
                            setErrorMessage(response);
                        }
                    }
                });
            }
        });
    });

 
</script>
</body>
</html>
