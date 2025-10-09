$.fn.push_data = function (options) {
    try {
        let settings = $.extend({
            url: '',
            elements: '',
            data: '',
            button: false,
        }, options);
        let $this = this;
        let new_class;
        $(document).on("click", settings.button, function () {
            $(settings.modal).modal('show');
            $.ajax({
                url: settings.url,
                data: settings.data,
                dataType: 'json',
                success: function (data) {
                    $.each(settings.elements, function (index, value) {
                        $(settings.modal).find("input[name='" + value.el + "']").val(data[value.el]);
                    });
                }
            });
        });
    } catch (error) {
        console.log(error);
    }

}