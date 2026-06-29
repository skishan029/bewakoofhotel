@extends('admin.common.layout')

@section('title', $title)
@section('module_title', 'Salary Report')

@push('includestyle')
    @include('admin.common.include.css.datatable')
@endpush

@section('content')
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

                                <div class="col-md-3">
                                    <label>Select Month</label>
                                    <input type="month" name="month" class="form-control form-control-sm rounded-0" value="{{ date('Y-m') }}">
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
                                        <th>Employee</th>
                                        <th>Month</th>
                                        <th>Salary</th>
                                        <th>No. of Days Present</th>
                                        <th>Amount Taken</th>
                                        <th>Due Salary</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total:</th>
                                        <th class="text-right" id="total_days_footer"></th>
                                        <th class="text-right" id="total_amount_footer"></th>
                                        <th class="text-right" id="total_due_footer"></th>
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
@endsection

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
                    d.month = $('[name=month]').val();
                    d.employee_id = $('[name=employee_id]').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'employee', name: 'employee' },
                { data: 'month', name: 'month' },
                { data: 'salary', name: 'salary', className: 'text-right' },
                { data: 'days_present', name: 'days_present', className: 'text-center' },
                { data: 'amount_taken', name: 'amount_taken', className: 'text-right' },
                { data: 'due_salary', name: 'due_salary', className: 'text-right' }
            ],
            footerCallback: function (row, data) {
                let totalTaken = 0;
                let totalDue = 0;
                let totalDays = 0;

                data.forEach(row => {
                    totalTaken += parseFloat(row.amount_taken || 0);
                    totalDue   += parseFloat(row.due_salary || 0);
                    totalDays  += parseFloat(row.days_present || 0); // use parseFloat instead of parseInt
                });

                $('#total_amount_footer').text(totalTaken.toFixed(2));
                $('#total_due_footer').text(totalDue.toFixed(2));
                $('#total_days_footer').text(totalDays.toFixed(1)); // show decimal if needed
            }
        });

        $('#search_form').on('submit', function (e) {
            e.preventDefault();
            table.draw();
        });

        $('#search_form').trigger('submit');
    });
</script>
@endpush
