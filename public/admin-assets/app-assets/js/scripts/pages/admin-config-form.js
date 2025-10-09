$(document).ready(function () {
    // choose transaction cherge limit
    $('#fixed, #percentage').prop('checked', false);
    $('#transaction-charge-limit').hide();
    $('#transaction-charge-limit-space-holder').hide();
    $('#fixed').change(function () {
        if ($(this).is(':checked')) {
            $('#fixed').prop('checked', true);
            $('#percentage').prop('checked', false);
            $('#transaction-charge-limit-space-holder').hide();
            $('#transaction-charge-limit').show();
        } else {
            $('#fixed').prop('checked', false);
            $('#transaction-charge-limit').hide();
            $('#transaction-charge-limit-space-holder').show();
        }
    });
    $('#percentage').change(function () {
        if ($(this).is(':checked')) {
            $('#percentage').prop('checked', true);
            $('#fixed').prop('checked', false);
            $('#transaction-charge-limit-space-holder').hide();
            $('#transaction-charge-limit').show();
        } else {
            $('#percentage').prop('checked', false);
            $('#transaction-charge-limit').hide();
            $('#transaction-charge-limit-space-holder').show();

        }
    });
});

// social media checkbox
$(document).ready(function () {

    $('#view-all-check, #facebook-check, #twitter-check, #skype-check, #youtube-check,  #telegram-check, #whatsapp-check, #line-check, #instagram-check').prop('checked', false);
    $('#facebook, #twitter, #skype, #youtube,  #telegram, #whatsapp, #line, #instagram').hide();
    $('#view-all-check').change(function () {
        if ($(this).is(':checked')) {
            $('#facebook-check, #twitter-check, #skype-check, #youtube-check,  #telegram-check,  #whatsapp-check, #line-check, #instagram-check').prop('checked', true);
            $('#facebook, #twitter, #skype, #youtube,  #telegram, #whatsapp, #line, #instagram').show();
        } else {
            $('#facebook-check, #twitter-check, #skype-check, #youtube-check,  #telegram-check, #whatsapp-check, #line-check, #instagram-check').prop('checked', false);
            $('#facebook, #twitter, #skype, #youtube,  #telegram, #whatsapp, #line, #instagram').hide();
            $('#facebook, #twitter, #skype, #youtube,  #telegram, #whatsapp, #line, #instagram').find("input").val("");
        }
    });
    if ($('.facebook').val()) {
        $('#facebook-check').prop('checked', true);
        $('#facebook').show();
    }
    if ($('.twitter').val()) {
        $('#twitter-check').prop('checked', true);
        $('#twitter').show();
    }
    if ($('.skype').val()) {
        $('#skype-check').prop('checked', true);
        $('#skype').show();
    }
    if ($('.youtube').val()) {
        $('#youtube-check').prop('checked', true);
        $('#youtube').show();
    }
    if ($('.telegram').val()) {
        $('#telegram-check').prop('checked', true);
        $('#telegram').show();
    }
    if ($('.whatsapp').val()) {
        $('#whatsapp-check').prop('checked', true);
        $('#whatsapp').show();
    }
    if ($('.instagram').val()) {
        $('#instagram-check').prop('checked', true);
        $('#instagram').show();
    }
    if ($('.line').val()) {
        $('#line-check').prop('checked', true);
        $('#line').show();
    }
    $('#facebook-check').change(function () {
        if ($(this).is(':checked')) {
            $('#facebook-check').prop('checked', true);
            $('#facebook').show();
        } else {
            $('#view-all-check, #facebook-check').prop('checked', false);
            $('#facebook').hide();
            $('#facebook').find("input").val("");
        }
    });
    $('#twitter-check').change(function () {
        if ($(this).is(':checked')) {
            $('#twitter-check').prop('checked', true);
            $('#twitter').show();
        } else {
            $('#view-all-check, #twitter-check').prop('checked', false);
            $('#twitter').hide();
            $('#twitter').find("input").val("");
        }
    });
    $('#skype-check').change(function () {
        if ($(this).is(':checked')) {
            $('#skype-check').prop('checked', true);
            $('#skype').show();
        } else {
            $('#view-all-check, #skype-check').prop('checked', false);
            $('#skype').hide();
            $('#skype').find("input").val("");
        }
    });
    $('#youtube-check').change(function () {
        if ($(this).is(':checked')) {
            $('#youtube-check').prop('checked', true);
            $('#youtube').show();
        } else {
            $('#view-all-check, #youtube-check').prop('checked', false);
            $('#youtube').hide();
            $('#youtube').find("input").val("");
        }
    });
    $('#telegram-check').change(function () {
        if ($(this).is(':checked')) {
            $('#telegram-check').prop('checked', true);
            $('#telegram').show();
        } else {
            $('#view-all-check, #telegram-check').prop('checked', false);
            $('#telegram').hide();
            $('#telegram').find("input").val("");
        }
    });
    $('#line-check').change(function () {
        if ($(this).is(':checked')) {
            $('#line-check').prop('checked', true);
            $('#line').show();
        } else {
            $('#view-all-check, #line-check').prop('checked', false);
            $('#line').hide();
            $('#line').find("input").val("");
        }
    });
    $('#instagram-check').change(function () {
        if ($(this).is(':checked')) {
            $('#instagram-check').prop('checked', true);
            $('#instagram').show();
        } else {
            $('#view-all-check, #instagram-check').prop('checked', false);
            $('#instagram').hide();
            $('#instagram').find("input").val("");
        }
    });
    $('#whatsapp-check').change(function () {
        if ($(this).is(':checked')) {
            $('#whatsapp-check').prop('checked', true);
            $('#whatsapp').show();
        } else {
            $('#view-all-check, #whatsapp-check').prop('checked', false);
            $('#whatsapp').hide();
            $('#whatsapp').find("input").val("");
        }
    });
});


