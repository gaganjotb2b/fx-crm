(function (window, document, $) { 
// get client email
// -------------------------------------------------------
// let search_string = $(this).val();
$.ajax({
    type:"get",
    url:'/admin/finance/credit-get-client/',
    dataType: 'json',
    success:function (data) {
        $("#trader").html(data);                
    }
});

// get trading account 
// ------------------------------------------------------------------------
$(document).on("change","#trader",function () {
    let client_id = $(this).val();
    $.ajax({
        type:"GET",
        url:'/admin/finance/credit-get-trading-account/'+client_id,
        dataType: 'json',
        success:function (data) {
            $("#trading_account").html(data.options);
            $("#user-name-top").html(data.users.name);
            $("#user-type").html(data.users.type);
            $("#name").html(data.users.name);
            $("#address").html(data.users.address);
            $("#address").html(data.users.address);
            $("#zip-code").html(data.users.zip_code);
            $("#city").html(data.users.city);
            $("#state").html(data.users.state);
        }
    });
});



})(window, document, jQuery);