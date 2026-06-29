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
                        <h5 class="m-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="change_password" data-parsley-validate>
                            <div class="form-group">
                                <label for="password">New Password <strong class="text-danger">*</strong></label>
                                <input type="password" name="password" id="password" class="form-control rounded-0 register-pswd" required="" minlength="6">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password <strong class="text-danger">*</strong></label>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control register-cnfm-pswd rounded-0" required="" data-parsley-equalto="#password">
                            </div>
                            <div class="form-group custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="exampleCheck2" value="2" onclick="myFunction(this.value)" >
                                <label class="custom-control-label" for="exampleCheck2">Show Password</label>
                            </div>

                            @props(['btnclass' => 'btn-primary', 'row'=> '4'])
                            <x-submitbutton :btnclass="$btnclass" :row="$row"/>
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
    </script>
@endpush

@endsection