// system nav controll
$(document).ready(function () {
    var dt_finance_settings;
    // get data by ajax 
    dt_finance_settings = $('.finance-settings-table').DataTable({
        language: {
            search: "",
            lengthMenu: " _MENU_ "
        },
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/admin/settings/finance_setting/fetch_data",
            "data": function (d) {
                return $.extend({}, d, {

                });
            }
        },
        "columns": [
            { "data": "transaction_type" },
            { "data": "transaction_limit" },
            { "data": "charge_type" },
            { "data": "charge_limit" },
            { "data": "kyc" },
            { "data": "amount" },
            { "data": "status" },
            { "data": "active_status" },
            { "data": "action" },
        ],
        "order": [[1, 'desc']],
        "drawCallback": function (settings) {
            var rows = this.fnGetData();
            if (rows.length !== 0) {
                feather.replace();
            }
        }
    });
    $('#filterBtn').click(function (e) {
        dt_finance_settings.draw();
    });


    // finance settings form validation
    $('#charge').on('blur keyup', function () {
        $('#charge_error').html('');
    });
    $('#fixed').on('click', function () {
        $('#charge_type_error').html('');
    });
    $('#percentage').on('click', function () {
        $('#charge_type_error').html('');
    });
    $('#charge').on('click', function () {
        $('#charge_error').html('');
    });
    // finance settings add action 
    $(document).on("submit", "#finance-settings-form-add", function (event) {
        let form_data = new FormData(this);
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "/admin/settings/finance-settings/add",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == false) {
                    let $errors = '';
                    if (data.charge_type) {
                        $('#charge_type_error').html(data.charge_type);
                    }
                    if (data.amount) {
                        $('#charge_error').html(data.amount);
                    }
                    if (data.message) {
                        toastr['error'](data.message, 'Finance Settings', {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                        });
                    } else {
                        toastr['error']('Failed To Update!', 'Finance Settings', {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                        });
                    }

                }
                if (data.status == true) {
                    toastr['success'](data.message, 'Finance Settings', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    dt_finance_settings.draw();
                }
            }
        });
    });  //END: click function 

    // passing id to finance settings delete modal
    $(document).on("click", "#finance-setting-delete-button", function (event) {
        let id = $(this).data('id');
        $('#finance-setting-delete-id').val(id);
    });
    // finance settings delete action 
    $(document).on("click", "#finance-setting-delete", function (event) {
        var id = $('#finance-setting-delete-id').val();
        // let form_data = new FormData(this);
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "/admin/settings/finance-settings/delete/" + id,
            dataType: "json",
            data: id,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == false) {
                    Swal.fire({
                        icon: "error",
                        title: "Error found!",
                        html: $errors,
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
                if (data.status == true) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        html: data.message,
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    });
                    dt_finance_settings.draw();
                }
            }
        });
    });  //END: click function 
    // passing id to finance settings active status change
    $(document).on("click", "#finance-setting-active-status-button", function (event) {
        let id = $(this).data('id');
        let value = $(this).data('value');
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "/admin/settings/finance-settings/change-active-status/" + id + "/" + value,
            dataType: "json",
            data: id,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == false) {
                    Swal.fire({
                        icon: "error",
                        title: "Finance Settings",
                        html: $errors,
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
                if (data.status == true) {
                    Swal.fire({
                        icon: "success",
                        title: "Finance Settings",
                        html: data.message,
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    });
                    dt_finance_settings.draw();
                }
            }
        });
    });  //END: click function 

    // get transaction_setting_id
    // $(document).on('click', '#finance-setting-edit-button', function (event) {
    //     let id = $(this).data('id');
    //     $("#transaction_setting_id").val(id);
    //     var transaction_setting_id = $('#transaction_setting_id').val();
    //     event.preventDefault();
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         type: "GET",
    //         url: "/admin/settings/finance-settings/edit_modal/fetch_data/" + transaction_setting_id,
    //         dataType: "json",
    //         cache: false,
    //         contentType: false,
    //         processData: false,

    //         success: function (data) {
    //             if (data.status == false) {
    //                 Swal.fire({
    //                     icon: "error",
    //                     title: "Error found!",
    //                     html: $errors,
    //                     customClass: {
    //                         confirmButton: "btn btn-danger"
    //                     }
    //                 });
    //             }
    //             if (data.status == true) {
    //                 $("#modal_active_status").val(data.transaction_type);
    //                 if (data.transaction_type == "deposit") {
    //                     $("#modal_transaction_type option[value='deposit']").prop("selected", true);
    //                     $('#modal_transaction_type').prop('selectedIndex', 0).trigger("change");
    //                 } else if (data.transaction_type == "withdraw") {
    //                     $("#modal_transaction_type option[value='withdraw']").prop("selected", true);
    //                     $('#modal_transaction_type').prop('selectedIndex', 1).trigger("change");
    //                 } else if (data.transaction_type == "a_to_w") {
    //                     $("#modal_transaction_type option[value='a_to_w']").prop("selected", true);
    //                     $('#modal_transaction_type').prop('selectedIndex', 2).trigger("change");
    //                 } else if (data.transaction_type == "w_to_a") {
    //                     $("#modal_transaction_type option[value='w_to_a']").prop("selected", true);
    //                     $('#modal_transaction_type').prop('selectedIndex', 2).trigger("change");
    //                 } else if (data.transaction_type == "a_to_a") {
    //                     $("#modal_transaction_type option[value='a_to_a']").prop("selected", true);
    //                     $('#modal_transaction_type').prop('selectedIndex', 3).trigger("change");
    //                 } else if (data.transaction_type == "w_to_w") {
    //                     $("#modal_transaction_type option[value='w_to_w']").prop("selected", true);
    //                     $('#modal_transaction_type').prop('selectedIndex', 3).trigger("change");
    //                 }
    //                 $("#modal_min_transaction").val(data.min_transaction);
    //                 $("#modal_max_transaction").val(data.max_transaction);
    //                 if (data.charge_type == "fixed") {
    //                     $("#modal-fixed").prop("checked", true);
    //                     $("#modal-percentage").prop("checked", false);
    //                 } else if (data.charge_type == "percentage") {
    //                     $("#modal-fixed").prop("checked", false);
    //                     $("#modal-percentage").prop("checked", true);
    //                 }

    //                 $("#modal_min_transaction").val(data.min_transaction);
    //                 $("#modal_max_transaction").val(data.max_transaction);
    //                 $("#modal_limit_start").val(data.limit_start);
    //                 $("#modal_limit_end").val(data.limit_end);

    //                 if (data.kyc == 1) {
    //                     $("#modal-kyc").prop("checked", true);
    //                 } else {
    //                     $("#modal-kyc").prop("checked", false);
    //                 }

    //                 $("#modal_amount").val(data.amount);
    //                 $("#modal_permission").val(data.permission);
    //                 if (data.permission == "panding") {
    //                     $("#modal_permission option[value='panding']").prop("selected", true);
    //                     $('#modal_permission').prop('selectedIndex', 0).trigger("change");
    //                 } else if (data.permission == "approved") {
    //                     $("#modal_permission option[value='approved']").prop("selected", true);
    //                     $('#modal_permission').prop('selectedIndex', 1).trigger("change");
    //                 }

    //                 $("#modal_active_status").val(data.active_status);
    //                 if (data.active_status == 0) {
    //                     $("#modal_active_status option[value='0']").prop("selected", true);
    //                     $('#modal_active_status').prop('selectedIndex', 0).trigger("change");
    //                 } else if (data.active_status == 1) {
    //                     $("#modal_active_status option[value='1']").prop("selected", true);
    //                     $('#modal_active_status').prop('selectedIndex', 1).trigger("change");
    //                 }
    //             }
    //         }
    //     });
    // });

    // modal transaction charge type check or uncheck
    // $('#modal-percentage').change(function () {
    //     if ($(this).is(':checked')) {
    //         $('#modal-fixed').prop('checked', false);
    //         $('#modal-percentage').prop('checked', true);
    //     } else {
    //         $('#modal-fixed').prop('checked', false);
    //         $('#modal-percentage').prop('checked', false);
    //     }
    // });
    // $('#modal-fixed').change(function () {
    //     if ($(this).is(':checked')) {
    //         $('#modal-fixed').prop('checked', true);
    //         $('#modal-percentage').prop('checked', false);
    //     } else {
    //         $('#modal-fixed').prop('checked', false);
    //         $('#modal-percentage').prop('checked', false);
    //     }
    // });

    // // finance settings edit action 
    // $(document).on("submit", "#finance-settings-edit-form", function (event) {
    //     let form_data = new FormData(this);
    //     event.preventDefault();
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         type: "POST",
    //         url: "/admin/settings/finance-settings/edit",
    //         dataType: "json",
    //         data: form_data,
    //         cache: false,
    //         contentType: false,
    //         processData: false,

    //         success: function (data) {
    //             if (data.status == false) {
    //                 let $errors = '';
    //                 if (data.charge_type) {
    //                     $('#charge_type_error').html(data.charge_type);
    //                 }
    //                 if (data.amount) {
    //                     $('#charge_error').html(data.amount);
    //                 }
    //                 if (data.message) {
    //                     toastr['error'](data.message, 'Finance Settings', {
    //                         showMethod: 'slideDown',
    //                         hideMethod: 'slideUp',
    //                         closeButton: true,
    //                         tapToDismiss: false,
    //                         progressBar: true,
    //                         timeOut: 2000,
    //                     });
    //                 } else {
    //                     toastr['error']('Failed To Update!', 'Finance Settings', {
    //                         showMethod: 'slideDown',
    //                         hideMethod: 'slideUp',
    //                         closeButton: true,
    //                         tapToDismiss: false,
    //                         progressBar: true,
    //                         timeOut: 2000,
    //                     });
    //                 }

    //             }
    //             if (data.status == true) {
    //                 toastr['success'](data.message, 'Finance Settings', {
    //                     showMethod: 'slideDown',
    //                     hideMethod: 'slideUp',
    //                     closeButton: true,
    //                     tapToDismiss: false,
    //                     progressBar: true,
    //                     timeOut: 2000,
    //                 });
    //                 dt_finance_settings.draw();

    //                 $('#finance-setting-edit-form').modal('toggle');
    //             }
    //         }
    //     });
    // });  //END: click function 
});

