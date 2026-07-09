function setProcessingButton(formID) {
    $("#" + formID + " [name='submit']").prop('disabled', true);
    $("#" + formID + " [name='submit']").html('<span class="spinner-border spinner-border-sm"></span><span class="visually-hidden">&nbsp;&nbsp;Processing</span>');
}
function setSuccessButton(response, formID, label = 'Submit') {
    resetButton(formID, label);
    $("#" + formID)[0].reset();
    $('#' + formID).parsley().reset();
    toastr.success(response.message);
}
function setUpdateSuccessButton(response, formID, label = 'Submit') {
    resetButton(formID, label);
    $('#' + formID).parsley().reset();
    toastr.success(response.message);
}
function resetButton(formID, label = 'Submit') {
    $("#" + formID + " [name='submit']").prop('disabled', false);
    $("#" + formID + " [name='submit']").text(label);
}

function setErrorMessage(response) {
    if (Array.isArray(response.message)) {
        response.message.forEach(function (val) { toastr.error(val); });
    } else { toastr.error(response.message); }
}
function setRedirect(response) {
    alert(response.message);
    window.location.href = response.url;
}

/* USE :  onkeypress="return isNumber(event)" */
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


/* USE : onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" */
function firstDeci() {
    $('input').keypress(function (evt) {
        if (evt.which == ".".charCodeAt(0) && $(this).val().trim() == "") {
            return false;
        }
    });
}

function isNumberKey(txt, evt) {

    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        /*Check if the text already contains the . character */
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}

/* USE :  onkeypress="return onlyNumberKey(event)" type="tel" */
function onlyNumberKey(evt) {
    /* Only ASCII character in that range allowed */
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) {
        return false;
    }
    return true;
}
function titleCase(str) {
    var splitStr = str.toLowerCase().split(' ');
    for (var i = 0; i < splitStr.length; i++) {
        splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
    }
    return splitStr.join(' ');
}
function allCaps(id) {
    var myid = $('#' + id).val();
    $('#' + id).val(myid.toUpperCase());
}

function handleAjaxError(xhr, type = 'toastr') {
    const status = xhr.status;
    const message = (xhr.responseJSON && xhr.responseJSON.message) || 'An error occurred';

    const showError = (msg) => {
        switch (type) {
            case 'alert':
                alert(msg);
                break;
            case 'toastr':
                toastr.error(msg);
                break;
            case 'swal':
                swal("Error", msg, "error");
                break;
            default:
                console.error(msg);
        }
    };

    if ([400, 404, 422, 401].includes(status)) {
        showError(message);
    } else if (status === 500) {
        showError('Internal server error');
    } else {
        showError(`Unexpected error (${status})`);
    }
}