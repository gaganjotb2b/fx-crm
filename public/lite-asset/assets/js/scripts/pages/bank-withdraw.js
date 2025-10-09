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
            method: 'POST',
            dataType: 'json',
            url: '/user/withdraw/bank',
            data: { bank_name: bank_name,op:'banks' },
            success: function (data) {
                var value=(data.bank_options);
                $("#bank-account").html(value)
                $("#bank-account-name").val(data.bank_accounts.bank_account_name);
                $("#swift-code").val(data.bank_accounts.swift_code);
                $("#iban").val(data.bank_accounts.iban);
                $("#country").html(data.bank_accounts.country);
                $("#address").val(data.bank_accounts.address);
                $("#swift-code-label").text(data.bank_accounts.swift_code_label);
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
            method: 'POST',
            dataType: 'json',
            url: '/user/withdraw/bank',
            data: { bank_account: bank_account, op:'bank-accounts'},
            success: function (data) {
                $("#bank-account").html(data.bank_options);
                $("#bank-account-name").val(data.bank_accounts.bank_account_name);
                $("#swift-code").val(data.bank_accounts.swift_code);
                $("#iban").val(data.bank_accounts.iban);
                $("#country").html(data.bank_accounts.country);
                $("#address").val(data.bank_accounts.address);
            }
        })
    });
})(window, document, jQuery);