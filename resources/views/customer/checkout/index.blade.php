@extends('frontend.layout.layout')
@section('title', $title)

@php
    $resturentStatus = \App\Helper\Helper::getRestaurantStatus();
@endphp

@section('content')

@if ($resturentStatus == 'Closed')
    <div class="alert alert-danger">
        Resturent is Closed Now..
    </div>
@endif

@include('frontend.layout.breadcrumb')

<div class="checkout-section pt-40 pb-60">
    <div class="container">



        <form action="{{ route('customer.order.checkout') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-wrap box--shadow mb-15">
                        <h4 class="title-25 mb-20">Shipping Details</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-inner">
                                    <label for="name" class="form-label">Full Name <span style="color: red;">*</span></label>
                                    <input type="text" name="name" value="{{ Auth::guard('customer')->user()->name }}" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-inner">
                                    <label for="mobile">Mobile Number <span style="color: red;">*</span></label>
                                    <input type="text" name="mobile" value="{{ Auth::guard('customer')->user()->username }}" required readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-inner">
                                    <label for="alt_mobile">Order Note (Optional)</label>
                                    <textarea name="order_note" rows="3">{{ old('order_note') ?? '' }}</textarea>
                                    @if ($errors->has('order_note'))
                                        <small class="text-danger">{{ $errors->first('order_note') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-inner">
                                    <label for="address">Delivery Address <span style="color: red;">*</span></label>
                                    <textarea name="address" rows="3" required>{{ old('address') ?? Auth::guard('customer')->user()->address }}</textarea>
                                    @if ($errors->has('address'))
                                        <small class="text-danger">{{ $errors->first('address') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-inner">
                                    <!-- <select style="display: none;">
                                        <option>Town / City</option>
                                        <option>Dhaka</option>
                                        <option>Saidpur</option>
                                        <option>Newyork</option>
                                    </select>
                                    <div class="nice-select" tabindex="0"><span class="current">Town / City</span>
                                        <ul class="list">
                                            <li data-value="Town / City" class="option selected">Town / City</li>
                                            <li data-value="Dhaka" class="option">Dhaka</li>
                                            <li data-value="Saidpur" class="option">Saidpur</li>
                                            <li data-value="Newyork" class="option">Newyork</li>
                                        </ul>
                                    </div> -->
                                    <label for="landmark">Landmark <span style="color: red;">*</span></label>
                                    <textarea name="landmark" rows="3" required>{{ old('landmark') ?? Auth::guard('customer')->user()->landmark }}</textarea>
                                    @if ($errors->has('landmark'))
                                        <small class="text-danger">{{ $errors->first('landmark') }}</small>
                                    @endif
                                </div>
                            </div>
                            @php
                                $region_id = Auth::guard('customer')->user()->region_id;
                                $subregion_id = Auth::guard('customer')->user()->subregion_id;
                            // dd($region_id);
                            @endphp
                            <div class="col-lg-6">
                                <div class="form-inner">
                                    <label for="region_id">Region <strong style="color: red;">*</strong></label>
                                    <select  name="region_id" id="region_id" onchange="getSubRegions('region_id','subregion_id')">
                                        <option value="">Select Region</option>
                                        @foreach ($parentRegions as $item)
                                            <option value="{{ $item->id }}" @selected($region_id == $item->id)>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="nice-select" tabindex="0"><span class="current">{{ optional($parentRegions->firstWhere('id',$region_id))->name ?? 'Select Region' }}</span>
                                        <ul class="list">
                                            @foreach ($parentRegions as $item)
                                                <li data-value="{{ $item->id }}" class="option @selected($region_id == $item->id)">{{ $item->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @error('region_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-inner">
                                    <label for="subregion_id" class="form-label">Sub Region <strong style="color: red;">*</strong></label>
                                    <select name="sub_region_id" id="subregion_id">
                                        @if ($subRegions->isNotEmpty())
                                            @foreach ($subRegions as $item)
                                                <option value="{{ $item->id }}" @selected($subregion_id == $item->id)>{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="nice-select" tabindex="0"><span class="current">{{ optional($subRegions->firstWhere('id',$subregion_id))->name ?? 'Select Sub Region *' }}</span>
                                        <ul class="list">
                                            @if ($subRegions->isNotEmpty())
                                                @foreach ($subRegions as $item)
                                                    <li data-value="{{ $item->id }}" class="option @selected($subregion_id == $item->id)">{{ $item->name }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    @error('sub_region_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $shippingCharge = 0;
                @endphp
                <aside class="col-lg-5">
                    <div class="added-product-summary mb-10">
                        <h5 class="title-25 checkout-title">
                            Order Summary
                        </h5>
                        <ul class="added-products">
                            @foreach ($cart as $item)
                            @php
                                $shippingCharge += $item->options['shipping_cost'] ?? 0;
                            @endphp
                                <li class="single-product d-flex justify-content-start">
                                    <div class="product-info">
                                        <h5 class="product-title"><a>{{ $item->name }}</a></h5>
                                        <div class="product-total d-flex align-items-center">
                                            <strong><span class="product-quantity">{{ $item->qty }}</span> <i class="bi bi-x-lg px-2"></i>
                                                <span class="product-price">₹ {{ $item->price }}</span>
                                            </strong>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="summery-card cost-summery mb-10">
                        <table class="table cost-summery-table">
                            <thead>
                                <tr>
                                    <th>Subtotal</th>
                                    <th>₹ {{ number_format(floatval($subTotal), 2) }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="tax">Shipping Charge</td>
                                    <td>Free</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="summery-card total-cost mb-10">
                        <table class="table cost-summery-table total-cost">
                            <thead>
                                <tr>
                                    <th>Total</th>
                                    <th>₹ {{ number_format(floatval($subTotal), 2) }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="payment-methods mb-10">
                        <div class="form-check payment-check">
                            <input class="form-check-input" type="radio" name="payment_option" id="flexRadioDefault2" value="cash" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Cash on delivery
                            </label>
                            <p class="para">Pay with cash upon delivery.</p>
                        </div>
                        <!-- <div class="form-check payment-check">
                            <input class="form-check-input" type="radio" name="payment_option" id="flexRadioDefault3" value="online" checked>
                            <label class="form-check-label" for="flexRadioDefault3">
                                PayPal
                            </label>
                            <p class="para">Pay with cash upon delivery.</p>
                        </div> -->
                    </div>
                    <div class="place-order-btn">
                        @if ($resturentStatus != 'Closed')
                            <button type="submit" class="primary-btn8 lg--btn">Place Order</button>
                        @else
                            <button type="submit" class="primary-btn8 lg--btn" disabled>Resturent Closed</button>
                        @endif
                    </div>
                </aside>
            </div>
        </form>





    </div>
</div>

@push('script')
    @include('customer.layout.ajax.region-ajax')
@endpush

@push('script')
<script>
$(document).ready(function () {

    $('#checkout-form').on('submit', function(e) {

        var region_id = $('#region_id').next('.nice-select').find('.option.selected').data('value');
        var subregion_id = $('#subregion_id').next('.nice-select').find('.option.selected').data('value');
    
        if (!region_id) {
            e.preventDefault();
            toastr.error('Please Select Region');
            return false;
        }

        if (!subregion_id) {
            e.preventDefault();
            toastr.error('Please Select Sub Region');
            return false;
        }
        // Optional: update actual select values before submit
        $('#region_id').val(region_id);
        $('#subregion_id').val(subregion_id);
    });

});
</script>
@endpush
@endsection