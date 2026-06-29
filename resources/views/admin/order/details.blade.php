@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Order')

@section('content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="m-0">{{ $title }}</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row"> 
                            <dt class="col-4">Order No</dt>
                            <dd class="col-8">: <strong>{{ $productOrder->order_no }}</strong></dd> 
                            <dt class="col-4">Oder Date & Time</dt>
                            <dd class="col-8">: <strong>{{ date('d-M-Y g:i A', strtotime($productOrder->created_at)) }}</strong></dd>

                            <dt class="col-4">Payment Option</dt>
                            <dd class="col-8">: <strong>{{ Helper::paymentOption($productOrder->payment_option) }}</strong></dd>

                            <dt class="col-4">Given Amount</dt>
                            <dd class="col-8">: <strong>&#8377;{{ number_format($productOrder->given_amount, 2, '.', '') }}</strong></dd>

                            <dt class="col-4">Return Amount</dt>
                            <dd class="col-8">: <strong>&#8377;{{ number_format($productOrder->return_amount, 2, '.', '') }}</strong></dd>

                            @if ($productOrder->payment_option != 'cash')
                                @if (!empty($productOrder->transaction_no))
                                    <dt class="col-4">Transaction Number</dt>
                                    <dd class="col-8">: <strong>{{ $productOrder->transaction_no }}</strong></dd>
                                @endif
                            @endif


                            @if (!empty($productOrder->name))
                                <dt class="col-4">Customer Name</dt>
                                <dd class="col-8">: {{ $productOrder->name }}</dd>
                            @endif
                            
                            @if (!empty($productOrder->whatsapp_number))
                                <dt class="col-4">Customer Whatsapp</dt>
                                <dd class="col-8">: {{ $productOrder->whatsapp_number }}</dd>
                            @endif
                            
                            @if (!empty($productOrder->alt_number))
                                <dt class="col-4">Customer Contact</dt>
                                <dd class="col-8">: {{ $productOrder->alt_number }}</dd>
                            @endif
                            @if (!empty($productOrder->address))
                                <dt class="col-4">Customer Address</dt>
                                <dd class="col-8">: {{ $productOrder->address }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-left">Product</th>
                                    <th>Qty <small>X</small> price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productOrder->order_items as $item)
                                <tr>
                                    <td>{{ $item->product_name." - ".Helper::getPlateType($item->plate_type) }}</td>
                                    <td>{{ $item->quantity }} <small>X</small> &#8377;{{ number_format($item->price, 2, '.', '') }}</td>
                                    <td class="text-right">&#8377;{{ number_format($item->total, 2, '.', '') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td><strong>Sub Total</strong></td>
                                    <td class="text-right"><strong>&#8377;{{ number_format($productOrder->sub_total, 2, '.', '') }}</strong></td>
                                </tr>
                                @if (!empty($productOrder->discount))
                                    <tr>
                                        <td style="border: none;"></td>
                                        <td style="border: none;"><strong>Discount</strong></td>
                                        <td class="text-right text-danger" style="border: none;"><strong>-&#8377;{{ number_format($productOrder->discount, 2, '.', '') }}</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="border: none;"> </td>
                                    <td style="border: none;"><strong>Grand Total</strong></td>
                                    <td class="text-right" style="border: none;"><strong>&#8377;{{ number_format($productOrder->grand_total, 2, '.', '') }}</strong></td>
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