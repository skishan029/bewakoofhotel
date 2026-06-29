<script>
    function getSubRegions(selectedID, renderID) {
        let region_id = $(`#${selectedID} option:selected`).val();
        let url = "{{ route('customer.ajax.subregions') }}"+"?region_id="+region_id;
        let htmlOption = `<option value="">Select Sub Region *</option>`;
        if (region_id != "") {
            $.ajax({
                type: "GET",
                url: url,
                dataType: "JSON",
                beforeSend:function(){
                    $(`#${renderID}`).html(`<option value="">Please wait...</option>`);
                    $(`#${renderID}`).niceSelect('update');
                },
                success: function(response) {
                    $.each(response.data, function(key, value) {
                        htmlOption += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    $(`#${renderID}`).html(htmlOption);
                    // Refresh Nice Select
                    $(`#${renderID}`).niceSelect('update');
                },
                error: function(xhr, status, error) {
                    $(`#${renderID}`).html(htmlOption);
                    if (xhr.status === 422 || xhr.status === 400) {
                        toastr.error(xhr.responseJSON.message);
                    }else {
                        toastr.error("Something went wrong");
                    }
                }
            });
        }else {
            $(`#${renderID}`).html(htmlOption);
            $(`#${renderID}`).niceSelect('update');
        }
    }
</script>
