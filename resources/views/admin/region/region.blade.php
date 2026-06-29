@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Region Master')

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
                            <h5 class="m-0">{{ empty($region) ? 'Create' : 'Edit' }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="region_form" data-parsley-validate>
                                @if (!empty($region))
                                    @method('PUT')
                                @endif

                                @php
                                    $parent_id = empty($region) ? '' : $region->parent_id;
                                @endphp
                                <div class="form-group">
                                    <label for="parent_id">Parent Region</label>
                                    <select name="parent_id" id="parent_id" class="form-control rounded-0">
                                        <option value="">None</option>
                                        @foreach ($parent_regions as $parent_region)
                                            <option value="{{ $parent_region->id }}"
                                                {{ $parent_id == $parent_region->id ? 'selected' : '' }}>
                                                {{ $parent_region->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Region Name <strong class="text-danger">*</strong></label>
                                    <input id="name" class="form-control rounded-0" type="text" name="name"
                                        required value="{{ empty($region) ? '' : $region->name }}">
                                </div>
                                {{-- <div class="form-group">
                                    <label for="price">Price</label>
                                    <input id="price" class="form-control rounded-0" type="text" name="price"
                                        value="{{ empty($region) ? '' : $region->price }}">
                                </div> --}}

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
                $('#region_form').submit(function(e) {
                    e.preventDefault();
                    if ($('#region_form').parsley().isValid()) {

                        const formData = new FormData(this);
                        const formID = 'region_form';

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
                                    @if (empty($region))
                                        setSuccessButton(response, formID, 'Submit');
                                        updateRegions(response.parent_regions);
                                    @else
                                        setUpdateSuccessButton(response, formID, 'Submit');
                                    @endif
                                    $('#dataTable').DataTable().ajax.reload(null, false);
                                } else if (response.type == 'error') {
                                    resetButton(formID, 'Submit');
                                    setErrorMessage(response);
                                }
                            }
                        });
                    }
                });
            });

            function updateRegions(params) {
                if (params.length > 0) {
                    let htmlString = '<option value="">None</option>';
                    for (const key in params) {
                        htmlString += `<option value="${params[key].id}">${params[key].name}</option>`;
                    }
                    $('#parent_id').html(htmlString);
                }
            }
        </script>
    @endpush
@endsection
