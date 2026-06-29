@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Expense Report')

@section('content')

@push('includestyle')
    @include('admin.common.include.css.datatable')
@endpush

@php
    $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
    $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
@endphp

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ $title }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="search_form">
                            <div class="row">
                                @if (Auth::guard('admin')->user()->user_type == '1')
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" id="start_date" name="start_date" class="form-control form-control-sm rounded-0" value="{{ $startOfMonth }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                                            <input type="date" id="end_date" name="end_date" class="form-control form-control-sm rounded-0" value="{{ $endOfMonth }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">Search</button>
                                    </div>
                                @else
                                    @php
                                        $today = \Carbon\Carbon::today()->format('Y-m-d');
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="hidden" id="start_date" name="start_date" class="form-control form-control-sm rounded-0" value="{{ $today }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="hidden" id="end_date" name="end_date" class="form-control form-control-sm rounded-0" value="{{ $today }}" required>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        @foreach ($table as $key => $label)
                                            @if ($key === 'exp_amount')
                                                <th class="text-left">{{ $label }}</th>
                                            @else
                                                <th>{{ $label }}</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="{{ $table->count() }}" class="text-right">Total:</th>
                                        <th id="total_amount_footer" class="text-right"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php $table_keys = $table->keys(); @endphp

@include('admin.common.include.defultmodel')

@push('includescript')
    @include('admin.common.include.js.datatable')
@endpush

@push('script')
<script>
    $(function () {
        //$('#search_form').parsley();

        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            paging: false,
            scrollX: false,
            ajax: {
                url: "{{ $dataTableURL }}",
                type: "GET",
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                @foreach ($table_keys as $key)
                    @if ($key === 'exp_amount')
                        { data: '{{ $key }}', name: '{{ $key }}', className: 'text-right' },
                    @else
                        { data: '{{ $key }}', name: '{{ $key }}' },
                    @endif
                @endforeach
            ],
            footerCallback: function (row, data) {
                let total = 0;
                data.forEach(item => {
                    if (item.exp_amount) {
                        total += parseFloat(item.exp_amount);
                    }
                });
                $('#total_amount_footer').html(total.toFixed(2));
            }
        });

        $('#search_form').on('submit', function (e) {
            e.preventDefault();
            if ($(this).parsley().isValid()) {
                table.draw();
            }
        });

        // Auto load current month on page load
        $('#search_form').trigger('submit');
    });
</script>
@endpush

@endsection
