@extends('frontend.layout.layout')
@section('title', $title)

@section('content')

    @include('frontend.layout.breadcrumb')
    <div class="cart-section pt-40 pb-20">
        <div class="container">

            <div class="row g-4">
                @forelse($orders as $order)
                    <div class="col-lg-12">
                        <table class="table total-table">
                            <thead>
                                <tr>
                                    <th>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</th>
                                    <th>{{ $order->order_no }}</th>
                                    @php
                                        $OrderStatusArr = \App\Helper\Helper::customerOrderStatus($order->order_status);
                                    @endphp
                                    <th><span
                                            style="background: {{ $OrderStatusArr['bg'] }}; padding: 4px 10px; border-radius: 4px;">
                                            {{ $OrderStatusArr['label'] }}
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Items:</td>
                                    <td>
                                        <ul class="cost-list text-start">
                                            @foreach ($order->order_items as $item)
                                                @php
                                                    $product_details = empty($item->product_details)
                                                        ? []
                                                        : json_decode($item->product_details, true);
                                                @endphp
                                                <li>
                                                    {{ $item->quantity }} x {{ $item->product_name }}
                                                    @if (isset($product_details['is_online_label_show']) && $product_details['is_online_label_show'] == true)
                                                        ({{ $item->plate_type == '1' ? 'Full' : 'Half' }})
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="single-cost text-end">
                                            @foreach ($order->order_items as $item)
                                                <li>₹ {{ number_format(floatval($item->total), 2) }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Payment Mode : {{ $order->payment_option == 'cash' ? 'COD' : 'Online' }}</td>
                                    <td>
                                        <ul class="cost-list text-end">
                                            <li>Sub Total</li>
                                            @if ($order->discount > 0)
                                                <li>Discount</li>
                                            @endif
                                            <li>Shipping Fee</li>
                                            <li><b>Total</b></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="single-cost text-end">
                                            <li>₹ {{ number_format(floatval($order->sub_total), 2) }}</li>
                                            @if ($order->discount > 0)
                                                <li>- ₹ {{ number_format(floatval($order->discount), 2) }}</li>
                                            @endif
                                            <li>
                                                @if ($order->delivery_charge > 0)
                                                    ₹ {{ number_format(floatval($order->delivery_charge), 2) }}
                                                @else
                                                    Free
                                                @endif
                                            </li>
                                            <li>₹ {{ number_format(floatval($order->grand_total), 2) }}</li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @empty
                    <div class="card" style="text-align: center; padding: 4rem;">
                        <i class="fa-solid fa-receipt"
                            style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                        <h3>No orders found</h3>
                        <p style="color: var(--text-muted);">You haven't placed any orders yet.</p>
                        <a href="{{ route('customer.product.index') }}" class="action-btn" style="margin-top: 1rem;">Order
                            Something</a>
                    </div>
                @endforelse
            </div>

            <div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
