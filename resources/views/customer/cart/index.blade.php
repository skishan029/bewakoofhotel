@extends('frontend.layout.layout')

@section('title', $title)

@section('content')

@include('frontend.layout.breadcrumb')
@if (!$cart->isEmpty())
<div class="cart-section pt-40 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table-wrapper">
                    @php
                        $showPlateColumn = collect($cart)->contains(function ($item) {
                            return !empty($item->options['is_online_label_show']);
                        });
                    @endphp
                    <table class="eg-table table cart-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Food Name</th>
                                @if($showPlateColumn)
                                    <th>Plate</th>
                                @endif
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $id => $details)
                            <tr>
                                <td data-label="Image">
                                    <img src="{{ $details->options['photo'] ? Storage::url($details->options['photo']) : asset('assests/logo/50x50.svg') }}" alt>
                                </td>
                                <td data-label="Food Name"><a>{{ $details->name }}</a></td>
                                @if($showPlateColumn)
                                    <td data-label="Plate">
                                        @if ($details->options['is_online_label_show'])
                                            {{ $details->options['plate_label'] }}
                                        @endif
                                    </td>
                                @endif
                                <td data-label="Discount Price">₹ {{ $details->price }}</td>
                                <td data-label="Quantity">
                                    <div style="display: inline-table;">
                                        <div style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: 4px; overflow: hidden;">
                                            <button type="button" onclick="updateQty('{{ $id }}', -1)" style="padding: 4px 8px; cursor: pointer;">-</button>
                                            <input type="text" id="qty-{{ $id }}" value="{{ $details->qty }}" style="width: 40px; text-align: center;" readonly>
                                            <button type="button" onclick="updateQty('{{ $id }}', 1)" style="padding: 4px 8px; cursor: pointer;">+</button>
                                        </div>
                                    </div>
                                    <span id="ajax-msg-{{ $id }}" style="color: red; font-size: 0.8rem;"></span>
                                </td>
                                <td data-label="Subtotal" id="row-total-{{ $id }}">{{ $details->price * $details->qty }}</td>
                                <td data-label="Delete">
                                    <div class="delete-icon">
                                        <form action="{{ route('customer.cart.destroy', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 1.1rem;">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="{{ $showPlateColumn ? 5 : 4 }}" style="text-align: right; font-weight: bold;">
                                    Total Amount
                                </td>
                                <td id="cart-subtotal" style="text-align: right; font-weight: bold;">
                                    ₹ {{ $subTotal }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <!-- <div class="coupon-area">
                            <div class="cart-coupon-input">
                                <h5 class="coupon-title">Coupon Code</h5>
                                <form class="coupon-input d-flex align-items-center">
                                    <input type="text" placeholder="Coupon Code">
                                    <button type="submit">Apply Code</button>
                                </form>
                            </div>
                        </div> -->
            </div>
            <div class="col-lg-8">
                <div class="cart-btn-group">
                    <a href="{{ route('customer.product.index') }}" class="primary-btn3 btn-lg">Continue to shopping</a>
                    <a href="{{ route('customer.order.checkout') }}" class="primary-btn3 btn-lg">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div style="text-align: center;">
    <i class="bi bi-cart-plus-fill" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
    <h3>Your cart is empty</h3>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Looks like you haven't added any delicious food
        yet.</p>
    <a href="{{ route('customer.product.index') }}" class="primary-btn3 btn-lg">Start Ordering</a>
</div>
@endif
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
            url: "{{ url('cart') }}/" + rowId,
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