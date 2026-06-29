@extends('customer.layout.app')

@section('title', 'Your Cart')

@section('content')
    <div class="row" style="max-width: 900px; margin: 0 auto;">
        @if (!$cart->isEmpty())
            <div class="card mt-5">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border); text-align: left;">
                            <th style="padding: 1rem;">Product</th>
                            <th style="padding: 1rem;">Plate</th>
                            <th style="padding: 1rem;">Price</th>
                            <th style="padding: 1rem;">Quantity</th>
                            <th style="padding: 1rem;">Total</th>
                            <th style="padding: 1rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $id => $details)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                    <img src="{{ $details->options['photo'] ? Storage::url($details->options['photo']) : asset('assests/logo/50x50.svg') }}"
                                        width="50" height="50" style="border-radius: 8px; object-fit: cover;">
                                    {{ $details->name }}
                                </td>
                                <td style="padding: 1rem;">
                                    @if ($details->options['is_online_label_show'])
                                        <span
                                            style="background: rgba(99, 102, 241, 0.2); color: var(--primary); padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                                            {{ $details->options['plate_label'] }}
                                        </span>
                                    @endif

                                </td>
                                <td style="padding: 1rem;">₹ {{ $details->price }}</td>
                                <td style="padding: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div
                                            style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 4px; overflow: hidden;">
                                            <button type="button" onclick="updateQty('{{ $id }}', -1)"
                                                style="background: rgba(255,255,255,0.05); border: none; padding: 4px 8px; color: inherit; cursor: pointer;">-</button>
                                            <input type="text" id="qty-{{ $id }}" value="{{ $details->qty }}"
                                                style="width: 40px; text-align: center; background: transparent; border: none; color: inherit; font-size: 0.9rem;"
                                                readonly>
                                            <button type="button" onclick="updateQty('{{ $id }}', 1)"
                                                style="background: rgba(255,255,255,0.05); border: none; padding: 4px 8px; color: inherit; cursor: pointer;">+</button>
                                        </div>
                                    </div>
                                    <span id="ajax-msg-{{ $id }}" style="color: red; font-size: 0.8rem;"></span>
                                </td>
                                <td style="padding: 1rem; font-weight: 600;" id="row-total-{{ $id }}">₹
                                    {{ $details->price * $details->qty }}</td>
                                <td style="padding: 1rem;">
                                    <form action="{{ route('customer.cart.destroy', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 1.1rem;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"
                                style="padding: 1.5rem; text-align: right; font-weight: 600; font-size: 1.2rem;">Total
                                Amount:</td>
                            <td colspan="2" id="cart-subtotal"
                                style="padding: 1.5rem; font-weight: 700; font-size: 1.5rem; color: var(--primary);">₹
                                {{ $subTotal }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('customer.product.index') }}" class="action-btn"
                        style="background: transparent; border: 1px solid var(--primary); color: var(--primary);">
                        Continue Shopping
                    </a>
                    <a href="{{ route('customer.order.checkout') }}" class="action-btn">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @else
            <div class="card mt-5" style="text-align: center; padding: 4rem;">
                <i class="fa-solid fa-cart-arrow-down"
                    style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <h3>Your cart is empty</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Looks like you haven't added any delicious food
                    yet.</p>
                <a href="{{ route('customer.product.index') }}" class="action-btn">Start Ordering</a>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        function updateQty(rowId, change) {
            var qtyInput = document.getElementById('qty-' + rowId);
            var currentQty = parseInt(qtyInput.value);
            var newQty = currentQty + change;

            if (newQty < 1) return;

            // Disable buttons while request is in progress
            qtyInput.value = newQty;

            $.ajax({
                url: "{{ url('customer/cart') }}/" + rowId,
                type: 'PUT',
                data: {
                    qty: newQty
                },
                beforeSend: function() {
                    $('#ajax-msg-' + rowId).text('Updating...');
                },
                success: function(response) {
                    if (response.success) {
                        // Update qty input
                        qtyInput.value = response.qty;

                        // Update row total
                        $('#row-total-' + rowId).text('₹ ' + parseFloat(response.rowTotal).toFixed(2));

                        // Update subtotal
                        $('#cart-subtotal').text('₹ ' + response.subTotal);

                        // Update cart badge in sidebar
                        $('#cart-badge').text(response.cartCount);

                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    // Revert qty on failure
                    qtyInput.value = currentQty;
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Cart update failed!';
                    toastr.error(msg);
                },
                complete: function() {
                    $('#ajax-msg-' + rowId).text('');
                }
            });
        }
    </script>
@endpush
