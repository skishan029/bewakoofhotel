@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Testimonial')

@section('content')

@push('style')
    @include('admin.common.include.css.datatable')
@endpush
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="m-0">{{ empty($testimonial) ? 'Create' : 'Edit' }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="testimonial_form" data-parsley-validate enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="thumbnail">Profile photo <small>(W*H 74px*74px & max-size 2MB)</small>
                                </label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" name="thumbnail" class="custom-file-input rounded-0" id="thumbnail" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="thumbnail">Choose file</label>
                                    </div>
                                    <div class="input-group-append" id="thumbnail_div">
                                        @if (!empty($testimonial))
                                            @if (!empty($testimonial->thumbnail))
                                                <button type="button" class="btn btn-primary" value="{{ Storage::url($testimonial->thumbnail) }}" onclick="viewFeaturedPhoto(this)">View</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title">Name <strong class="text-danger">*</strong></label>
                                <input id="title" class="form-control rounded-0" type="text" name="title" value="{{ empty($testimonial) ? '' : $testimonial->title }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description <strong class="text-danger">*</strong></label>
                                <textarea name="description" id="description" rows="3" class="form-control rounded-0" required>{{ empty($testimonial) ? '' : $testimonial->description }}</textarea>
                            </div>

                            @php
                                $rating = (empty($testimonial)) ? '1' : $testimonial->rating;
                            @endphp
                            <div class="form-group">
                                <label for="rating">Rating</label>
                                <select id="rating" class="form-control rounded-0" name="rating" id="rating">
                                    @for ($i=5; $i>= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} Star</option>
                                    @endfor
                                </select>
                            </div>
                            

                            @props(['row'=> '4'])
                            <x-submitbutton :row="$row" />
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="card-title">All Food Items</h5>
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
            </div>
        </div>
    </div>
</div>

@include('admin.common.include.defultmodel')

@push('includescript')
    @include('admin.common.include.js.datatable')
@endpush

@push('script')
    @include('admin.common.include.datatables-script')
    @include('admin.common.include.module-delete-restore-js')
    <script>
        $(function () {  
        
            $('#testimonial_form').submit(function (e) { 
                e.preventDefault();
                if ($('#testimonial_form').parsley().isValid()) {
                    const formData = new FormData(this);
                    const formID = 'testimonial_form';

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

                                @if (empty($testimonial))
                                    setSuccessButton(response, formID, 'Submit');
                                @else
                                    setUpdateSuccessButton(response, formID, 'Submit');

                                    thumbnail = response.thumbnail;
                                    if (thumbnail != null) {
                                        htmlString = `<button type="button" class="btn btn-primary" value="${thumbnail}" onclick="viewFeaturedPhoto(this)">View</button>`;
                                        $('#thumbnail_div').html(htmlString);
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