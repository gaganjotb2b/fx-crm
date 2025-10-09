
(function (window, document, $) {
    // change banks-------------------
    $(document).on("change", "#bank", function () {
        let bank_name = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: 'post',
            dataType: 'json',
            url: '/user/withdraw/bank',
            data: { bank_name: bank_name, op: 'banks' },
            success: function (data) {
                var value = (data.bank_options);
                $("#bank-account").html(value)
                $("#bank-account-name").val(data.bank_accounts.bank_account_name);
                $("#swift-code").val(data.bank_accounts.swift_code);
                $("#iban").val(data.bank_accounts.iban);
                $("#country").html(data.bank_accounts.country);
                $("#address").val(data.bank_accounts.address);
                $("#swift-code-label").text(data.bank_accounts.swift_code_label);
                $("#currency_name").val(data.bank_accounts.currency_name);
                if (data.bank_accounts.currency_name == "") {
                    $(".currency-field").addClass('d-none');
                    $(".local-currency").html("(" + data.bank_accounts.currency_name + ")");
                } else {
                    $(".currency-field").removeClass('d-none');
                    $(".local-currency").html("(" + data.bank_accounts.currency_name + ")");
                }
                // console.log(data.bank_accounts.currency_name);

                $("#transaction_type").val(data.bank_accounts.transaction_type);
                $("#currency").val(data.bank_accounts.currency_name);
            }
        })
    });

    // change bank accounts ----------------------
    $(document).on("change", "#bank-account", function () {
        let bank_account = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: 'post',
            dataType: 'json',
            url: '/user/withdraw/bank',
            data: { bank_account: bank_account, op: 'bank-accounts' },
            success: function (data) {
                $("#bank-account").html(data.bank_options);
                $("#bank-account-name").val(data.bank_accounts.bank_account_name);
                $("#swift-code").val(data.bank_accounts.swift_code);
                $("#iban").val(data.bank_accounts.iban);
                $("#country").html(data.bank_accounts.country);
                $("#address").val(data.bank_accounts.address);
                $("#currency_name").val(data.bank_accounts.currency_name);
                $(".local-currency").html("(" + data.bank_accounts.currency_name + ")");
                $("#transaction_type").val(data.bank_accounts.transaction_type);
                $('#currency option[value="' + data.bank_accounts.currency + '"]').prop("selected", true).trigger("change");
            }
        })
    });
})(window, document, jQuery);