// system config controll
$(document).ready(function () {
    let platform_type = $('#platform_type').val();
    let mt4_server_type = $('.mt4_server_type').val();
    let mt5_server_type = $('.mt5_server_type').val();
    if (platform_type == "MT4") {
        $('.mt4-download-link').show();
        $('.mt5-download-link').hide();
        $('#mt4_server_type').show();
        $('#mt5_server_type').hide();
        // if mt4 server type not null 
        if (mt4_server_type == "Manager API") {
            $('.mt4_manager_ip_config').show();
            $('.mt4_web_app_config').hide();
        } else if (mt4_server_type == "Web App") {
            $('.mt4_manager_ip_config').hide();
            $('.mt4_web_app_config').show();
        } else {
            $('.mt4_manager_ip_config').hide();
            $('.mt4_web_app_config').hide();
        }
        $('.mt5_server_type_config').hide();
        // if change mt4 type 
        $(document).on('change', '.mt4_server_type', function () {
            let mt4_server_type = $('.mt4_server_type').val();
            if (mt4_server_type == "Manager API") {
                $('.mt4_manager_ip_config').show();
                $('.mt4_web_app_config').hide();
            } else if (mt4_server_type == "Web App") {
                $('.mt4_manager_ip_config').hide();
                $('.mt4_web_app_config').show();
            } else {
                $('.mt4_manager_ip_config').hide();
                $('.mt4_web_app_config').hide();
            }
        });
    } else if (platform_type == "MT5") {
        $('.mt4-download-link').hide();
        $('.mt5-download-link').show();
        $('#mt4_server_type').hide();
        $('#mt5_server_type').show();
        $('.mt4_manager_ip_config').hide();
        $('.mt4_web_app_config').hide();
        if (mt5_server_type != null) {
            $('.mt5_server_type_config').show();
        } else {
            $('.mt5_server_type_config').hide();
        }
        $(document).on('change', '.mt5_server_type', function () {
            let mt5_server_type = $('.mt5_server_type').val();
            if (mt5_server_type == "Demo") {
                $('.mt5_server_type_config').show();
            } else if (mt5_server_type == "Live") {
                $('.mt5_server_type_config').show();
            } else {
                $('.mt5_server_type_config').hide();
            }
        });
    } else if (platform_type == "Both") {
        $('.mt4-download-link').show();
        $('.mt5-download-link').show();
        $('#mt4_server_type').show();
        $('#mt5_server_type').show();
        // if mt4 server type not null 
        if (mt4_server_type == "Manager API") {
            $('.mt4_manager_ip_config').show();
            $('.mt4_web_app_config').hide();
        } else if (mt4_server_type == "Web App") {
            $('.mt4_manager_ip_config').hide();
            $('.mt4_web_app_config').show();
        } else {
            $('.mt4_manager_ip_config').hide();
            $('.mt4_web_app_config').hide();
        }
        // if mt5 server type not null
        if (mt5_server_type != null) {
            $('.mt5_server_type_config').show();
        } else {
            $('.mt5_server_type_config').hide();
        }
        // if change mt4 server type
        $(document).on('change', '.mt4_server_type', function () {
            let mt4_server_type = $('.mt4_server_type').val();
            if (mt4_server_type == "Manager API") {
                $('.mt4_manager_ip_config').show();
                $('.mt4_web_app_config').hide();
            } else if (mt4_server_type == "Web App") {
                $('.mt4_manager_ip_config').hide();
                $('.mt4_web_app_config').show();
            } else {
                $('.mt4_manager_ip_config').hide();
                $('.mt4_web_app_config').hide();
            }
        });
        // if change mt5 server type 
        $(document).on('change', '.mt5_server_type', function () {
            let mt5_server_type = $('.mt5_server_type').val();
            if (mt5_server_type == "Demo") {
                $('.mt5_server_type_config').show();
            } else if (mt5_server_type == "Live") {
                $('.mt5_server_type_config').show();
            } else {
                $('.mt5_server_type_config').hide();
            }
        });
    }
    // when change platform type value
    $(document).on('change', '#platform_type', function () {
        let platform_type = $('#platform_type').val();
        if (platform_type == "MT4") {
            $('.mt4-download-link').show();
            $('.mt5-download-link').hide();
            $('#mt4_server_type').show();
            $('#mt5_server_type').hide();
            $(document).on('change', '.mt4_server_type', function () {
                let mt4_server_type = $('.mt4_server_type').val();
                if (mt4_server_type == "Manager API") {
                    $('.mt4_manager_ip_config').show();
                    $('.mt4_web_app_config').hide();
                } else if (mt4_server_type == "Web App") {
                    $('.mt4_manager_ip_config').hide();
                    $('.mt4_web_app_config').show();
                } else {
                    $('.mt4_manager_ip_config').hide();
                    $('.mt4_web_app_config').hide();
                }
            });
            $('.mt5_server_type_config').hide();
        } else if (platform_type == "MT5") {
            $('.mt4-download-link').hide();
            $('.mt5-download-link').show();
            $('#mt4_server_type').hide();
            $('#mt5_server_type').show();
            $('.mt4_manager_ip_config').hide();
            $('.mt4_web_app_config').hide();
            $(document).on('change', '.mt5_server_type', function () {
                let mt5_server_type = $('.mt5_server_type').val();
                if (mt5_server_type == "Demo") {
                    $('.mt5_server_type_config').show();
                } else if (mt5_server_type == "Live") {
                    $('.mt5_server_type_config').show();
                } else {
                    $('.mt5_server_type_config').hide();
                }
            });

        } else if (platform_type == "Both") {
            $('.mt4-download-link').show();
            $('.mt5-download-link').show();
            $('#mt4_server_type').show();
            $('#mt5_server_type').show();
            $(document).on('change', '.mt4_server_type', function () {
                let mt4_server_type = $('.mt4_server_type').val();
                if (mt4_server_type == "Manager API") {
                    $('.mt4_manager_ip_config').show();
                    $('.mt4_web_app_config').hide();
                } else if (mt4_server_type == "Web App") {
                    $('.mt4_manager_ip_config').hide();
                    $('.mt4_web_app_config').show();
                } else {
                    $('.mt4_manager_ip_config').hide();
                    $('.mt4_web_app_config').hide();
                }
            });
            $(document).on('change', '.mt5_server_type', function () {
                let mt5_server_type = $('.mt5_server_type').val();
                if (mt5_server_type == "Demo") {
                    $('.mt5_server_type_config').show();
                } else if (mt5_server_type == "Live") {
                    $('.mt5_server_type_config').show();
                } else {
                    $('.mt5_server_type_config').hide();
                }
            });
        }
    });
});