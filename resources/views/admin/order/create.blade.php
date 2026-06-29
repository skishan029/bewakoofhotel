@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Invoice')

@section('content')

<!-- Main content -->
<!--<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-widget widget-user-2">
                    <div class="widget-user-header">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="../dist/img/user7-128x128.jpg" alt="User Avatar" />
                        </div>
                        <h5 class="widget-user-desc">Lead Developer</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link"> Projects <span class="float-right badge bg-primary">31</span> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>-->
<div class="content">
    <div class="container-fluid">
        <form action="" method="post" id="invoice_form" data-parsley-validate enctype="multipart/form-data">
            <input id="grand_total" type="hidden" name="grand_total" value="">
            <div class="row">
                @forelse ($allproducts as $product)
                    @if (!empty($product->full_price))

                        @php
                            $jsonArr = json_encode([
                                'id'=> $product->id,
                                'plate_type'=>'1',
                                'price'=> $product->full_price,
                            ]);

                            $product_name = $product->product_name." - Full";
                            if ($product->full_lbl_show == '2') {
                                $product_name = $product->product_name;
                            }
                        @endphp
                        <div class="col-xl-3 col-lg-3 col-sm-12 col-md-12">
                            <div class="card shadow">
                                <div class="">
                                    <input id="product_id_{{ $product->id }}" type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                    <input id="plate_type_{{ $product->id }}" type="hidden" name="plate_types[]" value="1">
                                    <input id="price_1_{{ $product->id }}" class="form-control rounded-0" type="hidden" name="prices[]" value="{{ $product->full_price }}">
                                    <input id="total_1_{{ $product->id }}" class="form-control rounded-0 total" type="hidden" name="totals[]">
                                    <input id="product_name_1_{{ $product->id }}" class="form-control rounded-0" type="hidden" value="{{ $product_name }} ">

                                    <div class="row">
                                        <div class="col-6" style="background: url('{{ Storage::url($product->featured_photo) }}');background-repeat: no-repeat;background-position: left;background-size: cover;">
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" id="addqty_{{ $product->id }}" class="btn btn-primary btn-sm" value="{{ $jsonArr }}" onclick="addQty(this)"><i class="fas fa-plus"></i></button>
                                                </div>
                                                <input type="text" class="form-control" readonly value="0" id="quantity_1_{{ $product->id }}" name="quantitys[]">
                                                <div class="input-group-append">
                                                    <button type="button" id="lessqty_{{ $product->id }}" class="btn btn-danger btn-sm" value="{{ $jsonArr }}" onclick="lessQty(this)"><i class="fas fa-minus"></i></button>
                                                </div>
                                            </div>
                                            <h6 style="height:55px;">
                                                <small class="mt-1"><b>{{ $product_name }}</b></small>
                                            </h6>
                                            <h6><b>&#8377;{{ number_format($product->full_price, '2', '.', '') }}</b></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($product->half_price))

                        @php
                            $jsonArr = json_encode([
                                'id'=> $product->id,
                                'plate_type'=>'2',
                                'price'=> $product->half_price,
                            ]);
                        @endphp
                        <div class="col-xl-3 col-lg-3 col-sm-12 col-md-12">
                            <div class="card shadow">
                                <div class="">
                                    <input id="product_id_{{ $product->id }}" type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                    <input id="plate_type_{{ $product->id }}" type="hidden" name="plate_types[]" value="2">
                                    <input id="price_2_{{ $product->id }}" class="form-control rounded-0" type="hidden" name="prices[]" value="{{ $product->half_price }}">
                                    <input id="total_2_{{ $product->id }}" class="form-control rounded-0 total" type="hidden" name="totals[]">
                                    <input id="product_name_2_{{ $product->id }}" class="form-control rounded-0" type="hidden" value="{{ $product->product_name }} - Half">

                                    <div class="row">
                                        <div class="col-6" style="background: url('{{ Storage::url($product->half_photo) }}');background-repeat: no-repeat;background-position: left;background-size: cover;">
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" id="addqty_{{ $product->id }}" class="btn btn-primary btn-sm" value="{{ $jsonArr }}" onclick="addQty(this)"><i class="fas fa-plus"></i></button>
                                                </div>
                                                <input type="text" class="form-control" readonly value="0" id="quantity_2_{{ $product->id }}" name="quantitys[]">
                                                <div class="input-group-append">
                                                    <button type="button" id="lessqty_{{ $product->id }}" class="btn btn-danger btn-sm" value="{{ $jsonArr }}" onclick="lessQty(this)"><i class="fas fa-minus"></i></button>
                                                </div>
                                            </div>
                                            <h6 style="height:55px;">
                                            @if (!empty($product->half_photo))
                                                <!--<img class="img-fluid" src="{{ Storage::url($product->half_photo) }}" alt="{{ $product->product_name }}" style="width: 40px; height:23px;">-->
                                            @endif
                                            <small class="mt-1"><b>{{ $product->product_name }} - Half</b></small>
                                            </h6>
                                            <h6><b>&#8377;{{ number_format($product->half_price, '2', '.', '') }}</b></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-xl-4 col-lg-6 col-sm-12 col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4><small>No Data.....</small></h4>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="{{ Helper::adminCardClass() }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_time">Date and time</label>
                                        <input id="date_time" class="form-control rounded-0" type="text" readonly disabled value="{{ date('d-m-Y g:i a') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_option">Payment Option</label>
                                        <select id="payment_option" class="form-control rounded-0" name="payment_option" onchange="setTransactionNumber(this.value)">
                                            @foreach (Helper::getPaymentOption() as $key=> $item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input id="name" class="form-control rounded-0" type="text" name="name">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="given_amount">Given Amount <strong class="text-danger">*</strong></label>
                                        <input id="given_amount" class="form-control rounded-0" type="number" name="given_amount" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" onkeyup="calculateReturnAmt()" onchange="calculateReturnAmt()" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="return_amount">Return Amount</label>
                                        <input id="return_amount" class="form-control rounded-0" type="number" name="return_amount" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">  
                                        <label for="whatsapp_number">Whatsapp Number </label>
                                        <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control rounded-0" onkeypress="return isNumber(event)" onkeypress="return isNumber(event)" minlength="10" maxlength="10">
                                    </div>
                                </div>
                                

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="discount">Discount</label>
                                        <input id="discount" class="form-control rounded-0" type="number" name="discount" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" onkeyup="calculateGrandTotal()" onchange="calculateGrandTotal()" min="1">
                                    </div>
                                </div>
                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="{{ Helper::adminCardClass() }}">
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <tbody id="cartproductlist"></tbody>
                                
                            </table>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="border-top: none;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Sub Total</td>
                                        <th class="text-right">&#8377;<span id="subtotal_span">0.00</span></th>
                                    </tr>
                                    <tr>
                                        <td style="border-top: none;" colspan="3">Discount</td>
                                        <td class="text-right text-danger" style="border-top: none;">- &#8377;<span id="discount_span">0.00</span></td>
                                    </tr>
                                    
                                    <tr>
                                        <th style="border-top: none;" colspan="3">Grand Total</th>
                                        <th class="text-right" style="border-top: none;">&#8377;<span id="grand_total_span">0.00</span></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <a href="{{ route('admin.order.create') }}" class="btn btn-danger btn-block">Cancel Order</a>
                        </div>
                        <div class="col-4">
                            <button type="button" name="submit2" onclick="submitPending()" class="btn btn-primary btn-block">Pending Order</button>
                        </div>
                        <div class="col-4">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('script')
    @include('admin.order.js.formscript')
@endpush

@endsection