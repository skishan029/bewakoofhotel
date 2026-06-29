@extends('frontend.layout.layout')
@section('title', $title)

@section('content')

@include('frontend.layout.breadcrumb')
<div class="best-offer-area1 mb-20 mt-40">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12">
                <div class="best-offer-wrap clearfix">
                    <div class="best-offer-content">
                        <h3>Order Placed Successfully!</h3>
                        <p>Thank you for your purchase.</p>
                        <h5>Order Number</h5>
                        <h4><b>#{{ $order->order_no }}</b></h4>
                        <p>We have received your order and are processing it. You can track your order status in the "My Orders" section.</p>
                        <a href="{{ route('customer.order.index') }}" class="primary-btn3 btn-sm">View My Orders</a>
                        <a href="{{ route('customer.category') }}" class="primary-btn3 btn-sm">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection