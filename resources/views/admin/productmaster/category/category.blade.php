@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Product Master')

@section('content')
    <!-- Main content -->

    @push('style')
        @include('admin.common.include.css.datatable')
    @endpush

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="{{ Helper::adminCardClass() }}">
                        <div class="card-header">
                            <h5 class="m-0">{{ empty($productCategory) ? 'Create' : 'Edit' }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="category_form" data-parsley-validate>
                                <div class="form-group">
                                    <label for="cat_title">Category Title <strong class="text-danger">*</strong></label>
                                    <input id="cat_title" class="form-control rounded-0" type="text" name="cat_title"
                                        required value="{{ empty($productCategory) ? '' : $productCategory->cat_title }}">
                                </div>
                                <div class="form-group">
                                    <label for="cat_desc">Category Description</label>
                                    <textarea id="cat_desc" class="form-control rounded-0" name="cat_desc" rows="3">{{ empty($productCategory) ? '' : $productCategory->cat_desc }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="cat_thumbnail">Category Thumbnail <small>(only jpeg,png,jpeg,webp and max
                                            size
                                            2MB)</small></label>
                                    <div class="input-group mb-3">
                                        <input id="cat_thumbnail" class="form-control rounded-0" type="file"
                                            name="cat_thumbnail" accept=".png,.jpeg,.jpg,.webp">
                                        <div class="input-group-append" id="cat_thumbnail_div">
                                            @if (!empty($productCategory))
                                                @if (!empty($productCategory->cat_thumbnail))
                                                    <button type="button" class="btn btn-primary"
                                                        value="{{ Storage::url($productCategory->cat_thumbnail) }}"
                                                        onclick="viewCatThumbnail(this)">View</button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @props(['row' => '6'])
                                <x-submitbutton :row="$row" />

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">All Categories</h5>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-sm" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        @foreach ($table as $key => $var)
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

    @include('admin.common.include.defultmodel')

    @push('includescript')
        @include('admin.common.include.js.datatable')
        @include('admin.common.include.datatables-script')
        @include('admin.common.include.module-delete-restore-js')
    @endpush

    @push('script')
        <script>
            $(function() {
                $('#category_form').submit(function(e) {
                    e.preventDefault();
                    if ($('#category_form').parsley().isValid()) {

                        const formData = new FormData(this);
                        const formID = 'category_form';

                        $.ajax({
                            type: "POST",
                            url: "{{ $submitURL }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            cache: false,
                            beforeSend: function() {
                                setProcessingButton(formID);
                            },
                            success: function(response) {
                                if (response.type == 'success') {

                                    @if (empty($productCategory))
                                        setSuccessButton(response, formID, 'Submit');
                                    @else
                                        setUpdateSuccessButton(response, formID, 'Submit');
                                        let cat_thumbnail = response.cat_thumbnail;
                                        if (cat_thumbnail != null) {
                                            htmlString =
                                                `<button type="button" class="btn btn-primary" value="${cat_thumbnail}" onclick="viewCatThumbnail(this)">View</button>`;
                                            $('#cat_thumbnail_div').html(htmlString);
                                        }
                                    @endif
                                    $('#dataTable').DataTable().ajax.reload();
                                } else if (response.type == 'error') {
                                    resetButton(formID, 'Submit');
                                    setErrorMessage(response);
                                }
                            }
                        });
                    }
                });
            });

            function viewCatThumbnail(evt) {
                path = evt.value;
                if (path !== '') {

                    $('#defaultmodal-size').removeClass();
                    $('#defaultmodal-size').addClass('modal-dialog');
                    $('#defaultmodal').modal('show');
                    htmlString =
                        `<img src="${path}" class="img-thumbnail" alt="no-image" style="height: 300px; width: 465px;" >`;
                    $('#defaultmodal-body').html(htmlString);
                }
            }
        </script>
    @endpush
@endsection
