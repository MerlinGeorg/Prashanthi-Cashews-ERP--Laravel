
// $(function () {
//     'use strict';
     

 function fetchSubAccount() {
  
    var acc_slug = $('#accountList').val();
    $.ajax({
        url: "{{ url('stockyard/rcn-stock/sub-account-list') }}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "acc_slug": acc_slug
        },
        success: function(response) {
            $('select[name="sub_account_id"]').html('<option value=""> -Select- </option>');
            $.each(response.sub_accounts, function(key, value) {
                var selected = $('select[name="sub_account_id"]').data(
                    'selected');
                $('select[name="sub_account_id"]').append(
                    '<option value=" ' + value
                    .id + '"' + (selected == value.id ?
                        'selected="selected"' : "") + '>' + value
                    .account_state + '</option>');
            })
        }
    })

}

//});