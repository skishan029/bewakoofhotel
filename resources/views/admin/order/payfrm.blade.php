<form action="" method="post" id="placeorder_form" onsubmit="placeOrderSubmit()">
    <input type="hidden" name="placeorder_id" value="{{ $productOrder->id }}">
   
    {{-- <div class="col-md-6"> --}}
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
                            <input id="given_amount" class="form-control rounded-0" type="number" name="given_amount" value="{{ number_format($productOrder->grand_total, '2', '.', '') }}" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" onkeyup="calculateReturnAmt()" onchange="calculateReturnAmt()" required readonly>
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="return_amount">Return Amount</label>
                            <input id="return_amount" class="form-control rounded-0" type="number" name="return_amount" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" readonly>
                        </div>
                    </div> --}}

                    <div class="col-md-4">
                        <div class="form-group">  
                            <label for="whatsapp_number">Whatsapp Number </label>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control rounded-0" onkeypress="return isNumber(event)" onkeypress="return isNumber(event)" minlength="10" maxlength="10">
                        </div>
                    </div>
                    

                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input id="discount" class="form-control rounded-0" type="number" name="discount" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" onkeyup="calculateGrandTotal()" onchange="calculateGrandTotal()" min="1">
                        </div>
                    </div> --}}
    
                </div>
            </div>
        </div>
        @props(['label'=>'Place Order','row'=>'3'])
        <x-submitbutton :label="$label" :row="$row" />
    {{-- </div> --}}
</form>