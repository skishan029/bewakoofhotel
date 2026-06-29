@extends('customer.layout.app')

@section('title', 'Checkout')

@php
    $resturentStatus = \App\Helper\Helper::getRestaurantStatus();
@endphp

@section('content')

    @if ($resturentStatus == 'Closed')
        <div class="alert alert-danger">
            Resturent is Closed Now..
        </div>
    @endif

    <div class="row" style="max-width: 1000px; margin: 0 auto; display: flex; gap: 2rem; flex-wrap: wrap;">
        <div class="col-md-8" style="flex: 1; min-width: 300px;">
            <div class="card">
                <h3 style="margin-bottom: 1.5rem;">Shipping Details</h3>
                <form action="{{ route('customer.order.checkout') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name <span style="color: red;">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="{{ Auth::guard('customer')->user()->name }}" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="mobile" class="form-label">Mobile Number <span style="color: red;">*</span></label>
                        <input type="text" name="mobile" class="form-control"
                            value="{{ Auth::guard('customer')->user()->username }}" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="alt_mobile" class="form-label">Order Note (Optional)</label>
                        <textarea name="order_note" class="form-control" rows="3">{{ old('order_note') ?? '' }}</textarea>
                        @if ($errors->has('order_note'))
                            <small class="text-danger">{{ $errors->first('order_note') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">Delivery Address <span style="color: red;">*</span></label>
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address') ?? Auth::guard('customer')->user()->address }}</textarea>
                        @if ($errors->has('address'))
                            <small class="text-danger">{{ $errors->first('address') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="landmark" class="form-label">Landmark <span style="color: red;">*</span></label>
                        <textarea name="landmark" class="form-control" rows="3" required>{{ old('landmark') ?? Auth::guard('customer')->user()->landmark }}</textarea>
                        @if ($errors->has('landmark'))
                            <small class="text-danger">{{ $errors->first('landmark') }}</small>
                        @endif
                    </div>

                    @php
                        $region_id = Auth::guard('customer')->user()->region_id;
                        $subregion_id = Auth::guard('customer')->user()->subregion_id;
                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="region_id" class="form-label">Region <strong
                                        style="color: red;">*</strong></label>
                                <select name="region_id" id="region_id" class="form-select"
                                    onchange="getSubRegions('region_id','subregion_id')" required>
                                    <option value="">--Select Region</option>
                                    @foreach ($parentRegions as $item)
                                        <option value="{{ $item->id }}" @selected($region_id == $item->id)>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>

                                @error('region_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subregion_id" class="form-label">Sub Region <strong
                                        style="color: red;">*</strong></label>
                                <select name="sub_region_id" id="subregion_id" class="form-select" required>
                                    <option value="">--Select Region</option>
                                    @if ($subRegions->isNotEmpty())
                                        @foreach ($subRegions as $item)
                                            <option value="{{ $item->id }}" @selected($subregion_id == $item->id)>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @error('sub_region_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <h3 style="margin: 2rem 0 1rem 0;">Payment Method</h3>
                    <div class="payment-options" style="margin-bottom: 2rem;">
                        <label
                            style="display: flex; align-items: center; gap: 10px; background: rgba(15, 23, 42, 0.5); padding: 1rem; border-radius: 8px; border: 1px solid var(--border); cursor: pointer;">
                            <input type="radio" name="payment_option" value="cash" checked>
                            <span><i class="fa-solid fa-money-bill-wave" style="color: #34d399; margin-right: 5px;"></i>
                                Cash on Delivery</span>
                        </label>

                        {{-- <label
                            style="display: flex; align-items: center; gap: 10px; background: rgba(15, 23, 42, 0.5); padding: 1rem; border-radius: 8px; border: 1px solid var(--border); cursor: pointer;"
                            class="mt-1">
                            <input type="radio" name="payment_option" value="online">
                            <span><i class="fa-solid fa-credit-card" style="color: #34d399; margin-right: 5px;"></i>
                                Online Payment</span>
                        </label> --}}

                        @if ($errors->has('payment_option'))
                            <small class="text-danger">{{ $errors->first('payment_option') }}</small>
                        @endif
                    </div>


                    @if ($resturentStatus != 'Closed')
                        <button type="submit" class="action-btn" style="width: 100%; font-size: 1.1rem; padding: 1rem;">
                            Place Order <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
                        </button>
                    @else
                        <button type="button" disabled class="action-btn"
                            style="width: 100%; font-size: 1.1rem; padding: 1rem; background: #dc2626;">
                            Resturent Closed
                        </button>
                    @endif
                </form>
            </div>
        </div>

        @php
            $shippingCharge = 0;
        @endphp
        <div class="col-md-4" style="width: 350px;">
            <div class="card">
                <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
                <div class="summary-items" style="max-height: 300px; overflow-y: auto; margin-bottom: 1rem;">
                    @foreach ($cart as $item)
                        @php
                            $shippingCharge += $item->options['shipping_cost'] ?? 0;
                        @endphp
                        <div class="summary-item"
                            style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem;">
                            <span>
                                {{ $item->qty }}x {{ $item->name }}
                                @if ($item->options['is_online_label_show'])
                                    - {{ $item->options['plate_label'] }}
                                @endif
                            </span>
                            <span>₹ {{ $item->price * $item->qty }}</span>
                        </div>
                    @endforeach
                </div>
                <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Subtotal</span>
                        <span>₹ {{ number_format(floatval($subTotal), 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Shipping Charge</span>
                        <span>Free</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.2rem; color: var(--primary); margin-top: 10px;">
                        <span>Grand Total</span>
                        <span>₹ {{ number_format(floatval($subTotal), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        @include('customer.layout.ajax.region-ajax')
    @endpush
@endsection
