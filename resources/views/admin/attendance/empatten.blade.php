@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Attendance')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="m-0">{{ $title }}</h5>
                        <div class="card-tools">
                            <a href="{{ route('admin.attendance.index') }}" class="btn btn-primary btn-sm btn-flat">All Attendance</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="attendance_form" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Employee</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employee as $emp)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $emp->emp_name }}
                                                    <input type="hidden" name="employee_id[]" value="{{ $emp->id }}">
                                                    <input type="hidden" name="atten_date[]" id="atten-date-{{ $emp->id }}" value="{{ isset($attendance) ? $attendance->atten_date : date('Y-m-d') }}" class="atten-date">
                                                </td>
                                                <td>
                                                    {{ isset($attendance) ? $attendance->atten_date : date('Y-m-d') }}
                                                    <input type="hidden" name="atten_date[]" id="atten-date-{{ $emp->id }}" value="{{ isset($attendance) ? $attendance->atten_date : date('Y-m-d') }}" class="atten-date" readonly>
                                                </td>
                                                <td>
                                                    <select name="status[]" id="status-{{ $emp->id }}" class="form-control" data-emp="{{ $emp->id }}" data-parsley-required-message="Please select status" required>
                                                        <option value="1">Present</option>
                                                        <option value="2">Absent</option>
                                                        <option value="3">Half Day</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="amount[]" id="amount-{{ $emp->id }}" class="form-control" data-parsley-type="number" data-parsley-min="0" data-parsley-required-message="Please enter amount">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <x-submitbutton :row="2" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Fetch attendance for each employee on page load
    $(function () {
        $('#attendance_form').parsley(); // initialize parsley

        $('[data-emp]').each(function () {
            let empId = $(this).data('emp');
            let date = $('#atten-date-' + empId).val();
            checkAttendance(empId, date);
        });

        $('#attendance_form').submit(function (e) {
            e.preventDefault();
            let form = $(this);

            if (form.parsley().isValid()) {
                let formData = new FormData(this);
                const formID = 'attendance_form';

                $.ajax({
                    type: "POST",
                    url: "{{ $submitURL }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        setProcessingButton(formID);
                    },
                    success: function (response) {
                        if (response.type === 'success') {
                            setSuccessButton(response, formID, 'Submit');
                            window.location.reload();
                        } else {
                            resetButton(formID, 'Submit');
                            setErrorMessage(response);
                        }
                    },
                    error: function (xhr) {
                        resetButton(formID, 'Submit');
                        console.error(xhr.responseText);
                    }
                });
            } else {
                form.parsley().validate();
            }
        });
    });

    function checkAttendance(empId, date) {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.attendance.checkattendance') }}",
            data: { employee_id: empId, atten_date: date },
            success: function (response) {
                if (response.type === 'success' && response.attendance) {
                    $('#status-' + empId).val(response.attendance.status);
                    $('#amount-' + empId).val(response.attendance.amount);
                } else {
                    $('#status-' + empId).val('2');
                    $('#amount-' + empId).val('');
                }
            }
        });
    }
</script>
@endpush
@endsection