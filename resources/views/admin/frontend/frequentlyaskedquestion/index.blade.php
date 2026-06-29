@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'FAQ')

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
                        <h5 class="m-0">{{ empty($frequentlyAskedQuestion) ? 'Create' : 'Edit' }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="faq_form" data-parsley-validate enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="question">Question <strong class="text-danger">*</strong></label>
                                <textarea name="question" id="question" rows="2" class="form-control rounded-0" required>{{ empty($frequentlyAskedQuestion) ? '' : $frequentlyAskedQuestion->question }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="answer">Answer <strong class="text-danger">*</strong></label>
                                <textarea name="answer" id="answer" rows="4" class="form-control rounded-0" required>{{ empty($frequentlyAskedQuestion) ? '' : $frequentlyAskedQuestion->answer }}</textarea>
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
                        <h5 class="card-title">All FAQ's</h5>
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
        
            $('#faq_form').submit(function (e) { 
                e.preventDefault();
                if ($('#faq_form').parsley().isValid()) {
                    const formData = new FormData(this);
                    const formID = 'faq_form';

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

                                @if (empty($frequentlyAskedQuestion))
                                    setSuccessButton(response, formID, 'Submit');
                                @else
                                    setUpdateSuccessButton(response, formID, 'Submit');
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
    </script>
@endpush

@endsection