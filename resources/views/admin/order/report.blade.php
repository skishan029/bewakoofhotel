@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Report')

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
                        @include('admin.order.include.reportmenu')
                    </div>
                    <div class="card-body">

                        @php
                            $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d\TH:i');
                            $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d\TH:i');

                        @endphp 
                        <div class="row">
                            <div class="col-md-11">
                                <form action="" method="post" id="search_form">
                                    <div class="row">
                                        @if (Auth::guard('admin')->user()->user_type == '1')
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="start_date">Start Date <strong class="text-danger">*</strong></label>
                                                <input id="start_date" class="form-control rounded-0 form-control-sm" type="datetime-local" name="start_date" value="{{ $startOfMonth }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="end_date">Close Date <strong class="text-danger">*</strong></label>
                                                <input id="end_date" class="form-control rounded-0 form-control-sm" type="datetime-local" name="end_date" value="{{ $endOfMonth }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="{{ Helper::adminPrimaryButtonClass() }} btn-block">Search</button>
                                            </div>
                                        </div>
                                        @else
                                            @php
                                                $todayStart = \Carbon\Carbon::today()->format('Y-m-d') . 'T00:00';
                                                $todayEnd   = \Carbon\Carbon::today()->format('Y-m-d') . 'T23:59';
                                            @endphp
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input id="start_date" class="form-control rounded-0 form-control-sm d-none" type="datetime-local" name="start_date" value="{{ $todayStart }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input id="end_date" class="form-control rounded-0 form-control-sm d-none" type="datetime-local" name="end_date" value="{{ $todayEnd }}" required>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

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
                                <tfoot>
                                    <tr>
                                        <th colspan="6" style="text-align:right">Total:</th>
                                        <!--<th></th>-->
                                        <th></th>
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

            $('#search_form').parsley();

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                paging: false,
                ajax: {
                    url : "{{ $dataTableURL }}",
                    type: "GET",
                    data: function(d){
                        d.start_date    = $('#search_form input[name=start_date]').val();
                        d.end_date      = $('#search_form input[name=end_date]').val();
                    }
                },
                
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false},
                    @foreach ($table_keys as $var)
                        @if ($var == 'action')
                            {data: 'action', name: 'action', orderable: false, searchable: true},
                        @else
                            {data: '{{ $var }}', name: '{{ $var }}' },
                        @endif
                    @endforeach
                ],
                "footerCallback": function (row, data, start, end, display) { 
                    let api = this.api();               
                    var totalgrand_total = 0;
                    var onlinepayment = 0;
                    data.forEach(element => {
                        totalgrand_total = parseFloat(totalgrand_total) + parseFloat(element.grand_total);
                        /*
                        if (element.onlinepayment != '') {
                            onlinepayment = parseFloat(onlinepayment) + parseFloat(element.onlinepayment);
                        }
                        */
                    });
                    api.column(6).footer().innerHTML = totalgrand_total.toFixed(2);
                    // api.column(5).footer().innerHTML = onlinepayment.toFixed(2);
                },
            });  

            $('#search_form').submit(function (e) { 
                e.preventDefault();
                if ($('#search_form').parsley().isValid()) {
                    $('#search_form').parsley().reset();
                    $('#dataTable').DataTable().draw(true);
                }
            });

        });

        function printInvoice(url) {
            window.open(url,"Invoice Print","_blank","toolbar=yes,scrolbars=yes,resizable=yes,top=500,left=500,width=1000,height=700");
        }
    </script>
@endpush

@endsection