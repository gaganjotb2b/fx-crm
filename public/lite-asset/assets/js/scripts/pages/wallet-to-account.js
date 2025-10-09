
(function (window, document, $) {
    // change server-------------------
    $(document).on("change", "#platform", function () {
        let platform = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: 'post',
            dataType: 'json',
            url: '/user/transfer/get-meta-logo',
            data: { platform: platform },
            success: function (data) {
                console.log(data.option);
                $("#platform-logo").attr('src', data.platform_logo);
                $("#account").html(data.option);
            }
        });
    });
})(window, document, jQuery);