@extends('customer.layouts.app')
@section('title', 'Order Payment')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Payment</h4>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    Order Summary
                                </div>
                                <div class="card-body">
                                    <p><strong>Order No:</strong> {{ $orderIntent->order_no }}</p>
                                    <p><strong>Date:</strong> {{ $orderIntent->order_date }}</p>
                                    <hr>
                                    <h5>Items</h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orderIntent->order_item as $item)
                                                <tr>
                                                    <td>{{ $item['name'] }} <br>
                                                        <small>{{ $item['options']['plate_type'] }}</small></td>
                                                    <td>{{ $item['qty'] }}</td>
                                                    <td>₹{{ number_format($item['price'], 2) }}</td>
                                                    <td>₹{{ number_format($item['total'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Subtotal</th>
                                                <th>₹{{ number_format($orderIntent->sub_total, 2) }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Delivery Charge</th>
                                                <th>₹{{ number_format($orderIntent->delivery_charge, 2) }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Discount</th>
                                                <th>₹{{ number_format($orderIntent->discount, 2) }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Grand Total</th>
                                                <th>₹{{ number_format($orderIntent->grand_total, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    Delivery Address
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> {{ $orderIntent->customer_name }}</p>
                                    <p><strong>Phone:</strong> {{ $orderIntent->customer_phone }}</p>
                                    <p><strong>Address:</strong> {{ $orderIntent->address }}</p>
                                    <p><strong>Landmark:</strong> {{ $orderIntent->landmark }}</p>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    Payment
                                </div>
                                <div class="card-body">
                                    <p>Select Payment Method:</p>
                                    <!-- Placeholder for payment gateway buttons -->
                                    <button class="btn btn-primary btn-block">Pay Now (Razorpay)</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
