@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Employee')

@section('content')


<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="m-0">{{ empty($employee) ? 'Employee' : 'Edit Employee' }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="appemployee_form" data-parsley-validate enctype="multipart/form-data" >
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="emp_name">Name <strong class="text-danger">*</strong> </label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="text" name="emp_name" class="form-control rounded-0" id="emp_name" value="{{ (empty($employee)) ? '' : $employee->emp_name ; }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="contact_no">Contact No.</label>
                                        <input id="contact_no" class="form-control rounded-0" type="text" name="contact_no" value="{{ (empty($employee)) ? '' : $employee->contact_no ; }}" pattern="/^[0-9]{10}$/" minlength="10" maxlength="10" onkeypress="return onlyNumberKey(event)">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="salary">Salary</label>
                                        <input id="salary" class="form-control rounded-0" type="number" name="salary" value="{{ empty($employee) ? '' : $employee->salary }}" min="0" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input id="address" class="form-control rounded-0" type="text" name="address" value="{{ (empty($employee)) ? '' : $employee->address ; }}" min="12">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="aadhar_no">Aadhar No.</label>
                                        <input id="aadhar_no" class="form-control rounded-0" type="text" name="aadhar_no" value="{{ (empty($employee)) ? '' : $employee->aadhar_no ; }}" onkeypress="return isNumber(event)" maxlength="12" minlength="12">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="profile_photo">Profile Photo<small>(W*H 292px*292px & max-size 2MB)</small></label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" name="profile_photo" class="custom-file-input rounded-0" id="profile_photo" accept=".png,.jpeg,.jpg">
                                                <label class="custom-file-label" for="profile_photo">Choose file</label>
                                            </div>
                                            <div class="input-group-append" id="profile_photo_div">
                                                @if (!empty($employee))
                                                    @if (!empty($employee->profile_photo))
                                                        <button type="button" class="btn btn-primary" value="{{ Storage::url($employee->profile_photo) }}" onclick="viewFeaturedPhoto(this)">View</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @props(['row'=> '2'])
                            <x-submitbutton :row="$row" />
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

@include('admin.common.include.defultmodel')
@push('script')

    <script>
        $(function () {  
        
            $('#appemployee_form').submit(function (e) { 
                e.preventDefault();
                if ($('#appemployee_form').parsley().isValid()) {
                    const formData = new FormData(this);
                    const formID = 'appemployee_form';

                    $.ajax({
                        type: "POST",
                        url: "{{ $submitURL }}",
                        data: formData,
                        processData: false,
                        contentType : false,
                        cache: false,
                        beforeSend: function () {  
                            setProcessingButton(formID);
                        },
                        success: function (response) {
                            if (response.type == 'success') {

                                @if (empty($employee))
                                    setSuccessButton(response, formID, 'Submit');
                                @else
                                    setUpdateSuccessButton(response, formID, 'Submit');
                                    profile_photo = response.profile_photo;
                                    if (profile_photo != null) {
                                        htmlString = `<button type="button" class="btn btn-primary" value="${profile_photo}" onclick="viewFeaturedPhoto(this)">View</button>`;
                                        $('#profile_photo_div').html(htmlString);
                                    }

                                @endif
                                $('#dataTable').DataTable().ajax.reload();
                            }else if(response.type == 'error'){
                                resetButton(formID, 'Submit');
                                setErrorMessage(response);
                            }
                        }
                    });
                }
            });
        });

        function deleteAppSlider(evt) {
            if (evt.value != '') {
                swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function(){
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.expence.delete') }}",
                        data: {id:evt.value},
                        success: function (response) {
                            if (response.type == 'success') {
                                swal("", response.message, "success");
                                $('#dataTable').DataTable().ajax.reload();
                            } else {
                                swal.close();
                                setErrorMessage(response);
                            } 
                        }
                    }); 
                });
            }
        }

        function viewFeaturedPhoto(evt) {
            path = evt.value;
            if (path !== '') {

                $('#defaultmodal-size').removeClass();
                $('#defaultmodal-size').addClass('modal-dialog');
                $('#defaultmodal').modal('show');
                htmlString = `<img src="${path}" class="img-thumbnail" alt="no-image" style="height: 300px; width: 465px;" >`;
                $('#defaultmodal-body').html(htmlString);
            }
        }
    </script>
@endpush

@endsection