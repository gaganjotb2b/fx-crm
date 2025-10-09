
(function ($) {
    $.fn.height_control = function (options) {
        console.log('ok');
        var settings = $.extend({
            parent: '.multisteps-form__form'
        });
        this.each(function () {
            if ($(this).css("visibility") == "visible") {
                let $height = $(this).outerHeight(true);
                console.log('visible=' + $height);
                $(settings.parent).each(function () {
                    if ($(this).css("visibility") == "visible") {
                        $(this).height($height);
                    }

                })
            }
        });
    };
}(jQuery));
