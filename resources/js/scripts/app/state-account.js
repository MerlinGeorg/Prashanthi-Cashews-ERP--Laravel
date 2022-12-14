
function getstateaccounts() {
        
        let stateVal = $('.state_account').val();
        if (stateVal !='undefined' && stateVal !='') {
            $.ajax({
                url: "/admin/state_account/" + stateVal,
                type: "GET",
                success: function (response) {
                    var newOptionsSelect = '<option value="">Select</option>';
                    if (response['account'].length > 0) {
                        response['account'].forEach(element => {
                            newOptionsSelect = newOptionsSelect + '<option value="' + element["slug"] + '">' + element["account_name"] + '</option>';
                        });
                    }
                    //$('.state_account_list').find('option').remove().end().append( newOptionsSelect );
                    $('.state_account_list').each(function () {
                        var oldVal = $(this).val() || $(this).data('selected');
                        
                        $(this).find('option').remove().end().append(newOptionsSelect);
                        
                        $(this).val(oldVal).select2();
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                }
            });
        }
}
