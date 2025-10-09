/**
 * DataTables Advanced
 */

'use strict';

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

    // Ajax Sourced Server-side
    // --------------------------------------------------------------------
    // var datatable = dt_ajax_table.fetch_data({
    //     url: "/admin/client-management/trader-admin-fetch-data",
    //     columns: [
    //         { "data": "name" },
    //         { "data": "email" },
    //         { "data": "phone" },
    //         { "data": "joined" },
    //         { "data": "status" },
    //         { "data": "actions" },
    //     ],
    //     icon_feather: true,
    //     csv_export: true,
    //     description: true,
    // });


    var datatable = dt_ajax_table.fetch_data({
        url: '/manager/lead/report/data',
        csv_export: true,
        description: true,
        export_col:[0, 1, 2, 3, 4],
        total_sum: false,
        length_change: true,
        icon_feather: true,
        customorder: 3,
        columns: [
            { "data": "name" },
            { "data": "email" },
            { "data": "phone" },
            { "data": "joined" },
            { "data": "status" },
            { "data": "actions" },
        ],
    })

    //    datatable descriptions
    // --------------------------------------------------------------------------------------------------------
    var descrioption_dt = $("body").fetch_description(
        {
            description_dt: true,
            icon_feather: true,
            inner_col: [
                { "data": "account_number" },
                { "data": "platform" },
                { "data": "leverage" },
                { "data": "group" },
                { "data": "raw_group" },
                { "data": "created_at" },
                // { "data": "actions" },
            ]
        }
    );
    // data table deposit
    $(document).on("click", ".deposit-tab-fill", function () {
        let trader_id = $(this).data('id');
        var dt_deposit = $(this).closest('tr').find('.deposit');
        dt_deposit = dt_deposit.fetch_data({
            url: "/admin/client-management/trader-admin-dt-deposit-fetch-data/" + trader_id,
            columns: [
                { "data": "date" },
                { "data": "Ammount" },
                { "data": "Method" },
                { "data": "Status" },
                // { "data": "actions" },
            ],
            icon_feather: true,
        });
    })
    //  withdraw report
    $(document).on("click", ".withdraw-tab-fill", function () {
        let trader_id = $(this).data('id');
        var dt_withdraw = $(this).closest('tr').find('.withdraw');
        dt_withdraw = dt_withdraw.fetch_data({
            url: "/admin/client-management/trader-admin-dt-withdraw-fetch-data/" + trader_id,
            columns: [
                { "data": "date" },
                { "data": "Ammount" },
                { "data": "Method" },
                { "data": "Status" },
                // { "data": "actions" },
            ],
            icon_feather: true,
        });
    })
    //  bonus report
    $(document).on("click", ".bonus-tab-fill", function () {
        let trader_id = $(this).data('id');
        var dt_bonus = $(this).closest('tr').find('.bonus');
        dt_bonus = dt_bonus.fetch_data({
            url: "/admin/client-management/trader-admin-dt-bonus-fetch-data/" + trader_id,
            columns: [
                { "data": "date" },
                { "data": "amount" },
                { "data": "type" },
                { "data": "account_number" },
                { "data": "credit_expire" },
                { "data": "created_by" },
            ],
            icon_feather: true,
        });
    });
    //  Kyc report
    $(document).on("click", ".kyc-tab-fill", function () {
        let trader_id = $(this).data('id');
        var dt_kyc = $(this).closest('tr').find('.kyc');
        dt_kyc = dt_kyc.fetch_data({
            url: "/admin/client-management/trader-admin-dt-kyc-fetch-data/" + trader_id,
            columns: [
                { "data": "date" },
                { "data": "document_type" },
                { "data": "status" },
            ],
            icon_feather: true,
        });
    });
    //  Comments report
    $(document).on("click", ".comment-tab-fill", function () {
        let trader_id = $(this).data('id');
        var dt_comment = $(this).closest('tr').find('.comment');
        dt_comment = dt_comment.fetch_data({
            url: "/admin/client-management/trader-admin-dt-comment-fetch-data/" + trader_id,
            columns: [
                { "data": "date" },
                { "data": "comment" },
                { "data": "actions" },
            ],
            icon_feather: true,
        });
    });
    // internal transfer report
    // datatable
    $(document).on("click", ".btn-internal-trans", function () {
        let trader_id = $(this).data('id');
        console.log(trader_id);
        $(this).closest('tr').find('.internal-fill').trigger('click');
        var dt_internal_trans = $(this).closest('tr').find('.tbl-internal');
        dt_internal_trans = dt_internal_trans.fetch_data({
            url: "/admin/client-management/trader-admin-dt-internal-trans/" + trader_id,
            columns: [
                { "data": "account_number" },
                { "data": "platform" },
                { "data": "method" },
                { "data": "status" },
                { "data": "date" },
                { "data": "amount" },
            ],
            icon_feather: true,
            total_sum: true,
        });
    });
    // external transfer report
    // datatable
    $(document).on("click", ".btn-external-trans", function () {
        let trader_id = $(this).data('id');
        // console.log(trader_id);
        $(this).closest('tr').find('.external-fill').trigger('click');
        var dt_external_trans = $(this).closest('tr').find('.tbl-external');
        dt_external_trans = dt_external_trans.fetch_data({
            url: "/admin/client-management/trader-admin-dt-external-trans/" + trader_id,
            columns: [
                { "data": "receiver_email" },
                { "data": "type" },
                { "data": "date" },
                { "data": "status" },
                { "data": "charge" },
                { "data": "amount" },
            ],
            icon_feather: true,
            total_sum: true,
        });
    });
    // tab nav controll
    $(document).on('click', '.trd-nav-link', function () {
        let $this = $(this);
        $('.trd-nav-link').each(function (index, object) {
            $(object).not('.dd-child').removeClass('active');
            $(object).removeClass('bg-light-primary');
        }).promise().done(function () {
            $($this).addClass('active');
            $($this).addClass('bg-light-primary');
        });
    })
    // tab nav dropdown item control
    $(document).on('click', '.dd-child', function () {
        $(this).closest('li').find('.more-actions').addClass('active');
    })
    // update exist comment
    // get quil data into form
    $(document).on("click", ".btn-update-comment", function () {
        $('.comment-to').html($(this).data('name'));
        $('#trader-id-update').val($(this).data('id'));
        $('#comment-id').val($(this).data('commentid'));
        $(".ql-editor").html($(this).data('comment'));
    });

    // delete comment
    var comment_table_obj;
    $(document).on("click", ".btn-delete-comment", function () {
        let id = $(this).data('id');
        comment_table_obj = $(this).closest('.description').find('.comment');
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure? to delete this!',
            html: 'If you want to permanently delete this comment please click OK, otherwise simply click cancel',

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
                    url: '/admin/client-management/trader-admin-delete-comment',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id },
                    success: function (data) {
                        if (data.status === true) {
                            toastr['success'](data.message, 'Delete Comment', {
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            comment_table_obj.DataTable().draw();
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation failed!',
                                html: 'The comment delete operation failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            }//ending if condition 

        });//ending swite alert
    });

    // Action or security setttings
    // operation block/unblock

    $(document).on("change click", ".block-unblock-swtich, .btn-block-unblock", function () {
        console.log('ok');
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if (id == "") {
            id = $(this).data("id");
        }
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
        confirm_alert(warning_title, warning_msg, request_url, data, 'Trader ' + request_for, datatable, false, null, $(this));

    })
    // enable / disable google 2 step authentication
    $(document).on("change", ".2-step-swtich", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Google 2 setp!';
            warning_msg = 'If you want to Enable Google 2 step. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Google 2 setp!';
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
                    url: '/admin/client-management/trader-admin-google-two-step',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Google 2 step');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation failed!',
                                html: 'The comment delete operation failed, please try again later.',
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

    // Enable / Disable Email authentication
    $(document).on("change", ".email-a-swtich", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Email Authentication!';
            warning_msg = 'If you want to Enable Email Authentication. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Email Authentication!';
            warning_msg = 'If you want to Disable Email Authentication. please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-email-auth',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Email Authentication');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Email Authentication!',
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


    // Enable / Disable email verification
    $(document).on("change", ".email-v-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Email Verification!';
            warning_msg = 'If you want to Enable Email Verification. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Email Verification!';
            warning_msg = 'If you want to Disable Email Verification. please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-email-verification',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Email Verification');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deleted operation failed!',
                                html: 'The comment delete operation failed, please try again later.',
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
    // Enable / Disable deposit operation
    $(document).on("change", ".deposit-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Deposit Operation!';
            warning_msg = 'If you want to Enable Deposit Operation. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Deposit Operation!';
            warning_msg = 'If you want to Disable Deposit Operation. Please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-deposit-operation',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Deposit Operation');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Deposit operation!',
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
    // Enable / Disable Internal transfer
    $(document).on("change", ".atw-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Tranding account to wallet transfer!';
            warning_msg = 'If you want to Enable Tranding account to wallet transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Tranding account to wallet transfer!';
            warning_msg = 'If you want to Disable Tranding account to wallet transfer. Please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-internal-transfer',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Internal Transfer');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Internal Transfer!',
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
    // ******************************************************************
    //   finance wallet to account transfer
    $(document).on("change", ".wta-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable wallet to account transfer!';
            warning_msg = 'If you want to Enable wallet to account transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable wallet to account transfer!';
            warning_msg = 'If you want to Disable wallet to account transfer. Please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-internal-transfer-wta',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Wallet to account transaction');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Wallet to account transaction!',
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
    // ******************************************************************
    //   finance trader to trader transfer
    $(document).on("change", ".trader_to_trader-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Trader to trader transfer!';
            warning_msg = 'If you want to Enable Trader to trader transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Trader to trader transfer!';
            warning_msg = 'If you want to Disable Trader to trader transfer. Please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-trader-to-trader',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Trader to trader transaction');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Trader to trader transaction!',
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
    // trader to ib
    $(document).on("change", ".trader_to_ib-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable Trader to IB transfer!';
            warning_msg = 'If you want to Enable Trader to IB transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable Trader to IB transfer!';
            warning_msg = 'If you want to Disable Trader to IB transfer. Please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-trader-to-ib',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'Trader to IB transaction');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Trader to IB transaction!',
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
    // ******************************************************************
    //   finance trader to trader transfer
    $(document).on("change", ".ib_to_trader-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = 'Are you sure? to Enable IB to Trader transfer!';
            warning_msg = 'If you want to Enable IB to Trader transfer. please click OK, otherwise simply click cancel'
            request_for = 'enable'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Disable IB to Trader transfer!';
            warning_msg = 'If you want to Disable IB to Trader transfer. Please click OK, otherwise simply click cancel'
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
                    url: '/admin/client-management/trader-admin-internal-transfer-wta',
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id, request_for: request_for },
                    success: function (data) {
                        if (data.status === true) {
                            notify('success', data.message, 'IB to Trader transaction');
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'IB to Trader transaction!',
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

    // set category to traders
    $(document).on("click", ".save-category", function () {
        let id = $(this).data('id');
        let category = $(this).closest('.row').find('select').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/client-management/trader-admin-set-category',
            method: 'POST',
            dataType: 'json',
            data: { id: id, category: category },
            success: function (data) {
                if (data.status === true) {
                    notify('success', data.message, 'Set Category')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Set Category!',
                        html: (data.hasOwnProperty('message')) ? data.message : data.errors.category,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            }
        })
    });

    //Reset passsword
    $(document).on("click", ".reset-password-btn", function () {
        let id = $(this).data('id');
        let $request_url = '/admin/client-management/trader-admin-reset-password';
        let data = { trader_id: id, };
        let $url = '/admin/client-management/trader-admin-reset-password-mail/' + id;
        confirm_alert('Reset Trader Password', 'Are you confirm reset trader password', $request_url, data, 'Reset password', null, true, $url);
    });

    //Reset transaction pin
    $(document).on("click", ".transaction-pin-reset", function () {
        let id = $(this).data('id');
        let $request_url = '/admin/client-management/trader-admin-reset-transaction-pin';
        let data = { trader_id: id };
        let $url = '/admin/client-management/trader-admin-reset-transaction-pin-mail/' + id;
        confirm_alert('Reset Trader Transaction Pin', 'Are you confirm reset transaction pin', $request_url, data, 'Transaction pin reset', null, true, $url);
    });
    // get user data for update
    // user profile---------------------------------------------------------
    $(document).on("click", ".btn-update-profile", function () {
        let user_id = $(this).data('user');
        $.ajax({
            url: '/admin/client-management/get-user-info/' + user_id,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#update-profile").find('#name').val(data.user.name);
                $("#update-profile").find('#email').val(data.user.email);
                $("#update-profile").find('#phone').val(data.user.phone);
                $('#update-profile').find('#country option[value="' + data.user.country_id + '"]').prop('selected', true);
                $('#update-profile').find('#live-status-update option[value="' + data.user.live_status + '"]').prop('selected', true);
                $("#update-profile").find('#appr-investment').val(data.user.app_investment);
                $("#update-profile").find('#city').val(data.user.city);
                $("#update-profile").find('#state').val(data.user.state);
                $("#update-profile").find('#zip-code').val(data.user.zip_code);
                $("#update-profile").find('#address').val(data.user.address);
                $("#update-profile").find('#trading-ac-limit').val(data.user.trading_ac_limit);
                $("#update-profile").find('#transaction-pin').val(data.transaction_password);
                $("#update-profile").find('#password').val(data.password);
                $("#update-profile").find('#user-id').val(data.user.user_id);
                $("#update-profile").find('#facebook').val(data.social.facebook);
                $("#update-profile").find('#twitter').val(data.social.twitter);
                $("#update-profile").find('#whatsapp').val(data.social.whatsapp);
                $("#update-profile").find('#linkedin').val(data.social.linkedin);
                $("#update-profile").find('#skype').val(data.social.skype);
                $("#update-profile").find('#telegram').val(data.social.telegram);
                // set value each user id

                $(".update-profile-user-id").each(function () {
                    $(this).val(user_id);
                })
                // verification status
                // alert(data.user.kyc_status);
                if (data.user.kyc_status == 1) {
                    $("#verified").prop('checked', true);
                } else {
                    $("#verified").prop('checked', false);
                }
            }
        });
        $("#update-profile").modal("show");
    });
    //   get data for document selector
    // get data for kyc document
    $(document).on('click', '.get-perpose', function () {
        let perpose = $(this).data('perpose');
        if (perpose === 'id proof') {
            $("#tabVerticalRight3").find('input[type="hidden"]').prop('disabled', false);
            $("#tabVerticalRight4").find('input[type="hidden"]').prop('dissabled', true);
        }
        else {
            $("#tabVerticalRight3").find('input[type="hidden"]').prop('disabled', true);
            $("#tabVerticalRight4").find('input[type="hidden"]').prop('disabled', false);
        }
        $.ajax({
            url: '/admin/client-management/trader-admin?perpose=' + perpose,
            dataType: 'json',
            method: 'GET',
            success: function (data) {
                $("#kyc-doctype").html(data)
            }
        });
    })

    // get user id for DESK MANAGER/ and open modal
    // ASSIGN DESK MANAGER---------------------------------------------------------
    $(document).on("click", ".btn-desk-manager", function () {
        let user_id = $(this).data('user');
        $('#assign-to-desk-manager').find('.trader-id-for-manager').val(user_id);
        $("#assign-to-desk-manager").modal("show");
    })

    // toushspin min max----------------------------------------------------
    var touchspinValue = $('.touchspin-min-max'),
        counterMin = 0,
        counterMax = 50;
    if (touchspinValue.length > 0) {
        touchspinValue
            .TouchSpin({
                min: counterMin,
                max: counterMax,
                buttondown_txt: feather.icons['minus'].toSvg(),
                buttonup_txt: feather.icons['plus'].toSvg()
            })
            .on('touchspin.on.startdownspin', function () {
                var $this = $(this);
                $('.bootstrap-touchspin-up').removeClass('disabled-max-min');
                if ($this.val() == counterMin) {
                    $(this).siblings().find('.bootstrap-touchspin-down').addClass('disabled-max-min');
                }
            })
            .on('touchspin.on.startupspin', function () {
                var $this = $(this);
                $('.bootstrap-touchspin-down').removeClass('disabled-max-min');
                if ($this.val() == counterMax) {
                    $(this).siblings().find('.bootstrap-touchspin-up').addClass('disabled-max-min');
                }
            });
    }

    // find desk manager----------------------------------------------
    $("#desk-manager-email").val("");
    $(document).on('blure keyup', "#desk-manager-email", function () {
        let email = $(this).val();
        $.ajax({
            url: '/admin/client-management/get-desk-manager/' + email,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.status == true) {
                    $("#assign-to-desk-manager").find('.desk-manager-info').find(".not-found-msg").slideUp();
                    $("#assign-to-desk-manager").find('.desk-manager-info').find(".tbl-manager-info").slideDown();
                    $("#assign-to-desk-manager").find('#desk-m-name').text(data.desk_manager_info.name)
                    $("#assign-to-desk-manager").find('#desk-m-email').text(data.desk_manager_info.email)
                    $("#assign-to-desk-manager").find('#desk-m-phone').text(data.desk_manager_info.phone)
                    $("#assign-to-desk-manager").find('#desk-m-country').text(data.desk_manager_info.country)
                    $("#assign-to-desk-manager").find('#desk-m-group').text(data.desk_manager_info.group_name)
                    // enable submit button
                    $("#btn-assign-desk-manager").prop('disabled', false);
                }
                if (data.status == false) {
                    $("#assign-to-desk-manager").find('.desk-manager-info').find(".not-found-msg").text(data.message).slideDown();
                    $("#assign-to-desk-manager").find('.desk-manager-info').find(".tbl-manager-info").slideUp();
                    // disable submit button
                    $("#btn-assign-desk-manager").prop('disabled', true);
                }
            }
        });
    })
    // end desk manager----------------------------------------------
    // get user id for account MANAGER/ and open modal
    // ASSIGN account MANAGER---------------------------------------------------------
    $(document).on("click", ".btn-account-manager", function () {
        let user_id = $(this).data('user');
        $('#assign-to-account-manager').find('.trader-id-for-manager').val(user_id);
        $("#assign-to-account-manager").modal("show");
    })

    // find account manager----------------------------------------------
    $("#desk-manager-email").val("");
    $(document).on('blure keyup', "#account-manager-email", function () {
        let email = $(this).val();
        $.ajax({
            url: '/admin/client-management/get-account-manager/' + email,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.status == true) {
                    $("#assign-to-account-manager").find('.account-manager-info').find(".not-found-msg").slideUp();
                    $("#assign-to-account-manager").find('.account-manager-info').find(".tbl-manager-info").slideDown();
                    $("#assign-to-account-manager").find('#account-m-name').text(data.account_manager_info.name)
                    $("#assign-to-account-manager").find('#account-m-email').text(data.account_manager_info.email)
                    $("#assign-to-account-manager").find('#account-m-phone').text(data.account_manager_info.phone)
                    $("#assign-to-account-manager").find('#account-m-country').text(data.account_manager_info.country)
                    $("#assign-to-account-manager").find('#account-m-group').text(data.account_manager_info.group_name)
                    // enable submit button
                    $("#btn-assign-account-manager").prop('disabled', false);
                }
                if (data.status == false) {
                    $("#assign-to-account-manager").find('.account-manager-info').find(".not-found-msg").text(data.message).slideDown();
                    $("#assign-to-account-manager").find('.account-manager-info').find(".tbl-manager-info").slideUp();
                    // disable submit button
                    $("#btn-assign-account-manager").prop('disabled', true);
                }
            }
        });
    })
    // end account manager----------------------------------------------

    // resent verification email------------------------------------------
    $(document).on("click", ".btn-resent-verification-email", function () {
        let trader_id = $(this).data('user');
        var close_time;
        Swal.fire({
            title: 'Verification Email',
            text: 'Are You Confirm to send verification email',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Send Email',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                $(".swal2-html-container").text("We Sending Email, Please Wait.....")
                return fetch(`/admin/client-management/resent-verification-email/` + trader_id)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value.status == false) {
                    toastr['error'](result.value.message, 'Verification Email', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                } else {
                    toastr['success'](result.value.message, 'Verification Email', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }

            }
        })
    })
    // END: resent verfication email--------------------------------------

    // send welcome mail------------------------------------------
    $(document).on("click", ".btn-send-welcome-mail", function () {
        let trader_id = $(this).data('user');
        var close_time;
        Swal.fire({
            title: 'Welcome Email',
            text: 'Are You Confirm to send welcome mail ?',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Send Email',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                $(".swal2-html-container").text("We Sending Email, Please Wait.....")
                return fetch(`/admin/client-management/send-welcome-email/` + trader_id)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            console.log(result);
            if (result.isConfirmed) {
                if (result.value.status == false) {
                    notify('error', result.nvalue.message, 'Welcome Email');
                } else {
                    notify('success', result.value.message, 'Welcome Email');
                }
            }
        })
    })
    // END: send welcome mail--------------------------------------

    // finance report--------------------------------------------
    $(document).on("click", '.btn-finance-report', function () {
        let id = $(this).data('user');
        $.ajax({
            url: '/admin/client-management/finance-report/' + id,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#total-deposit-amount").find('span').html(data.total_deposit_amount);
                $("#total-deposit-counter").find('span').html(data.total_deposit_counter);
                $("#total-rec-fund-amount").find('span').html(data.total_rec_fund_amount);
                $("#total-rec-fund-counter").find('span').html(data.total_rec_fund_counter);
                $("#total-up-balance-amount").find('span').html(data.total_up_balance_amount);
                $("#total-up-balance-counter").find('span').html(data.total_up_balance_counter);
                $("#total-dc-balance-amount").find('span').html(data.total_dc_balance_amount);
                $("#total-dc-balance-counter").find('span').html(data.total_dc_balance_counter);
                $("#total-withdraw-amount").find('span').html(data.total_withdraw_amount);
                $("#total-withdraw-counter").find('span').html(data.total_withdraw_counter);
                $("#total-pending-withdraw-amount").find('span').html(data.total_pending_withdraw_amount);
                $("#total-pending-withdraw-counter").find('span').html(data.total_pending_withdraw_counter);
                $("#total-send-fund-amount").find('span').html(data.total_send_fund_amount);
                $("#total-send-fund-counter").find('span').html(data.total_send_fund_counter);
                $("#total-ib-balance-amount").find('span').html(data.total_ib_balance_amount);
                $("#total-ib-balance-counter").find('span').html(data.total_ib_balance_counter);
                $("#total-current-balance").find('span').html(data.total_current_balance);
            }
        });
        $("#finance-report").modal('show');
    })
    // ending finance report---------------------------------------

    // trader registrations*********************************************
    // get account category data for registrations------------------------------------
    $(document).on("change", "#server", function () {
        let server = $(this).val();
        $.ajax({
            url: '/admin/client-management/get-client-type/' + server,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#client-type").html(data);
                $("#client-type").data('server', server);
            }
        });
    });


    // end: get account category data------------------------------------------
    // get client group data for registrations------------------------------------
    $(document).on("change", "#client-type", function () {
        let server = $(this).val();
        let client_type = $(this).data('server');
        $.ajax({
            url: '/admin/client-management/get-client-groups/' + client_type + '/meta-server/' + server,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#account-type").html(data.client_groups);
                $("#leverage").html(data.leverage);
            }
        });
    })
    // get leverage for add acccount
    // for manually
    $(document).on("change", "#platform-account", function () {
        let server = $(this).val();
        let client_type = 'live';
        $.ajax({
            url: '/admin/client-management/get-client-groups/' + server + '/meta-server/' + client_type,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#group-account").html(data.client_groups);
                $("#leverage-account").html(data.leverage);
            }
        });
    });
    

    // get leverage for add acccount
    // for auto/open live account
    $(document).on("change", "#platform-live", function () {
        let server = $(this).val();
        let client_type = 'live';
        $.ajax({
            url: '/admin/client-management/get-client-groups/' + server + '/meta-server/' + client_type,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#group-live").html(data.client_groups);
                $("#leverage-live").html(data.leverage);
            }
        });
    });
    // get leverage add account
    // add manually
    $(document).on("change", "#group-account", function () {
        let group_id = $(this).val();
        $.ajax({
            url: '/admin/client-management/get-leverage/' + group_id,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#leverage-account").html(data);
            }
        });
    })
    // get leverage add account
    // add auto/open live account
    $(document).on("change", "#group-live", function () {
        let group_id = $(this).val();
        $.ajax({
            url: '/admin/client-management/get-leverage/' + group_id,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#leverage-live").html(data);
            }
        });
    })
    // end: get client group data------------------------------------------
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
    // get user
    // filter_user(0,"account-manager-email","browser");

});