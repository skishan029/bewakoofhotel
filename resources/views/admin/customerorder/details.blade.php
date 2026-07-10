@extends('admin.common.layout')
@section('title', $page_title)
@section('module_title', 'Customer Order')

@section('content')

    @push('includestyle')
        <style>
            .overlayclass {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                /* backdrop-filter: blur(5px);  */
                z-index: 9999;
            }
        </style>
    @endpush
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div id="overlay"></div>
            <div class="row">
                <div class="col-md-6">
                    <!-- Order Details Card -->
                    <div class="{{ Helper::adminCardClass() }}">
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-12 text-center border-bottom pb-2 mb-2">Order Details</dt>
                                <dt class="col-4">Order No</dt>
                                <dd class="col-8">: <strong>{{ $order->order_no }}</strong></dd>

                                <dt class="col-4">Order Date</dt>
                                <dd class="col-8">:
                                    <strong>{{ date('d-M-Y g:i A', strtotime($order->created_at)) }}</strong>
                                </dd>

                                <dt class="col-4">Note</dt>
                                <dd class="col-8">: <span>{{ $order->order_note }}</span>
                                </dd>


                                <dt class="col-4">Total Amount</dt>
                                <dd class="col-8">: <strong>&#8377;{{ number_format($order->grand_total, 2) }}</strong>
                                </dd>

                                <dt class="col-4">Current Status</dt>
                                <dd class="col-8">: <span
                                        class="badge badge-info status-label">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                                </dd>
                                <dt class="col-12 text-center border-bottom pb-2 mb-2 mt-2">Customer Details</dt>
                                <dt class="col-4">Customer Name</dt>
                                <dd class="col-8">: {{ $order->customer->name }}</dd>

                                <dt class="col-4">Customer Contact</dt>
                                <dd class="col-8">: {{ $order->customer->username }}</dd>

                                <dt class="col-4">Shipping Address</dt>
                                <dd class="col-8">: {{ $order->address }}</dd>

                                <dt class="col-4">Shipping Landmark</dt>
                                <dd class="col-8">: {{ $order->landmark }}</dd>

                                @if ($order->whatsapp_number)
                                    <dt class="col-4">Whatsapp</dt>
                                    <dd class="col-8">: {{ $order->whatsapp_number }}</dd>
                                @endif

                                <dt class="col-4">Region</dt>
                                <dd class="col-8">: {{ $order->region?->name ?? null }}</dd>

                                <dt class="col-4">Sub Region</dt>
                                <dd class="col-8">: {{ $order->sub_region?->name ?? null }}</dd>

                                <dt class="col-12 text-center border-bottom pb-2 mb-2 mt-2">Payment Details</dt>
                                <dt class="col-4">Payment Mode</dt>
                                <dd class="col-8">: <strong>{{ Helper::paymentOption($order->payment_option) }}</strong>
                                </dd>

                                <dt class="col-4">Payment Status</dt>
                                <dd class="col-8">: <strong>{{ $order->is_paid ? 'Paid' : 'Not Paid' }}</strong>
                                </dd>

                                <dt class="col-4">Payment ID</dt>
                                <dd class="col-8">: <strong>{{ $order->transaction_id }}</strong></dd>

                                <dt class="col-4">Payment Order ID</dt>
                                <dd class="col-8">: <strong>{{ $order->transaction_no }}</strong></dd>

                            </dl>
                        </div>
                    </div>

                    <div class="row">
                        @if (in_array($order->order_status, ['pending', 'accepted', 'preparing', 'out_for_delivery']))
                            <div class="col-md-4">
                                @if ($order->order_status == 'pending')
                                    <button type="button" class="btn btn-primary btn-block"
                                        onclick="changeOrderStatus('accepted')">Accept Order</button>
                                @elseif ($order->order_status == 'accepted')
                                    <button type="button" class="btn btn-info btn-block"
                                        onclick="changeOrderStatus('preparing')">Preparing Order</button>
                                @elseif ($order->order_status == 'preparing')
                                    <button type="button" class="btn btn-dark btn-block"
                                        onclick="changeOrderStatus('out_for_delivery')">Out for Delivery Order</button>
                                @elseif ($order->order_status == 'out_for_delivery')
                                    <button type="button" class="btn btn-success btn-block"
                                        onclick="changeOrderStatus('delivered')">Delivered Order</button>
                                @endif
                            </div>
                        @endif
                        @if (in_array($order->order_status, ['pending', 'accepted', 'preparing', 'out_for_delivery']))
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger btn-block"
                                    onclick="changeOrderStatus('cancelled')">Cancel Order</button>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <button type="button" class="btn btn-warning btn-block" title="Print Invoice"
                                value="{{ route('admin.order.print', $order->order_key) }}"
                                onclick="printInvoice(this.value)">Print
                                Invoice</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Order Items Card -->
                    <div class="{{ Helper::adminCardClass() }}">
                        <div class="card-header">
                            <h5 class="m-0">Order Items</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Img</th>
                                        <th>Product</th>
                                        <th>Qty x Price</th>
                                        <th>Delivery Charge</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_items as $item)
                                        @php
                                            $product_details = empty($item->product_details)
                                                ? []
                                                : json_decode($item->product_details, true);
                                        @endphp
                                        <tr>
                                            <td>
                                                <img src="{{ Storage::url($item->product->featured_photo) }}"
                                                    alt="Product" class="img-fluid" style="width: 50px; height: 50px;">
                                            </td>
                                            <td>{{ $item->product_name }}
                                                @if (isset($product_details['is_online_label_show']) && $product_details['is_online_label_show'] == true)
                                                    <br>
                                                    <small
                                                        class="badge badge-danger">{{ Helper::getPlateType($item->plate_type) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->quantity }} x &#8377;{{ number_format($item->price, 2) }}</td>
                                            <td>&#8377;{{ number_format($item->total_delivery_charge, 2) }}</td>
                                            <td class="text-right">&#8377;{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"><strong>Sub Total</strong></td>
                                        <td class="text-right">
                                            <strong>&#8377;{{ number_format($order->sub_total, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @if (floatval($order->discount) > 0)
                                        <tr>
                                            <td colspan="4"><strong>Discount</strong></td>
                                            <td class="text-right text-danger">
                                                <strong>-&#8377;{{ number_format($order->discount, 2) }}</strong>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="4"><strong>Delivery Charge</strong></td>
                                        <td class="text-right">
                                            @if ($order->delivery_charge > 0)
                                                <strong>&#8377;{{ number_format($order->delivery_charge, 2) }}</strong>
                                            @else
                                                <strong>Free</strong>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong>Grand Total</strong></td>
                                        <td class="text-right">
                                            <strong>&#8377;{{ number_format($order->grand_total, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function printInvoice(url) {
            window.open(url, "Invoice Print", "_blank",
                "toolbar=yes,scrolbars=yes,resizable=yes,top=500,left=500,width=1000,height=700");
        }

        function formatStatus(status) {
            return status
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }

        function changeOrderStatus(status) {
            let title = formatStatus(status);
            swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: 'Yes, ' + title + ' it!',
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                },
                function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.customerorder.updatestatus') }}",
                        data: {
                            order_id: '{{ $order->id }}',
                            status: status
                        },
                        dataType: "JSON",
                        beforeSend: function() {},
                        success: function(response) {
                            if (response.status == 'success') {
                                swal({
                                        title: "",
                                        text: response.message,
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    },
                                    function() {
                                        window.location.reload();
                                    });
                            } else {
                                swal("Error", response.message, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 422 || xhr.status === 400 || xhr.status === 404) {
                                swal("Error", xhr.responseJSON.message, "error");
                            } else if (xhr.status === 500) {
                                swal("Error", 'Internal server error', "error");
                            }
                        }
                    });
                });
        }
    </script>
@endpush
