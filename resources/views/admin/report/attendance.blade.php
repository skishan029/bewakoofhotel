@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Attendance Report')

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
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="card-title">{{ $title }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="search_form">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Employee</label>
                                    <select name="employee_id" class="form-control form-control-sm rounded-0">
                                        <option value="">All</option>
                                        @foreach ($employee as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->emp_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Status</label>
                                    <select name="status" class="form-control form-control-sm rounded-0">
                                        <option value="">All</option>
                                        <option value="1">Present</option>
                                        <option value="2">Absent</option>
                                        <option value="3">Half Day</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control form-control-sm rounded-0" value="{{ $startOfMonth }}" required>
                                </div>

                                <div class="col-md-2">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control form-control-sm rounded-0" value="{{ $endOfMonth }}" required>
                                </div>

                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Search</button>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        @foreach ($table as $key => $label)
                                            @if ($key === 'amount')
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
                                        <th class="text-right" id="total_amount_footer"></th>
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

@push('includescript')
    @include('admin.common.include.js.datatable')
@endpush

@push('script')
<script>
    $(function () {
        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            ajax: {
                url: "{{ $dataTableURL }}",
                data: function (d) {
                    d.start_date = $('[name=start_date]').val();
                    d.end_date = $('[name=end_date]').val();
                    d.employee_id = $('[name=employee_id]').val();
                    d.status = $('[name=status]').val(); // ✅ Add this line
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                @foreach ($table_keys as $key)
                    @if ($key === 'amount')
                        { data: '{{ $key }}', name: '{{ $key }}', className: 'text-right' },
                    @else
                        { data: '{{ $key }}', name: '{{ $key }}' },
                    @endif
                @endforeach
            ],
            footerCallback: function (row, data) {
                let total = 0;
                data.forEach(row => {
                    total += parseFloat(row.amount || 0);
                });
                $('#total_amount_footer').text(total.toFixed(2));
            }
        });

        $('#search_form').on('submit', function (e) {
            e.preventDefault();
            if ($(this).parsley().isValid()) {
                table.draw();
            }
        });

        // 🔄 Auto-load current month
        $('#search_form').trigger('submit');
    });
</script>
@endpush

@endsection
