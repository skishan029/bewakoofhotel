@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Customer Order')

@section('content')

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
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <!-- Keyword Search -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Keyword</label>
                                            <input type="text" name="keyword"
                                                class="form-control"
                                                placeholder="Search by order no, name, mobile..."
                                                value="{{ request('keyword') }}">
                                        </div>
                                    </div>

                                    <!-- From Date -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date"
                                                name="from_date"
                                                class="form-control"
                                                value="{{ request('from_date') }}">
                                        </div>
                                    </div>

                                    <!-- To Date -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date"
                                                name="to_date"
                                                class="form-control"
                                                value="{{ request('to_date') }}">
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.customerorder.index', ['status' => request('status') ]) }}"
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
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>C. Date</th>
                                            <th>C. Time</th>
                                            <th>Inv No</th>
                                            <th>Cus Name</th>
                                            <th>Payment Mode</th>
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

                                                    <a href="{{ route('admin.customerorder.details', $order->order_key) }}"
                                                        class="btn btn-info btn-sm btn-flat"><i class="fas fa-eye"></i></a>
                                                    <button class="btn btn-warning btn-sm btn-flat" type="button"
                                                        title="Print Invoice"
                                                        value="{{ route('admin.order.print', $order->order_key) }}"
                                                        onclick="printInvoice(this.value)"><i
                                                            class="fas fa-print"></i></button>

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

                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.common.include.defultmodel')

    @push('script')
        <script>
            function printInvoice(url) {
                window.open(url, "Invoice Print", "_blank",
                    "toolbar=yes,scrolbars=yes,resizable=yes,top=500,left=500,width=1000,height=700");
            }
        </script>
    @endpush

@endsection
