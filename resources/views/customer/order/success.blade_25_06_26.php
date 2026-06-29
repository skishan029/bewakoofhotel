@extends('customer.layout.app')

@section('title', 'Order Placed')

@section('content')
    <div class="container" style="padding: 2rem 0; text-align: center;">
        <div class="card" style="max-width: 600px; margin: 0 auto; padding: 3rem 1rem;">
            <div style="margin-bottom: 2rem;">
                <div
                    style="width: 80px; height: 80px; background: rgba(34, 197, 94, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fa-solid fa-check" style="font-size: 3rem; color: var(--success, #22c55e);"></i>
                </div>
                <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-main);">Order Placed Successfully!</h1>
                <p style="color: var(--text-muted); font-size: 1.1rem;">Thank you for your purchase.</p>
            </div>

            <div
                style="background: var(--bg-secondary); padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; display: inline-block; min-width: 250px;">
                <p style="margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">Order Number</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">#{{ $order->order_no }}</p>
            </div>

            <div>
                <p style="margin-bottom: 2rem; color: var(--text-muted);">
                    We have received your order and are processing it.<br>
                    You can track your order status in the "My Orders" section.
                </p>

                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('customer.order.index') }}" class="action-btn"
                        style="background: transparent; border: 1px solid var(--primary); color: var(--primary);">
                        View My Orders
                    </a>
                    <a href="{{ route('customer.category') }}" class="action-btn">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
