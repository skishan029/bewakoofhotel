@extends('admin.common.layout')
@section('title', 'Change Password')
@section('module_title', 'Profile')

@section('content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="m-0">Employee Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="change_password" data-parsley-validate>

                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control" type="email" id="email" name="email" placeholder="Enter Email ID"  required  data-parsley-errors-container="#email_error">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" onclick="searchEmailID()">Search</button>
                                    </div>
                                </div>
                                <div id="email_error"></div>
                            </div>                             
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('#change_password').submit(function (e) { 
                e.preventDefault();
                const formID = 'change_password';
                
                $.ajax({
                    type: "POST",
                    url: "{{ $submitURL }}",
                    data: new FormData(this),
                    dataType: "JSON",
                    processData: false,
                    contentType : false,
                    cache: false,
                    beforeSend: function(){
                        setProcessingButton(formID);
                    },
                    success: function (response) {
                        if (response.type == 'success') {
                            setSuccessButton(response, formID);
                        } else {
                            resetButton(formID);
                            setErrorMessage(response);
                        }
                    }
                });
            });
        });
        function myFunction(int) {
            //alert(int);
            const x = document.getElementsByClassName('login-pswd')[0];
            const y = document.getElementsByClassName('register-pswd')[0];
            const z = document.getElementsByClassName('register-cnfm-pswd')[0];
            if(int === '1'){
                //alert('1');
                if(x.type === "password"){
                    x.type = "text";
                }
                else{
                    x.type = "password";
                }
            }
            if(int === '2'){
                if(y.type === "password"){
                    y.type = "text";
                    z.type = "text";
                }
                else{
                    y.type = "password";
                    z.type = "password";
                }
            }
        }

        function searchEmailID() {
            $('#email').parsley().validate();
            if ($('#email').parsley().isValid()) {
                const email = $('#email').val();

                // swal({
                //     title: "Are you sure?",
                //     //text: "Submit to run ajax request",
                //     type: "info",
                //     showCancelButton: true,
                //     closeOnConfirm: false,
                //     showLoaderOnConfirm: true
                // }, function () {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.profile.emppassword') }}",
                        data: {email:email},
                        dataType: "JSON",
                        success: function (response) {
                            if (response.type == 'success') {
                                swal.close();
                                $('#change_password').html(response.html);
                            } else {
                                $('#change_password').parsley().reset();
                                swal.close();
                                setErrorMessage(response);
                            }
                        }
                    });
                // });
            }
        }


    </script>
@endpush

@endsection