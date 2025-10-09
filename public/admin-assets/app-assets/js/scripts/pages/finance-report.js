
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
                startDate =
                    selectedDates[0].getMonth() + 1 + '/' + selectedDates[0].getDate() + '/' + selectedDates[0].getFullYear();
                $('.start_date').val(startDate);
            }
            if (selectedDates[1] != undefined) {
                endDate =
                    selectedDates[1].getMonth() + 1 + '/' + selectedDates[1].getDate() + '/' + selectedDates[1].getFullYear();
                $('.end_date').val(endDate);
            }
            $(rangePickr).trigger('change').trigger('keyup');
        }
    });
}

// Advanced Search Functions Ends

$(function () {
    var isRtl = $('html').attr('data-textdirection') === 'rtl';

    var dt_ajax_table = $('.datatables-ajax'),
        assetPath = '../../../app-assets/';

    if ($('body').attr('data-framework') === 'laravel') {
        assetPath = $('body').attr('data-asset-path');
    }

    // Ajax Sourced Server-side datatable
    // --------------------------------------------------------------------
    if (dt_ajax_table.length) {

        feather.replace();
        var datatable = dt_ajax_table.DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4,5]
                    },
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
            ],
            "ajax": {
                "url": "/admin/finance/finance-report-dt",
                "data": function (d) {
                    return $.extend({}, d, {
                        "month": $("#month").val(),
                        "start_date": $(".start_date").val(),
                        "end_date": $(".end_date").val(),
                        "transaction_for": $("#transaction_for").val(),
                        "transaction_type": $("#transaction_type").val(),
                        "email": $("#email").val(),
                        "manager_info": $("#manager").val()
                    });
                }
            },

            "columns": [
                { "data": "name","orderable":true },
                { "data": "source", "orderable":false},//<---as user type like IB/Trader
                { "data": "transaction_type" },
                { "data": "amount" },
                { "data": "status" },
                { "data": "date" },
                { "data": "action", "orderable":false },
            ],
            "columnDefs": [ {
                "targets": 1,
                "orderable": false
                } ],
            "order": [[5, 'desc']],
            "drawCallback": function (settings) {
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            }
        });
        // Filter operation
        $("#btn-filter").on("click", function (e) {
            console.log($(".start_date").val());
            datatable.draw();
        });
        // reset operation
        $("#btn-reset").on("click", function (e) {
            $(".start_date").val('');
            $(".end_date").val('');
            $("#filter-form").trigger('reset');
            $(".select2").val("").trigger('change');
            datatable.draw();
        });

    }

    // datatable export function
    $(document).on("change", "#fx-export", function () {
        if ($(this).val() === 'csv') {
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            $(".buttons-excel").trigger('click');
        }

    });


    // Filter form control to default size for all tables
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm').removeClass('form-control-sm');

});