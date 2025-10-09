(function (window, document, $) {
    $("#client-type").on("change", function () {
        let type = $(this).val();
        $.ajax({
            type: "GET",
            url: '/admin/kyc-management/get-client/' + type,
            dataType: 'json',
            success: function (data) {
                $("#client").html(data.users);
            }
        });//END: get client
    });
    // END: Client Type
    // ---------------------------------------------------------------------------
    // START: get finance details
    $(document).on('click input', '.fill-input, #myInput', function () {
        let client_id = $(this).val();
        // console.log(client_id);
        if (client_id == "") {
            client_id = $(this).data('value');
        }
        $.ajax({
            type: "GET",
            url: '/admin/kyc-management/get-client-details/' + client_id,
            dataType: 'json',
            success: function (data) {
                $("#name").html(data.name);
                $("#address").html(data.address);
                $("#zip-code").html(data.zip_code);
                $("#city").html(data.city);
                $("#state").html(data.state);
                $("#user-type").html(data.type);
                $("#user-name-top").html(data.name);
            }
        });//END: get client
    })// END: Get finance detailed

    $("#client-type, #status").val('');

    // kyc files upload by drop zone
    // -----------------------------------------------------------------------------------------------

    // get decline reason field
    // ------------------------------------------------------------------------------
    $("#status").on("change", function () {
        let status = $(this).val();
        if (status == 2) {
            $("#decline-reason-row").slideDown();
        }
        else {
            $("#decline-reason-row").slideUp();
        }
    });

})(window, document, jQuery);