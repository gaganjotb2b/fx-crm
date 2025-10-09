(function (window, document, $) {
    $("#client-type").on("change", function () {
        let type = $(this).val();
        $("#client").prop("selected", false);
        if (type === '4') {
            $("#method-row").slideUp();
        }
        else {
            $("#method-row").slideDown();
        }
        // END: Show/hide method row
        // -------------------------------------------------------------
        // START: get client
        $.ajax({
            type: "GET",
            url: '/admin/finance/get-client/' + type,
            dataType: 'json',
            success: function (data) {
                if (data.status == true) {
                    // $("#client").html(data.users);
                }
                if (data.status == false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Admin Group!',
                        html: data.errors.group_name,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    })
                }
            }
        });//END: get client
    });
    // END: Client Type

    // ---------------------------------------------------------------------------
    // START: get finance details
    $(document).on('change', '#client', function () {
        let client_id = $(this).val();
        if (client_id == "") {
            client_id = $(this).data('value');
        }
        $.ajax({
            type: "GET",
            url: '/admin/finance/get-client-finance/' + client_id,
            data: { client_type: $("#client-type").val() },
            dataType: 'json',
            success: function (data) {
                if (data.status == true) {
                    // $("#client").html(data.users);
                    $("#wallet-balance").html(data.wallet_balance);
                    $("#last-deposit").html(data.last_deposit);
                    // $("#last-withdraw").html(data.last_withdraw);
                    $("#last-deposit-date").html(data.last_deposit_date);
                    $("#last-withdraw-date").html(data.last_withdraw_date);
                    $("#last-d-date-list").addClass('d-flex').slideDown();
                    $("#last-w-date-list").addClass('d-flex').slideDown();
                    $("#user-type").html(data.user_type);
                    $("#user-name-top").html(data.user_name);
                    $("#last-deposit-status").html(data.deposit_status);
                    $("#last-withdraw-status").html(data.withdraw_status);
                    $("#last-withdraw-status").slideDown();
                    $("#last-deposit-status").slideDown();
                    $(".user-avatar").attr("src", data.avatar);
                    // deposit approved status
                    if (data.deposit_status.toLowerCase() === 'approved') {
                        $("#last-deposit-status").addClass('bg-success').removeClass('bg-danger');
                    }
                    else if (data.deposit_status.toLowerCase() === 'pending') {
                        $("#last-deposit-status").addClass('bg-warning').removeClass('bg-danger bg-success');
                    }
                    else {
                        $("#last-deposit-status").addClass('bg-danger').removeClass('bg-success bg-warning');
                    }
                    // withdraw approved status
                    if (data.withdraw_status.toLowerCase() === 'approved') {
                        $("#last-withdraw-status").addClass('bg-success').removeClass('bg-danger');
                    }
                    else if (data.withdraw_status.toLowerCase() === 'pending') {
                        $("#last-withdraw-status").addClass('bg-warning').removeClass('bg-danger bg-success');
                    }
                    else {
                        $("#last-withdraw-status").addClass('bg-danger').removeClass('bg-success bg-warning');
                    }
                    // hidden last deposit for IB
                    if (data.user_type.toLowerCase() == 'ib') {
                        $("#last-deposit-li").slideUp();
                    } else {
                        $("#last-deposit-li").slideDown();
                    }
                }
            }
        });//END: get client
    })// END: Get finance detailed

    // START: Get bank filed
    // ------------------------------------------------------------------------------
    $(document).on("change", "#method", function () {
        let method = $(this).val();
        let client_id = $("#client").val();
        if (method === 'bank') {
            $("#bank-row").slideDown();
            $.ajax({
                type: "GET",
                url: '/admin/finance/balance-management-bank/' + client_id,
                dataType: 'json',
                success: function (data) {
                    if (data.status == true) {
                        $("#client-bank").html(data.banks);
                    }
                    if (data.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Client Bank!',
                            html: 'Client bank account not found!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            });
        }
        else {
            $("#bank-row").slideUp();
        }
    });

    // if onload trader selected
    let client_type = $('#client-type').val("");
    // if onload method bank selected
    $('#method').val('');


})(window, document, jQuery);