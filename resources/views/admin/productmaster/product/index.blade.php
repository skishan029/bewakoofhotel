@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Product')

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
                            @if (Auth::guard('admin')->user()->user_type == '1')
                                <div class="card-tools">
                                    <a href="{{ route('admin.productmaster.product.create') }}" class="btn btn-primary btn-sm btn-flat"><i class="fas fa-plus-circle"></i> Create</a>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
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
    </div>

    @include('admin.common.include.defultmodel')

    @push('includescript')
        @include('admin.common.include.js.datatable')
    @endpush

    @push('script')
        @include('admin.common.include.datatables-script')
        @include('admin.common.include.module-delete-restore-js')

        <script>
            function changeStatus(id) {
                $.ajax({
                    url: "{{ route('admin.productmaster.product.status', ':id') }}".replace(':id', id),
                    type: "GET",
                    success: function(response) {
                        if (response.type == 'success') {
                            $('#dataTable').DataTable().ajax.reload(null, false);
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        </script>
    @endpush

@endsection
