(function (window, document, $) {
    
    
    // get client 
    // -----------------------------------------------------------
    $.ajax({
        type:"GET",
        url:'/admin/finance/fund-management-get-client',
        dataType: 'json',
        success:function (data) {
            $("#trader").html(data);                
        }
    });
    // END: Client Type

    // ---------------------------------------------------------------------------
    // START: get finance details
    $(document).on('change','#trader',function () {
        let client_id = $(this).val();
        $.ajax({
            type: "GET",
            url: '/admin/finance/get-client-finance/'+client_id,
            dataType: 'json',
            success: function (data) {
                if (data.status == true) {
                    $("#client").html(data.users);
                    $("#wallet-balance").html(data.wallet_balance);
                    $("#last-deposit").html(data.last_deposit);
                    $("#last-withdraw").html(data.last_withdraw);
                    $("#last-deposit-date").html(data.last_deposit_date);
                    $("#last-withdraw-date").html(data.last_withdraw_date);
                    $("#last-d-date-list").addClass('d-flex').slideDown();
                    $("#last-w-date-list").addClass('d-flex').slideDown();
                    // $("#user-type").html(data.user_type);
                    $("#user-name-top").html(data.user_name);
                    $("#last-deposit-status").html(data.deposit_status);
                    $("#last-withdraw-status").html(data.withdraw_status);
                    $("#last-withdraw-status").slideDown();
                    $("#last-deposit-status").slideDown();
                    // deposit approved status
                    if (data.deposit_status.toLowerCase() === 'approved') {
                        $("#last-deposit-status").addClass('bg-success').removeClass('bg-danger');
                    }
                    else if (data.deposit_status.toLowerCase()==='pending') {
                        $("#last-deposit-status").addClass('bg-warning').removeClass('bg-danger bg-success');
                    }
                    else{
                        $("#last-deposit-status").addClass('bg-danger').removeClass('bg-success bg-warning');
                    }

                    // withdraw approved status
                    if (data.withdraw_status.toLowerCase() === 'approved') {
                        $("#last-withdraw-status").addClass('bg-success').removeClass('bg-danger');
                    }
                    else if (data.withdraw_status.toLowerCase()==='pending') {
                        $("#last-withdraw-status").addClass('bg-warning').removeClass('bg-danger bg-success');
                    }
                    else if (data.withdraw_status.toLowerCase()==='decline') {
                        $("#last-withdraw-status").addClass('bg-danger').removeClass('bg-success bg-warning');
                    }
                    else{
                        $("#last-withdraw-status").addClass('bg-secondary').removeClass('bg-success bg-secondary');
                    }

                    // trading accounts
                    $("#trading_account").html(data.trading_account);
                }
            }
        });//END: get client
    })// END: Get finance detailed

    // START: get transaction method
    // ------------------------------------------------------------------------------
    $(document).on("change","#type",function () {
        let type = $(this).val();
        if (type === 'deduct') {
            $('#method-row').slideUp();
        }
        else{
            $('#method-row').slideDown();
        }
    });

    // if onload trader selected
    let client_type = $('#type').val("");
    // if onload method bank selected
    $('#method').val('');


})(window, document, jQuery);