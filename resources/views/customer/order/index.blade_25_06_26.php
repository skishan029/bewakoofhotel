@extends('customer.layout.app')

@section('title', 'My Orders')

@section('content')
    <div class="orders-list">
        @forelse($orders as $order)
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="order-header"
                    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1rem;">
                    <div>
                        <span style="font-weight: 700; font-size: 1.1rem;">{{ $order->order_no }}</span>
                        <span
                            style="font-size: 0.85rem; color: var(--text-muted); display: block;">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</span>
                    </div>
                    <div>
                        @php
                            $OrderStatusArr = \App\Helper\Helper::customerOrderStatus($order->order_status);
                        @endphp
                        <span
                            style="background: {{ $OrderStatusArr['bg'] }}; color: {{ $OrderStatusArr['color'] }}; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 500;">
                            {{ $OrderStatusArr['label'] }}
                        </span>
                    </div>
                </div>

                <div class="order-body">
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Items:</p>
                    <ul style="list-style: none; margin-bottom: 1rem;">
                        @foreach ($order->order_items as $item)
                            @php
                                $product_details = empty($item->product_details)
                                    ? []
                                    : json_decode($item->product_details, true);
                            @endphp
                            <li
                                style="display: flex; justify-content: space-between; font-size: 0.95rem; margin-bottom: 4px;">
                                <span>{{ $item->quantity }}x {{ $item->product_name }}
                                    @if (isset($product_details['is_online_label_show']) && $product_details['is_online_label_show'] == true)
                                        ({{ $item->plate_type == '1' ? 'Full' : 'Half' }})
                                    @endif
                                </span>
                                <span>₹ {{ number_format(floatval($item->total), 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="order-footer" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                        <span style="font-size: 0.9rem; color: var(--text-muted);">Payment Mode:
                            <span style="font-weight: 500; color: var(--text-color);">
                                {{ $order->payment_option == 'cash' ? 'Cash on Delivery' : 'Online Payment' }}
                            </span>
                        </span>

                        <div style="min-width: 250px;">
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 0.9rem;">
                                <span>Sub Total</span>
                                <span>₹ {{ number_format(floatval($order->sub_total), 2) }}</span>
                            </div>
                            @if ($order->discount > 0)
                                <div
                                    style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 0.9rem; color: #2ecc71;">
                                    <span>Discount</span>
                                    <span>- ₹ {{ number_format(floatval($order->discount), 2) }}</span>
                                </div>
                            @endif
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 0.9rem;">
                                <span>Delivery Charge</span>
                                <span>Free</span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; margin-top: 8px; border-top: 1px dashed var(--border); padding-top: 8px; font-weight: 700; font-size: 1.1rem;">
                                <span>Total</span>
                                <span>₹ {{ number_format(floatval($order->grand_total), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card" style="text-align: center; padding: 4rem;">
                <i class="fa-solid fa-receipt" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <h3>No orders found</h3>
                <p style="color: var(--text-muted);">You haven't placed any orders yet.</p>
                <a href="{{ route('customer.product.index') }}" class="action-btn" style="margin-top: 1rem;">Order
                    Something</a>
            </div>
        @endforelse

        <div>
            {{ $orders->links() }}
        </div>
    </div>
@endsection
