@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Invoice')

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
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filter Search
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <!-- Keyword Search -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Keyword</label>
                                            <input type="text" name="keyword" class="form-control"
                                                placeholder="Search by order no, name, mobile..."
                                                value="{{ request('keyword') }}">
                                        </div>
                                    </div>

                                    <!-- From Date -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date" name="from_date" class="form-control"
                                                value="{{ request('from_date') }}">
                                        </div>
                                    </div>

                                    <!-- To Date -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date" name="to_date" class="form-control"
                                                value="{{ request('to_date') }}">
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                            <a href="{{ route(Route::currentRouteName()) }}"
                                                class="btn btn-secondary btn-block mt-2">
                                                Reset
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $title }}</h5>
                            <div class="card-tools">
                                <a href="{{ route('admin.order.create') }}" class="btn btn-primary btn-sm btn-flat"><i
                                        class="fas fa-plus-circle"></i> Create</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>C. Date</th>
                                            <th>C. Time</th>
                                            <th>Inv No</th>
                                            <th>Cus Name</th>
                                            <th>Payment Option</th>
                                            <th>Grand Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $order->created_at->format('H:i A') }}</td>
                                                <td>{{ $order->order_no }}</td>
                                                <td>{{ $order->name }}</td>
                                                <td>{{ \App\Helper\Helper::paymentOption($order->payment_option) }}</td>
                                                <td>{{ number_format($order->grand_total, '2', '.', '') }}</td>
                                                <td width="12%">
                                                    {{-- @if (Auth::guard('admin')->user()->user_type == '1') --}}
                                                        <a href="{{ route('admin.order.edit', $order->id) }}"
                                                            class="btn btn-primary btn-sm btn-flat"><i
                                                                class="fas fa-edit"></i></a>
                                                    {{-- @endif --}}


                                                    <a href="{{ route('admin.order.details', $order->order_key) }}"
                                                        class="btn btn-info btn-sm btn-flat"><i class="fas fa-eye"></i></a>

                                                    @if ($order->status == '2')
                                                        <button class="btn btn-warning btn-sm btn-flat" type="button"
                                                            title="Print Invoice"
                                                            value="{{ route('admin.order.print', $order->order_key) }}"
                                                            onclick="printInvoice(this.value)"><i
                                                                class="fas fa-print"></i></button>
                                                    @else
                                                        @php
                                                            $approveJson = json_encode([
                                                                'id' => $order->id,
                                                            ]);
                                                        @endphp
                                                        <button class="btn btn-success btn-sm btn-flat" type="button"
                                                            title="Place Order" value='{{ $approveJson }}'
                                                            onclick="placeOrderFrom(this)"><i
                                                                class="fas fa-check"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No orders found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if (Auth::guard('admin')->user()->user_type == '1')
                                {{ $orders->links() }}
                            @endif

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
        {{-- @include('admin.common.include.datatables-script') --}}
        {{-- @include('admin.common.include.module-delete-restore-js') --}}

        <script>
            function printInvoice(url) {
                window.open(url, "Invoice Print", "_blank",
                    "toolbar=yes,scrolbars=yes,resizable=yes,top=500,left=500,width=1000,height=700");
            }
            @if (request()->routeIs('admin.order.pendingindex'))
                function placeOrderFrom(evt) {
                    if (evt.value != '') {
                        if (jQuery.isEmptyObject(evt.value) == false) {

                            $.ajax({
                                type: "POST",
                                url: "{{ route('admin.order.placeorderform') }}",
                                data: JSON.parse(evt.value),
                                dataType: "JSON",
                                beforeSend: function() {
                                    $('#defaultmodal-size').removeClass();
                                    $('#defaultmodal-size').addClass('modal-dialog modal-lg');
                                    $('#defaultmodal-body').html(
                                        '<div class="text-center"><div id="loading"></div></div>');
                                    $('#defaultmodal').modal('show');
                                },
                                success: function(response) {
                                    if (response.type == 'success') {
                                        $('#defaultmodal-body').html(response.html);

                                        if (response.status == '2') {
                                            $('#approve_form').parsley();
                                        } else if (response.status == '3') {
                                            $('#cancel_form').parsley();
                                        }

                                    } else {
                                        $('#defaultmodal').modal('hide');
                                        setErrorMessage(response);
                                    }
                                }
                            });
                        }
                    } else {
                        toastr.error('Id is required');
                    }
                }

                function placeOrderSubmit() {
                    event.preventDefault();
                    const formID = 'placeorder_form';
                    const formData = new FormData($(`#${formID}`)[0]);

                    if ($(`#${formID}`).parsley().isValid()) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.order.placeordersubmit') }}",
                            data: formData,
                            dataType: "JSON",
                            cache: false,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                setProcessingButton(formID);
                            },
                            success: function(response) {
                                if (response.type == 'success') {
                                    $('#defaultmodal').modal('hide');
                                    alert(response.message);
                                    window.location.reload();
                                } else {
                                    resetButton(formID, 'Place Order');
                                    setErrorMessage(response);
                                }
                            }
                        });
                    }
                }
            @endif
        </script>
    @endpush

@endsection
