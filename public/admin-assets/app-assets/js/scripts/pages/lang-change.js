(function (window, document, $) {
    $(document).on('click', ".lang-change", function () {
        let lang = $(this).data('language');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/change-language',
            method: 'post',
            dataType: 'json',
            data:{lang:lang},
            success: function (data) {
                if (data.status===true) {
                    location.reload();
                }
            }
        });
    });
})(window, document, jQuery);