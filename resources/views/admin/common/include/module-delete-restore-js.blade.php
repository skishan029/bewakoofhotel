<script>
    function commonDeleteRestore(evt) {
        if (evt.value != '') {
            val = JSON.parse(evt.value);
            if (jQuery.isEmptyObject(val) === false) {

                let confirmButtonText = '';
                if (val.status == '2') {
                    confirmButtonText = "Yes, delete it!";
                    confirmButtonClass = "btn-danger";
                } else {
                    confirmButtonText = "Yes, restore it!";
                    confirmButtonClass = "btn-success";
                }

                swal({
                    title: confirmButtonText,
                    text: "Submit to run request",
                    type: "info",
                    confirmButtonClass: confirmButtonClass,
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function() {

                    $.ajax({
                        type: "POST",
                        url: "{{ $changeStatusURL }}",
                        data: val,
                        dataType: "JSON",
                        success: function(response) {
                            if (response.type == 'success') {
                                $('#dataTable').DataTable().ajax.reload(null, false);
                                swal("Message!", response.message, "success");
                            } else {
                                swal.close();
                                setErrorMessage(response);
                            }
                        }
                    });
                });
            }
        }
    }
</script>
