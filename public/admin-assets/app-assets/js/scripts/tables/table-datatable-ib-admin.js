var dt;
// quil editor
var snowEditor;
var update_editor;
(function (window, document, $) {
    'use strict';

    var Font = Quill.import('formats/font');
    Font.whitelist = ['sofia', 'slabo', 'roboto', 'inconsolata', 'ubuntu'];
    Quill.register(Font, true);
    // Snow Editor for comment

    snowEditor = new Quill('#snow-container .editor', {
        bounds: '#snow-container .editor',
        modules: {
            formula: true,
            syntax: true,
            toolbar: '#snow-container .quill-toolbar'
        },
        theme: 'snow'
    });

    // comment update editor
    update_editor = new Quill('#snow-container-update .editor', {
        bounds: '#snow-container-update .editor',
        modules: {
            formula: true,
            syntax: true,
            toolbar: '#snow-container-update .quill-toolbar'
        },
        theme: 'snow'
    });
    var editors = [snowEditor, update_editor];
})(window, document, jQuery);

snowEditor.on('text-change', function (delta, oldDelta, source) {
    $('#text_quill').val(snowEditor.container.firstChild.innerHTML);
});

// for update comment
update_editor.on('text-change', function (delta, oldDelta, source) {
    $('#text_quill_update').val(update_editor.container.firstChild.innerHTML);
});


/**
 * DataTables Advanced
 */

'use strict';

// Advanced Search Functions Starts
// --------------------------------------------------------------------

// Filter column wise function
function filterColumn(i, val) {
    if (i == 5) {
        var startDate = $('.start_date').val(),
            endDate = $('.end_date').val();
        if (startDate !== '' && endDate !== '') {
            filterByDate(i, startDate, endDate); // We call our filter function
        }

        $('.dt-advanced-search').dataTable().fnDraw();
    } else {
        $('.dt-advanced-search').DataTable().column(i).search(val, false, true).draw();
    }
}

// Datepicker for advanced filter
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

//
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

// Advance filter function
// We pass the column location, the start date, and the end date
var filterByDate = function (column, startDate, endDate) {
    // Custom filter syntax requires pushing the new filter to the global filter array
    $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
        var rowDate = normalizeDate(aData[column]),
            start = normalizeDate(startDate),
            end = normalizeDate(endDate);

        // If our date from the row is between the start and end
        if (start <= rowDate && rowDate <= end) {
            return true;
        } else if (rowDate >= start && end === '' && start !== '') {
            return true;
        } else if (rowDate <= end && start === '' && end !== '') {
            return true;
        } else {
            return false;
        }
    });
};

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
var normalizeDate = function (dateString) {
    var date = new Date(dateString);
    var normalized =
        date.getFullYear() + '' + ('0' + (date.getMonth() + 1)).slice(-2) + '' + ('0' + date.getDate()).slice(-2);
    return normalized;
};
// Advanced Search Functions Ends

