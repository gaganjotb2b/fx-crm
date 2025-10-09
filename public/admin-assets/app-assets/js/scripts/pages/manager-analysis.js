
(function (window, document, $) {

    // Datepicker for advanced filter
    // ---------------------------------------------------------------------------
    var separator = ' - ',
        rangePickr = $('.flatpickr-range'),
        dateFormat = 'MM/DD/YYYY';
    var options = {
        autoUpdateInput: false,
        autoApply: true,
        locale: {
            format: dateFormat,
            separator: separator
        },
        opens: $('html').attr('data-textdirection') === 'rtl' ? 'left' : 'right'
    };

    //Range Picker
    // ---------------------------------------------------------------------------------------------
    if (rangePickr.length) {
        rangePickr.flatpickr({
            mode: 'range',
            dateFormat: 'm/d/Y',
            onClose: function (selectedDates, dateStr, instance) {
                var startDate = '',
                    endDate = new Date();
                if (selectedDates[0] != undefined) {
                    startDate = selectedDates[0].getFullYear() + '-' + (selectedDates[0].getMonth() +1)+ '-' + selectedDates[0].getDate();
                    $('.start_date').val(startDate);
                }
                if (selectedDates[1] != undefined) {
                    endDate = selectedDates[1].getFullYear() + '-' + (selectedDates[1].getMonth() +1)+ '-' + selectedDates[1].getDate();
                    $('.end_date').val(endDate);
                }
                $(rangePickr).trigger('change').trigger('keyup');
            }
        });
    }
    
})(window, document, jQuery);