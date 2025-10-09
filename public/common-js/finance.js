// function for get balance,equity,free_margin
function balance_equity($this, account_id, search) {
    $($this).find('i').addClass('fa-spin');
    $.ajax({
        url: '/user/balance-equity/' + search + '/account/' + account_id,
        dataType: 'json',
        method: 'get',
        success: function (data) {
            $($this).find('.amount').text(data.amount);
            $($this).find('i').removeClass('fa-spin');
            // console.log(data)
            $('.modal-platform-logo').attr('src', data.platform_logo);
            $('.modal-leverage').text("1:" + data.leverage);
            $('.modal-account-number').text(data.account_number);
            $('.modal-account-balance').text(" $ " + data.balance);
            $('.modal-account-equity').text(" $ " + data.equity);
            $('.modal-free-margin').text(" $ " + data.free_margin);
        }
    });
}