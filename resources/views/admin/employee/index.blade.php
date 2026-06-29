@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Employee')

@section('content')

@push('includestyle')
    @include('admin.common.include.css.datatable')
@endpush

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ $title }}</h5>
                        <div class="card-tools">
                            <a href="{{ route('admin.employee.create') }}" class="btn btn-primary btn-sm btn-flat"><i class="fas fa-plus-circle"></i> Create</a>
                        </div>
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
    {{-- @include('admin.common.include.module-delete-restore-js') --}}

    <script>
        function deleteAppEmployee(evt) {
            if (evt.value !== '') {
                swal({
                    title: "Are you sure?",
                    text: "This employee will be moved to trash!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function () {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.employee.delete') }}",
                        data: {
                            _token: "{{ csrf_token() }}",  // ✅ Include CSRF token here
                            id: evt.value
                        },
                        success: function (response) {
                            if (response.type === 'success') {
                                swal("", response.message, "success");
                                $('#dataTable').DataTable().ajax.reload();
                            } else {
                                swal.close();
                                setErrorMessage(response);
                            }
                        },
                        error: function () {
                            swal.close();
                            alert('Something went wrong. Please try again.');
                        }
                    });
                });
            }
        }

        function restoreAppEmployee(evt) {
            if (evt.value !== '') {
                swal({
                    title: "Are you sure?",
                    text: "You want to restore this employee?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonClass: "btn-primary",
                    confirmButtonText: "Yes, restore it!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function () {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.employee.restore') }}",
                        data: {
                            _token: "{{ csrf_token() }}",  // ✅ Include CSRF token
                            id: evt.value
                        },
                        success: function (response) {
                            if (response.type === 'success') {
                                swal("", response.message, "success");
                                $('#dataTable').DataTable().ajax.reload();
                            } else {
                                swal.close();
                                setErrorMessage(response);
                            }
                        },
                        error: function () {
                            swal.close();
                            alert('Something went wrong. Please try again.');
                        }
                    });
                });
            }
        }

       
    </script>
@endpush

@endsection