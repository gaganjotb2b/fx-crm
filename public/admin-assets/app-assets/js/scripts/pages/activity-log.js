
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
                "url": "/admin/report/activity-log-dt",
                "data": function (d) {
                    return $.extend({}, d, {
                        "month": $("#month").val(),
                        "user_type": $("#user_type").val(),
                        "activity": $("#activity").val(),
                        "event": $("#event").val(),
                        "trading_account": $("#trading_account").val(),
                        "trader_info": $("#trader_info").val(),
                        "ib_info": $("#ib_info").val(),
                        "manager_info":$("#manager_info").val(),
                        "value_from_start_date": $("#value_from_start_date").val(),
                        "value_from_end_date": $("#value_from_end_date").val(),
                    });
                }
            },

            "columns": [
                { "data": "name","orderable":false },
                { "data": "user_type", "orderable":false},//<---as user type like IB/Trader
                { "data": "email", "orderable":false },
                { "data": "activity"},
                { "data": "event" },
                { "data": "date" },
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
            $('#month').prop('selectedIndex', 0).trigger("change");
            $('#user_type').prop('selectedIndex', 0).trigger("change");
            $('#activity').prop('selectedIndex', 0).trigger("change");
            $('#event').prop('selectedIndex', 0).trigger("change");
            $("#filter-form").trigger('reset');
            datatable.draw();
        });

    }

    // datatable description
    dt_description(null,'/admin/report/activity-log-dt-desctiption/',true);

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
    // block unblock-------------------------------------------------
    $(document).on("change click", ".switch-user-block", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        console.log(id);
        if ($(this).is(":checked") || ($(this).data('request_for') != "" && $(this).data('request_for') === 'block')) {
            warning_title = 'Are you sure? to Block this user!';
            warning_msg = 'If you want to Block this User please click OK, otherwise simply click cancel'
            request_for = 'block'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Unblock this user!';
            warning_msg = 'If you want to Unblock this User please click OK, otherwise simply click cancel'
            request_for = 'unblock'
        }
        let data = { id: id, request_for: request_for };
        let request_url = '/admin/client-management/trader-admin-block-trader';
        confirm_alert(warning_title, warning_msg, request_url, data, 'User ' + request_for);
    })
});