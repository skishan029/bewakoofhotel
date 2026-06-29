@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Offer')

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
                        <h5 class="m-0">{{ empty($slider) ? 'Upload' : 'Edit Upload' }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="appslider_form" data-parsley-validate enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="offer_image">Font Image
                                    @if (empty($offerdiscounts))
                                        <strong class="text-danger">*</strong>
                                    @endif
                                    
                                    <small>(W*H 300px*300px & max-size 2MB)</small>
                                </label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" name="offer_image" class="custom-file-input rounded-0" id="offer_image" accept=".png,.jpeg,.jpg" @required(empty($offerdiscounts)) >
                                        <label class="custom-file-label" for="offer_image">Choose file</label>
                                    </div>
                                    <div class="input-group-append" id="offer_image_div">
                                        @if (!empty($offerdiscounts))
                                            @if (!empty($offerdiscounts->offer_image))
                                                <button type="button" class="btn btn-primary" value="{{ Storage::url($offerdiscounts->offer_image) }}" onclick="viewFeaturedPhoto(this)">View</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="back_image">Background Image
                                    @if (empty($offerdiscounts))
                                        <strong class="text-danger">*</strong>
                                    @endif
                                    <small>(W*H 416px*414px & max-size 2MB)</small>
                                </label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" name="back_image" class="custom-file-input rounded-0" id="back_image" accept=".png,.jpeg,.jpg" @required(empty($offerdiscounts)) >
                                        <label class="custom-file-label" for="back_image">Choose file</label>
                                    </div>
                                    <div class="input-group-append" id="back_image_div">
                                        @if (!empty($offerdiscounts))
                                            @if (!empty($offerdiscounts->back_image))
                                                <button type="button" class="btn btn-primary" value="{{ Storage::url($offerdiscounts->back_image) }}" onclick="viewFeaturedPhoto(this)">View</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="percent">Percentage</label>
                                <input id="percent" class="form-control rounded-0" type="text" name="percent" value="{{ empty($offerdiscounts) ? '' : $offerdiscounts->percent }}">
                            </div>
                            <div class="form-group">
                                <label for="offer_text">Offer Text</label>
                                <input id="offer_text" class="form-control rounded-0" type="text" name="offer_text" value="{{ empty($offerdiscounts) ? '' : $offerdiscounts->offer_text }}">
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