<script>
    $(function () {  

        calculateGrandTotal();

        $('#invoice_form').submit(function (e) { 
            e.preventDefault();
            if ( $('#invoice_form').parsley().isValid()) {

                if(isValidGrandTotal() == true) {
                    const formData = new FormData(this);
                    const formID = 'invoice_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ $submitURL }}",
                        data: formData,
                        processData: false,
                        contentType : false,
                        cache: false,
                        beforeSend: function () {  
                            setProcessingButton(formID);
                            $("#"+formID+" [name='submit2']").prop('disabled', true);
                        },
                        success: function (response) {
                            if (response.type == 'success') {

                                @if (empty($productOrder))
                                    //setRedirect(response);
                                    window.location.href = response.url;
                                @else
                                    setRedirect(response);
                                @endif
                                
                                
                            }else if(response.type == 'error'){
                                @if (empty($productOrder))
                                    resetButton(formID, 'Place Order');
                                @else
                                    resetButton(formID, 'Update');
                                @endif
                                
                                setErrorMessage(response);
                            }
                        }
                    });
                }else{
                    toastr.error('Grand total must be greater than 0');
                }
            }
        });
    });

    function submitPending() {
        if(isValidGrandTotal() == true) {
            const formData = new FormData(invoice_form);
            formData.append("pending", "1");
            const formID = 'invoice_form';
            $.ajax({
                type: "POST",
                url: "{{ $submitURL }}",
                data: formData,
                processData: false,
                contentType : false,
                cache: false,
                beforeSend: function () {  
                    // setProcessingButton(formID);
                    $("#"+formID+" [name='submit']").prop('disabled', true);
                    $("#"+formID+" [name='submit2']").prop('disabled', true);
                    $("#"+formID+" [name='submit2']").html('<span class="spinner-border spinner-border-sm"></span><span class="visually-hidden">&nbsp;&nbsp;Processing</span>');
                },
                success: function (response) {
                    if (response.type == 'success') {

                        @if (empty($productOrder))
                            //setRedirect(response);
                            window.location.href = response.url;
                        @else
                            setRedirect(response);
                        @endif
                        
                        
                    }else if(response.type == 'error'){
                        @if (empty($productOrder))
                            resetButton(formID, 'Place Order');
                        @else
                            resetButton(formID, 'Update');
                        @endif
                        
                        setErrorMessage(response);
                    }
                }
            });
            console.log(formData);
        }else{
            toastr.error('Grand total must be greater than 0');
        }

        
    }

    function setTransactionNumber(payment_option) {
        if (payment_option == 'cash') {
            $('#transaction_no').attr('disabled', true);
        }else {
            $('#transaction_no').attr('disabled', false);
        }
    }
    function isValidGrandTotal() {
        let subtotal = 0;
        $( ".total" ).each(function() {
            if ($(this).val() !== '') {
                subtotal = parseFloat(subtotal) + parseFloat( $(this).val() );
            }
        });
        if ( $('#discount').val() != '') {
            let discount = parseFloat( $('#discount').val() );
            if (discount < parseFloat(subtotal)) {
                subtotal = parseFloat(subtotal) - parseFloat(discount);
            }
        }
        if (parseFloat(subtotal) > 0) {
            return true;
        }else{
            return false;
        }
    }
    function calculateGrandTotal() {
        let subtotal = 0; htmlString = '';
        $( ".total" ).each(function() {
            if ($(this).val() !== '') {
                subtotal = parseFloat(subtotal) + parseFloat( $(this).val() );
                product_name_id = $(this).attr('id').replaceAll('total_','');
                product_name = $(`#product_name_${product_name_id}`).val();
                product_price = parseFloat( $(this).val() );
                price = parseFloat( $(`#price_${product_name_id}`).val() );
                quantity = parseInt( $(`#quantity_${product_name_id}`).val() );

                if (product_price > 0) {
                    htmlString +=`
                        <tr>
                            <td style="border-top: none; width: 5px;"><button type="button" class="btn btn-sm text-danger" style="line-height: 0; padding: 0;" value="${product_name_id}" onclick="deleteCartproductlist(this.value)"><small><i class="fas fa-trash-alt"></i></small></button></td>
                            <td style="border-top: none;">${product_name}</td>
                            <td style="border-top: none;">${quantity}<small>x</small>&#8377;${price.toFixed(2)}</td>
                            <th class="text-right" style="border: none;">&#8377;<span>${product_price.toFixed(2)}</span></th>
                        </tr>
                    `;
                }
                
            }
        });
        $('#cartproductlist').html(htmlString);

        $('#subtotal_span').text( subtotal.toFixed(2) );

        if ( $('#discount').val() != '') {
            let discount = parseFloat( $('#discount').val() );
            if (discount > (parseFloat(subtotal)-1)) {
                $('#discount').val('');
                $('#discount_span').text('0.00');
            }else{
                subtotal = parseFloat(subtotal) - parseFloat(discount);
                $('#discount_span').text(discount.toFixed(2));
            }
        }else{
            $('#discount_span').text('0.00');
        }
        if (parseFloat(subtotal) > 0) {
            $("#invoice_form [name='submit']").prop('disabled', false);
            $("#invoice_form [name='submit2']").prop('disabled', false);
        } else {
            $("#invoice_form [name='submit']").prop('disabled', true);
            $("#invoice_form [name='submit2']").prop('disabled', true);
        }
        $('#grand_total').val(subtotal);
        $('#grand_total_span').text( subtotal.toFixed(2) );
        $('#grand_total').val(subtotal);
        $('#given_amount').val(subtotal.toFixed(2));
        $('#given_amount').attr('min', subtotal);
        $('#given_amount').parsley().reset();

        calculateReturnAmt();
    }
    function addQty(evt) {
        if (evt.value != '') {
            val = JSON.parse(evt.value);
            if (jQuery.isEmptyObject(val) === false) {
                const quantityid = `#quantity_${val.plate_type}_${val.id}`;
                quantity = parseInt($(quantityid).val());
                $(quantityid).val( parseInt(quantity) + 1);
                total = parseFloat(val.price) * (parseInt(quantity) + 1);
                $(`#total_${val.plate_type}_${val.id}`).val(total);
                calculateGrandTotal();
            }
        }
    }
    function lessQty(evt) {
        if (evt.value != '') {
            val = JSON.parse(evt.value);
            if (jQuery.isEmptyObject(val) === false) {
                const quantityid = `#quantity_${val.plate_type}_${val.id}`;
                quantity = parseInt($(quantityid).val());
                if (quantity > 0) {
                    $(quantityid).val( parseInt(quantity) - 1);
                    total = parseFloat(val.price) * (parseInt(quantity) - 1);
                    $(`#total_${val.plate_type}_${val.id}`).val(total);
                    calculateGrandTotal();
                }
            }
        }
    }
    function deleteCartproductlist(row) {
        $(`#quantity_${row}`).val('0');
        $(`#total_${row}`).val('0');
        calculateGrandTotal();
    }
    function calculateReturnAmt() {
        if ($('#grand_total').val() != '') {
            let given_amount = 0;
            grand_total = parseFloat($('#grand_total').val());
            if ( $('#given_amount').val() == '') {
                //$('#given_amount').val(grand_total.toFixed(2));
                //$('#return_amount').val('0.00');
            }else{
                given_amount = parseFloat($('#given_amount').val());
                if (given_amount >= grand_total) {
                    return_amount = parseFloat(given_amount) - parseFloat(grand_total);
                }else{
                    return_amount = 0;
                }
                $('#return_amount').val(return_amount.toFixed(2));
            }
        }else{
            $('#given_amount').val('');
            $('#return_amount').val('');
        }
    }
    
</script>