@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Contact US image')

@section('content')

@push('style')
    {{-- @include('admin.common.include.css.datatable') --}}
@endpush
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="m-0">{{ empty($contactusimages) ? 'Upload' : 'Edit Upload' }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="appslider_form" data-parsley-validate enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="front_image">Front Image
                                    @if (empty($contactusimages))
                                        <strong class="text-danger">*</strong>
                                    @endif
                                    
                                    <small>(W*H 338px*332px & max-size 2MB)</small>
                                </label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" name="front_image" class="custom-file-input rounded-0" id="front_image" accept=".png,.jpeg,.jpg" @required(empty($contactusimages)) >
                                        <label class="custom-file-label" for="front_image">Choose file</label>
                                    </div>
                                    <div class="input-group-append" id="front_image_div">
                                        @if (!empty($contactusimages))
                                            @if (!empty($contactusimages->front_image))
                                                <button type="button" class="btn btn-primary" value="{{ Storage::url($contactusimages->front_image) }}" onclick="viewFeaturedPhoto(this)">View</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="background_image">Background Image
                                    @if (empty($contactusimages))
                                        <strong class="text-danger">*</strong>
                                    @endif
                                    <small>(W*H 1170px*546px & max-size 2MB)</small>
                                </label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" name="background_image" class="custom-file-input rounded-0" id="background_image" accept=".png,.jpeg,.jpg" @required(empty($contactusimages)) >
                                        <label class="custom-file-label" for="background_image">Choose file</label>
                                    </div>
                                    <div class="input-group-append" id="background_image_div">
                                        @if (!empty($contactusimages))
                                            @if (!empty($contactusimages->background_image))
                                                <button type="button" class="btn btn-primary" value="{{ Storage::url($contactusimages->background_image) }}" onclick="viewFeaturedPhoto(this)">View</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>                            

                            @props(['row'=> '4'])
                            <x-submitbutton :row="$row" />
                        </form>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-7">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="card-title">All Sliders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        @foreach ($table as $key=> $var)
                                            @if ($key == 'action')
                                                <th width="5%" class="text-center">Action</th>
                                            @else
                                                <th>{{ $var }}</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>

@include('admin.common.include.defultmodel')

@push('includescript')
    {{-- @include('admin.common.include.js.datatable') --}}
@endpush

@push('script')
    {{-- @include('admin.common.include.datatables-script') --}}
    <script>
        $(function () {  
        
            $('#appslider_form').submit(function (e) { 
                e.preventDefault();
                if ($('#appslider_form').parsley().isValid()) {
                    const formData = new FormData(this);
                    const formID = 'appslider_form';

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

                                alert(response.message);
                                window.location.reload();
                            }else if(response.type == 'error'){
                                resetButton(formID, 'Submit');
                                setErrorMessage(response);
                            }
                        }
                    });
                }
            });
        });

        // function deleteAppSlider(evt) {
        //     if (evt.value != '') {
        //         swal({
        //             title: "Are you sure?",
        //             text: "Your will not be able to recover this imaginary file!",
        //             type: "warning",
        //             showCancelButton: true,
        //             confirmButtonClass: "btn-danger",
        //             confirmButtonText: "Yes, delete it!",
        //             closeOnConfirm: false,
        //             showLoaderOnConfirm: true
        //         },
        //         function(){
        //             $.ajax({
        //                 type: "POST",
        //                 url: "{{ route('admin.frontend.slider.delete') }}",
        //                 data: {id:evt.value},
        //                 success: function (response) {
        //                     if (response.type == 'success') {
        //                         swal("", response.message, "success");
        //                         $('#dataTable').DataTable().ajax.reload();
        //                     } else {
        //                         swal.close();
        //                         setErrorMessage(response);
        //                     } 
        //                 }
        //             }); 
        //         });
        //     }
        // }

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