$(function () {
    var isRtl = $('html').attr('data-textdirection') === 'rtl';

    var dt_ajax_table = $('.datatables-ajax'),


        dt_filter_table = $('.dt-column-search'),
        dt_adv_filter_table = $('.dt-advanced-search'),
        dt_responsive_table = $('.dt-responsive'),
        assetPath = '../../../app-assets/';

    var dt_ajax_inner_table = $('.datatable-inner'),
        dt_adv_filter_inner = $('.dt-advanced-search'),
        dt_responsive_inner = $('.dt-responsive');

    if ($('body').attr('data-framework') === 'laravel') {
        assetPath = $('body').attr('data-asset-path');
    }


    // Ajax Sourced Server-side
    // --------------------------------------------------------------------
    // datatable for ib admin
    $(document).ready(function () {
        dt = $('.datatables-ajax').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": true,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            buttons: [{
                extend: 'csv',
                text: 'csv',
                className: 'btn btn-success btn-sm',
                action: serverSideButtonAction
            },
            {
                extend: 'excel',
                text: 'excel',
                className: 'btn btn-warning btn-sm',
                action: serverSideButtonAction
            }
            ],
            "ajax": {
                "url": "/admin/ib-management/ib-admin-report-process",
                "data": function (d) {
                    return $.extend({}, d, $("#filterForm").serializeObject());
                }
            },

            "columns": [{
                "data": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "phone"
            },
            {
                "data": "country"
            },
            {
                "data": "group"
            },
            {
                "data": "joined"
            },
            {
                "data": "status"
            },
            {
                "data": "action"
            },
            ],

            "drawCallback": function (settings) {
                $("#filterBtn").html("FILTER");
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            },
            "order": [[5, 'desc']],
        });
        $('#filterBtn').click(function (e) {
            dt.draw();
        });
    });


    // filter form reset
    $(document).ready(function () {
        $("#resetBtn").click(function () {
            $("#filterForm")[0].reset();
            $('#status').prop('selectedIndex', 0).trigger("change");
            $('#verification_status').prop('selectedIndex', 0).trigger("change");
            $('#active_status').prop('selectedIndex', 0).trigger("change");
            $('#group').prop('selectedIndex', 0).trigger("change");

            dt.draw();
        });
    });

    // ib admin status action start
    $(document).on('click', '#ib-admin-status-button', function () {
        let __this = $(this);
        let id = $(this).data('id');
        let value = $(this).data('value');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/ib-management/change-status',
            method: 'POST',
            dataType: 'json',
            data: {
                id: id,
                value: value
            },
            success: function (data) {
                if (data.status === true) {
                    Swal.fire({
                        icon: 'success',
                        title: "Activity Status",
                        html: data.message,
                    });
                    dt.draw();
                } else {
                    Swal.fire({
                        icon: 'danger',
                        title: "Activity Status",
                        html: data.message,
                    });
                }
            }
        })
    });
    // ib admin status action end


    // datatable export function
    $(document).on("change", "#fx-export", function () {
        if ($(this).val() === 'csv') {
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            $(".buttons-excel").trigger('click');
        }
    });
    //   ajax sourced server side inner table
    function serverSideButtonAction(e, dt, node, config) {

        var me = this;
        var button = config.text.toLowerCase();
        if (typeof $.fn.dataTable.ext.buttons[button] === "function") {
            button = $.fn.dataTable.ext.buttons[button]();
        }
        var len = dt.page.len();
        var start = dt.page();
        dt.page(0);

        // Assim que ela acabar de desenhar todas as linhas eu executo a função do botão.
        // ssb de serversidebutton
        dt.context[0].aoDrawCallback.push({
            "sName": "ssb",
            "fn": function () {
                $.fn.dataTable.ext.buttons[button].action.call(me, e, dt, node, config);
                dt.context[0].aoDrawCallback = dt.context[0].aoDrawCallback.filter(function (e) {
                    return e.sName !== "ssb"
                });
            }
        });
        dt.page.len(999999999).draw();
        setTimeout(function () {
            dt.page(start);
            dt.page.len(len).draw();
        }, 500);
    }

    // Filter form control to default size for all tables
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm').removeClass('form-control-sm');

    //


    //    datatable descriptions
    $(document).on("click", ".dt-description", function (params) {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        $.ajax({
            type: "GET",
            url: '/admin/ib-management/ib-admin-report-description/' + ib_id,
            dataType: 'json',
            success: function (data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);

                        $(__this).find('.w').html(feather.icons['minus'].toSvg());

                        // Inner datatable
                        if ($(__this).closest("tr").next(".description").find('.datatable-inner').length) {
                            $(__this).closest("tr").next(".description").find('.datatable-inner').DataTable().clear().destroy();
                            var dt_inner = $(__this).closest('tr').next('.description').find('.trader-list').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "searching": true,
                                "lengthChange": false,
                                "dom": 'Bfrtip',
                                "ajax": {
                                    "url": "/admin/ib-management/ib-admin-report-description-inner-trader-list/" + ib_id
                                },
                                "columns": [{
                                    "data": "name"
                                },
                                {
                                    "data": "email"
                                },
                                {
                                    "data": "deposit"
                                },
                                {
                                    "data": "withdraw"
                                },
                                {
                                    "data": "balance"
                                },
                                {
                                    "data": "action"
                                }
                                ],
                                "order": [
                                    [1, 'desc']
                                ],
                                "drawCallback": function (settings) {
                                    var rows = this.fnGetData();
                                    if (rows.length !== 0) {
                                        feather.replace();
                                    }
                                },

                            });
                        }
                    }
                }
            }
        });
    });

    // trader list
    $(document).on("click", ".trader-list-tab", function () {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        if ($(__this).closest('tr').find('.datatable-inner').length) {
            $(__this).closest('tr').find('.datatable-inner').DataTable().clear().destroy();
            var dt_inner = $(__this).closest('tr').find('.trader-list').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "lengthChange": false,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-report-description-inner-trader-list/" + ib_id
                },
                "columns": [{
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "deposit"
                },
                {
                    "data": "withdraw"
                },
                {
                    "data": "balance"
                },

                {
                    "data": "action"
                }
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    // trading account
    $(document).on("click", ".trading-account-tab", function () {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        if ($(__this).closest('tr').find('.datatable-inner').length) {
            $(__this).closest('tr').find('.datatable-inner').DataTable().clear().destroy();
            var dt_ajax_table = $(__this).closest('tr').find('.trader-account').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "dom": 'Bfrtip',
                "ajax": { "url": "/admin/ib-management/ib-admin-report-description-inner-trading-account/" + ib_id },
                "columns": [
                    { "data": "account_number" },
                    { "data": "platform" },
                    { "data": "leverage" },
                    { "data": "group" },
                    { "data": "raw_group" },
                    { "data": "created_at" },
                ],
                "order": [[1, 'desc']],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    // sub ib show here
    $(document).on("click", ".sub-ib-tab", function () {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        if ($(__this).closest('tr').find('.datatable-inner').length) {
            $(__this).closest('tr').find('.datatable-inner').DataTable().clear().destroy();
            var dt_ajax_table = $(__this).closest('tr').find('.sub-ib').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "lengthChange": false,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-report-description-inner-sub-ib/" + ib_id
                },


                "columns": [{
                    "data": "name"
                },
                {
                    "data": "email"
                },

                {
                    "data": "withdraw"
                },
                {
                    "data": "balance"
                },
                {
                    "data": "action"
                },
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    // trading deposit
    $(document).on("click", ".trading-deposit-tab", function () {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        if ($(__this).closest('tr').find('.datatable-inner').length) {
            $(__this).closest('tr').find('.datatable-inner').DataTable().clear().destroy();
            var dt_ajax_table = $(__this).closest('tr').find('.trading-deposit').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-report-description-inner-trading-deposit/" + ib_id
                },
                "columns": [{
                    "data": "c_name"
                },
                {
                    "data": "c_email"
                },
                {
                    "data": "ib_email"
                },
                {
                    "data": "t_a_amount"
                },
                {
                    "data": "t_p_amount"
                },
                {
                    "data": "t_amount"
                },
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    // trading deposit
    $(document).on("click", ".trading-withdraw-tab", function () {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        if ($(__this).closest('tr').find('.datatable-inner').length) {
            $(__this).closest('tr').find('.datatable-inner').DataTable().clear().destroy();
            var dt_ajax_table = $(__this).closest('tr').find('.trading-withdraw').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-report-description-inner-trading-withdraw/" + ib_id
                },
                "columns": [{
                    "data": "c_name"
                },
                {
                    "data": "c_email"
                },
                {
                    "data": "ib_email"
                },
                {
                    "data": "t_a_amount"
                },
                {
                    "data": "t_p_amount"
                },
                {
                    "data": "t_amount"
                },
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    // trading withdraw
    $(document).on("click", ".self-withdraw-tab", function () {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        if ($(__this).closest('tr').find('.datatable-inner').length) {
            $(__this).closest('tr').find('.datatable-inner').DataTable().clear().destroy();
            var dt_ajax_table = $(__this).closest('tr').find('.self-withdraw').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-report-description-inner-self-withdraw/" + ib_id
                },
                "columns": [{
                    "data": "txnid"
                },
                {
                    "data": "amount"
                },
                {
                    "data": "method"
                },
                {
                    "data": "status"
                },
                {
                    "data": "date"
                },
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    // ib commission
    $(document).on("click", ".ib-commission-tab", function () {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        if ($(__this).closest('tr').find('.datatable-inner').length) {
            $(__this).closest('tr').find('.datatable-inner').DataTable().clear().destroy();
            var dt_ajax_table = $(__this).closest('tr').find('.ib-commission').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-report-description-inner-ib-commission/" + ib_id
                },
                "columns": [{
                    "data": "trader"
                },
                {
                    "data": "ticket"
                },
                {
                    "data": "login"
                },
                {
                    "data": "symbol"
                },
                {
                    "data": "amount"
                },
                {
                    "data": "date"
                }
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    //  Kyc report
    $(document).on("click", ".kyc-tab-fill", function () {
        let ib_id = $(this).data('ib_id');
        if ($('.kyc').length) {
            $('.kyc').DataTable().clear().destroy();
            var cd = (new Date()).toISOString().split('T')[0];
            var dt_kyc = $(this).closest('tr').find('.kyc').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "buttons": true,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-dt-kyc-fetch-data/" + ib_id
                },
                "columns": [{
                    "data": "date"
                },
                {
                    "data": "document_type"
                },
                {
                    "data": "status"
                },
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });
    var dt_comment = null;
    //  Comments report
    $(document).on("click", ".comment-tab-fill", function () {
        let ib_id = $(this).data('ib_id');
        if ($(this).closest('tr').find('.comment').length) {
            $(this).closest('tr').find('.comment').DataTable().clear().destroy();
            var cd = (new Date()).toISOString().split('T')[0];
            dt_comment = $(this).closest('tr').find('.comment').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "buttons": true,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/ib-management/ib-admin-dt-comment-fetch-data/" + ib_id
                },
                "columns": [{
                    "data": "date"
                },
                {
                    "data": "comment"
                },
                {
                    "data": "actions"
                },
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function (settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });
    // add modal title
    $(document).on("click", ".btn-add-comment", function () {
        $('.comment-to').html($(this).data('name'));
        $('#ib_id').val($(this).data('id'));
    })

    // add new comment
    $(document).on("submit", "#form-add-comment", function (event) {
        let form_data = new FormData(this);
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/admin/ib-management/ib-admin-dt-comment-post-data',
            dataType: 'json',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == false) {
                    toastr['error']('Comment is too short!', 'Comments', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
                if (data.status == true) {
                    toastr['success'](data.message, 'Comments', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    dt_comment.draw();
                }
            }
        });
    });
    $(document).on("click", "#save-comment-btn", function (event) {
        $("#form-add-comment").trigger("submit")
    });
    // update exist comment
    // get quil data into form
    $(document).on("click", ".btn-update-comment", function () {
        $('.comment-to').html($(this).data('name'));
        $('#ib_id-update').val($(this).data('id'));
        $('#comment-id').val($(this).data('commentid'));
        $(".ql-editor").html($(this).data('comment'));
    });

    // submit ajax request for comment update
    $(document).on("submit", "#form-update-comment", function (event) {
        let form_data = new FormData(this);
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/admin/ib-management/ib-admin-update-comment',
            dataType: 'json',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == false) {
                    toastr['error']('Failed To Update!', 'Comments', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
                if (data.status == true) {
                    toastr['success'](data.message, 'Comments', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    dt_comment.draw();
                }
            }
        });
    });
    $(document).on("click", "#update-comment-btn", function (event) {
        $("#form-update-comment").trigger("submit")
    })

    // delete comment
    $(document).on("click", ".btn-delete-comment", function () {
        let id = $(this).data('id');
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure? to delete this!',
            html: 'If you want to permanently delete this comment please click OK, otherwise simply click cancel',

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-admin-delete-comment',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Comment Deleted!',
                                html: 'The comment permanently deleted.',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((willDelete) => {
                                dt_comment.draw();
                            })
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation filed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } //ending if condition 

        }); //ending swite alert
    });

    // Action or security setttings
    // operation block/unblock
    $(document).on("change", ".block-unblock-swtich", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to block this user!';
            warning_msg = 'If you want to Block this User please click OK, otherwise simply click cancel'
            request_for = 'block'
        } else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to unblock this user!';
            warning_msg = 'If you want to Unblock this User please click OK, otherwise simply click cancel'
            request_for = 'unblock'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/admin-block-ib',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation filed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }

        }); //ending swite alert
    })

    // enable / disable google two step authentication
    $(document).on("change", "#two-step-swtich", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable google two step!';
            warning_msg = 'If you want to enable google two step. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        } else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable google two step!';
            warning_msg = 'If you want to Disable Google 2 step. please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-admin-google-two-step-auth',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation filed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }

        }); //ending swite alert
    })

    // Enable / Disable Email authentication
    $(document).on("change", "#email-a-swtich", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable email authentication!';
            warning_msg = 'If you want to enable email authentication. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        } else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable email authentication!';
            warning_msg = 'If you want to disable email authentication. please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-admin-email-auth',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation filed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }

        }); //ending swite alert
    })


    // Enable / Disable email verification
    $(document).on("change", "#email-v-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable email verification!';
            warning_msg = 'If you want to enable email verification. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        } else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable email verification!';
            warning_msg = 'If you want to disable email verification. please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-admin-email-verification',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation filed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                });

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }

        }); //ending swite alert
    });
    // Enable / Disable deposit operation
    $(document).on("change", "#deposit-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable deposit operation!';
            warning_msg = 'If you want to enable deposit operation. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        } else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable deposit operation!';
            warning_msg = 'If you want to disable deposit operation. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-admin-deposit-operation',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation filed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        }); //ending swite alert
    });

    // Enable / Disable withdraw operation
    $(document).on("change", "#withdraw-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable withdraw operation!';
            warning_msg = 'If you want to Enable Withdraw Operation. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        } else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable withdraw operation!';
            warning_msg = 'If you want to disable withdraw operation. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-admin-withdraw-operation',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation filed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        }); //ending swite alert
    });
    // Enable / Disable Internal transfer
    $(document).on("change", "#atw-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable tranding account to wallet transfer!';
            warning_msg = 'If you want to enable tranding account to wallet transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        } else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable tranding account to wallet transfer!';
            warning_msg = 'If you want to disable tranding account to wallet transfer. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-admin-internal-transfer',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Account To Wallet Operation!',
                                html: 'Failed To Update!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                }
                else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        });//ending swite alert
    });
    // Enable / Disable Wallet To Account transfer
    $(document).on("change", "#wta-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable wallet to trading account transfer!';
            warning_msg = 'If you want to enable wallet to trading account transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable wallet to trading account transfer!';
            warning_msg = 'If you want to disable wallet to trading account transfer. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/wta-transfer',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Wallet To Account Operation!',
                                html: 'Failed To Update!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                }
                else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        });//ending swite alert
    });

    // Enable / Disable IB To IB transfer
    $(document).on("change", ".ib_to_ib-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable IB To IB transfer!';
            warning_msg = 'If you want to enable IB To IB transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable IB To IB transfer!';
            warning_msg = 'If you want to disable IB To IB transfer. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-to-ib-transfer-op',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'IB To IB Transfer!',
                                html: 'Failed To Update!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                }
                else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        });//ending swite alert
    });

    // Enable / Disable IB To Trader transfer
    $(document).on("change", ".ib_to_trader-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to enable IB To Trader transfer!';
            warning_msg = 'If you want to enable IB To Trader transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to disable IB To Trader transfer!';
            warning_msg = 'If you want to disable IB To Trader transfer. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },

        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/ib-management/ib-to-trader-transfer',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'IB To Trader Operation!',
                                html: 'Failed To Update!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        }); //ending swite alert
    });
    /*************************************************************** */
    // Enable / Disable withdraw operation
    $(document).on("change", ".withdraw-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Withdraw Operation!';
            warning_msg = 'If you want to Enable Withdraw Operation. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Withdraw Operation!';
            warning_msg = 'If you want to Disable Withdraw Operation. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },
            closeOnCancel: false,
            closeOnConfirm: false,
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/client-management/trader-admin-withdraw-operation',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Withdraw Operation');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Withdraw Operation!',
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            }
            else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                }
                else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        });//ending swite alert
    })
    /********************************************************** */
    //   security settings change kyc status
    $(document).on("change", ".kyc_verify-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to KYC Status Verified!';
            warning_msg = 'If you want to KYC status verified. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to KYC Status Unverified!';
            warning_msg = 'If you want to  KYC status unverified. Please click OK, otherwise simply click cancel'
            request_for = 'disable'
        }
        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },
            closeOnCancel: false,
            closeOnConfirm: false,
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/client-management/trader-admin-change-kyc',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Change Kyc Status');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Change Kyc Status!',
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            }
            else {
                if ($(this).is(":checked")) {
                    $(this).prop('checked', false);
                }
                else if ($(this).is(":not(:checked)")) {
                    $(this).prop('checked', true);
                }

            }
        });//ending swite alert
    });


    // set ib category
    $(document).on("click", "#save-category", function () {
        let id = $(this).data('id');
        let category = $(this).closest('.row').find('select').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/ib-management/ib-admin-set-category',
            method: 'POST',
            dataType: 'json',
            data: {
                id: id,
                category: category
            },
            success: function (data) {
                if (data.status === true) {
                    Swal.fire({
                        icon: 'success',
                        title: data.success_title,
                        html: data.message,
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then((willDelete) => {
                        // location.reload();
                    })
                } else {
                    Swal.fire({
                        icon: 'danger',
                        title: 'Update operation failed!',
                        html: 'The comment delete operation failed, please try again later.',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            }
        })
    });

    // change password
    $(document).on("click", ".change-password-btn", function () {
        $('.password-change-for').html($(this).data('name'));
        $('#ib-id').val($(this).data('ib_id'));
    });

    // change transaction password
    $(document).on("click", ".change-transaction-password-btn", function () {
        $('.transaction-password-change-for').html($(this).data('name'));
        $('.ib-id').val($(this).data('ib_id'));
    });


    $(document).on("submit", ".change-transaction-password-form", function (event) {
        let form_data = $(this).serializeArray();
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/ib-management/ib-admin-change-transaction-password',
            method: 'POST',
            dataType: 'json',
            data: form_data,
            success: function (data) {
                if (data.status === true) {
                    // success toaster
                    toastr['success'](data.message, 'Change transaction password', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                        rtl: isRtl,
                    });
                    $('.send-mail-pass').modal('toggle');
                    $('.change-transaction-password-modal').modal('toggle');
                    // sending mail
                    $.ajax({
                        url: '/admin/ib-management/ib-admin-change-transaction-password-mail',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            ib_id: data.ib_id,
                            password: data.password
                        },
                        success: function (inner_data) {
                            if (inner_data.status === true) {
                                toastr['success'](inner_data.message, 'Transaction Password', {
                                    showMethod: 'slideDown',
                                    hideMethod: 'slideUp',
                                    closeButton: true,
                                    tapToDismiss: false,
                                    progressBar: true,
                                    timeOut: 2000,
                                    rtl: isRtl
                                });
                                $('.send-mail-pass').modal('toggle');
                            }
                        }
                    });
                } else {
                    console.log(data.errors);
                    let $errors = '';
                    if (data.errors.hasOwnProperty('password')) {
                        $errors += "  " + data.errors.password[0] + '<br>';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Update operation failed!',
                        html: $errors,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            }
        });
    });

    //Reset passsword
    $(document).on("click", ".reset-password-btn-2", function () {
        let id = $(this).data('id');
        let $request_url = '/admin/ib-management/ib-admin-reset-password';
        let data = { ib_id: id, };
        let $url = '/admin/client-management/trader-admin-reset-password-mail/' + id;
        confirm_alert('Reset IB Password', 'Are you confirm reset IB password', $request_url, data, 'Reset password', null, true, $url);
    });
    //Reset transaction pin
    $(document).on("click", ".reset-transaction-password-btn", function () {
        let id = $(this).data('id');
        let $request_url = '/admin/client-management/trader-admin-reset-transaction-pin';
        let data = { trader_id: id };
        let $url = '/admin/client-management/trader-admin-reset-transaction-pin-mail/' + id;
        confirm_alert('Reset IB Transaction Pin', 'Are you confirm reset transaction pin', $request_url, data, 'Transaction pin reset', null, true, $url);
    });
});




