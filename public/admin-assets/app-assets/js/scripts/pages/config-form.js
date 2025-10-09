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
    $('#view-all-check, #facebook-check, #twitter-check, #skype-check, #youtube-check,  #telegram-check, #linkedin-check, #livechat-check').prop('checked', false);
    $('#facebook, #twitter, #skype, #youtube,  #telegram, #linkedin, #livechat').hide();
    $('#view-all-check').change(function () {
        if ($(this).is(':checked')) {
            $('#facebook-check, #twitter-check, #skype-check, #youtube-check,  #telegram-check, #linkedin-check, #livechat-check').prop('checked', true);
            $('#facebook, #twitter, #skype, #youtube,  #telegram, #linkedin, #livechat').show();
        } else {
            $('#facebook-check, #twitter-check, #skype-check, #youtube-check,  #telegram-check, #linkedin-check, #livechat-check').prop('checked', false);
            $('#facebook, #twitter, #skype, #youtube,  #telegram, #linkedin, #livechat').hide();
        }
    });
    $('#facebook-check').change(function () {
        if ($(this).is(':checked')) {
            $('#facebook-check').prop('checked', true);
            $('#facebook').show();
        } else {
            $('#view-all-check, #facebook-check').prop('checked', false);
            $('#facebook').hide();
        }
    });
    $('#twitter-check').change(function () {
        if ($(this).is(':checked')) {
            $('#twitter-check').prop('checked', true);
            $('#twitter').show();
        } else {
            $('#view-all-check, #twitter-check').prop('checked', false);
            $('#twitter').hide();
        }
    });
    $('#skype-check').change(function () {
        if ($(this).is(':checked')) {
            $('#skype-check').prop('checked', true);
            $('#skype').show();
        } else {
            $('#view-all-check, #skype-check').prop('checked', false);
            $('#skype').hide();
        }
    });
    $('#youtube-check').change(function () {
        if ($(this).is(':checked')) {
            $('#youtube-check').prop('checked', true);
            $('#youtube').show();
        } else {
            $('#view-all-check, #youtube-check').prop('checked', false);
            $('#youtube').hide();
        }
    });
    $('#telegram-check').change(function () {
        if ($(this).is(':checked')) {
            $('#telegram-check').prop('checked', true);
            $('#telegram').show();
        } else {
            $('#view-all-check, #telegram-check').prop('checked', false);
            $('#telegram').hide();
        }
    });
    $('#linkedin-check').change(function () {
        if ($(this).is(':checked')) {
            $('#linkedin-check').prop('checked', true);
            $('#linkedin').show();
        } else {
            $('#view-all-check, #linkedin-check').prop('checked', false);
            $('#linkedin').hide();
        }
    });
    $('#livechat-check').change(function () {
        if ($(this).is(':checked')) {
            $('#livechat-check').prop('checked', true);
            $('#livechat').show();
        } else {
            $('#view-all-check, #livechat-check').prop('checked', false);
            $('#livechat').hide();
        }
    });
});


// system nav controll
$(document).ready(function () {
    var dt_finance_settings;
    $('#theme-setup-form').show();
    $('#api-configuration-form').hide();
    $('#smtp-setup-form').hide();
    $('#company-info-form').hide();
    $('#finance-setting-form').hide();
    $('#software-setting-form').hide();
    // tab action theme setup
    $(document).on('click', '#theme-setup', function () {
        $('#theme-setup').addClass("active");
        $('#api-configuration').removeClass("active");
        $('#smtp-setup').removeClass("active");
        $('#company-info').removeClass("active");
        $('.finance-setting').removeClass("active");
        $('#software-setting').removeClass("active");
        $('#theme-setup-form').show();
        $('#api-configuration-form').hide();
        $('#smtp-setup-form').hide();
        $('#company-info-form').hide();
        $('#finance-setting-form').hide();
        $('#software-setting-form').hide();
    });
    // tab action api configuration
    $(document).on('click', '#api-configuration', function () {
        $('#theme-setup').removeClass("active");
        $('#api-configuration').addClass("active");
        $('#smtp-setup').removeClass("active");
        $('#company-info').removeClass("active");
        $('.finance-setting').removeClass("active");
        $('#software-setting').removeClass("active");
        $('#theme-setup-form').hide();
        $('#api-configuration-form').show();
        $('#smtp-setup-form').hide();
        $('#company-info-form').hide();
        $('#finance-setting-form').hide();
        $('#software-setting-form').hide();
    });
    // tab action smtp setup
    $(document).on('click', '#smtp-setup', function () {
        $('#theme-setup').removeClass("active");
        $('#api-configuration').removeClass("active");
        $('#smtp-setup').addClass("active");
        $('#company-info').removeClass("active");
        $('.finance-setting').removeClass("active");
        $('#software-setting').removeClass("active");
        $('#theme-setup-form').hide();
        $('#api-configuration-form').hide();
        $('#smtp-setup-form').show();
        $('#company-info-form').hide();
        $('#finance-setting-form').hide();
        $('#software-setting-form').hide();
    });
    // tab action company settings
    $(document).on('click', '#company-info', function () {
        $('#theme-setup').removeClass("active");
        $('#api-configuration').removeClass("active");
        $('#smtp-setup').removeClass("active");
        $('#company-info').addClass("active");
        $('.finance-setting').removeClass("active");
        $('#software-setting').removeClass("active");
        $('#theme-setup-form').hide();
        $('#api-configuration-form').hide();
        $('#smtp-setup-form').hide();
        $('#company-info-form').show();
        $('#finance-setting-form').hide();
        $('#software-setting-form').hide();
    });
    // tab action finance settings
    $(document).on('click', '.finance-setting', function () {
        $('#theme-setup').removeClass("active");
        $('#api-configuration').removeClass("active");
        $('#smtp-setup').removeClass("active");
        $('#company-info').removeClass("active");
        $('.finance-setting').addClass("active");
        $('#software-setting').removeClass("active");
        $('#theme-setup-form').hide();
        $('#api-configuration-form').hide();
        $('#smtp-setup-form').hide();
        $('#company-info-form').hide();
        $('#finance-setting-form').show();
        $('#software-setting-form').hide();
        // clear datatable
        $('.finance-settings-table').DataTable().clear().destroy();

        // get data by ajax 
        dt_finance_settings = $('.finance-settings-table').DataTable({
            language: {
                search: "",
                lengthMenu: " _MENU_ "
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/system/finance_setting",
                "data": function (d) {
                    return $.extend({}, d, {

                    });
                }
            },
            "columns": [

                // { "data": "serial" },
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
            url: "/system/finance-settings/add",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == false) {
                    if (data.charge_type) {
                        $('#charge_type_error').html(data.charge_type);
                    }

                    let $errors = '';
                    if (data.errors.hasOwnProperty('amount')) {
                        $('#charge_error').html(data.errors.amount[0]);
                    }
                    toastr['error']('Failed To Update!', 'Finance Settings', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
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
            url: "/system/finance-settings/delete/" + id,
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

    // get transaction_setting_id
    $(document).on('click', '#finance-setting-edit-button', function (event) {
        let id = $(this).data('id');
        $("#transaction_setting_id").val(id);
        var transaction_setting_id = $('#transaction_setting_id').val();
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: "/system/finance-settings/getdata/" + transaction_setting_id,
            dataType: "json",
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
                    $("#modal_active_status").val(data.transaction_type);
                    if (data.transaction_type == "deposit") {
                        $("#modal_transaction_type option[value='Deposit']").prop("selected", true);
                        $('#modal_transaction_type').prop('selectedIndex', 0).trigger("change");
                    } else if (data.transaction_type == "withdraw") {
                        $("#modal_transaction_type option[value='Withdraw']").prop("selected", true);
                        $('#modal_transaction_type').prop('selectedIndex', 1).trigger("change");
                    } else if (data.transaction_type == "a_to_a") {
                        $("#modal_transaction_type option[value='a_to_a']").prop("selected", true);
                        $('#modal_transaction_type').prop('selectedIndex', 2).trigger("change");
                    } else if (data.transaction_type == "w_to_w") {
                        $("#modal_transaction_type option[value='w_to_w']").prop("selected", true);
                        $('#modal_transaction_type').prop('selectedIndex', 3).trigger("change");
                    }
                    $("#modal_min_transaction").val(data.min_transaction);
                    $("#modal_max_transaction").val(data.max_transaction);
                    if (data.charge_type == "fixed") {
                        $("#modal-fixed").prop("checked", true);
                        $("#modal-percentage").prop("checked", false);
                    } else if (data.charge_type == "percentage") {
                        $("#modal-fixed").prop("checked", false);
                        $("#modal-percentage").prop("checked", true);
                    }

                    $("#modal_min_transaction").val(data.min_transaction);
                    $("#modal_max_transaction").val(data.max_transaction);
                    $("#modal_limit_start").val(data.limit_start);
                    $("#modal_limit_end").val(data.limit_end);

                    if (data.kyc == 1) {
                        $("#modal-kyc").prop("checked", true);
                    } else {
                        $("#modal-kyc").prop("checked", false);
                    }

                    $("#modal_amount").val(data.amount);
                    $("#modal_permission").val(data.permission);
                    if (data.permission == "panding") {
                        $("#modal_permission option[value='panding']").prop("selected", true);
                        $('#modal_permission').prop('selectedIndex', 0).trigger("change");
                    } else if (data.permission == "approved") {
                        $("#modal_permission option[value='approved']").prop("selected", true);
                        $('#modal_permission').prop('selectedIndex', 1).trigger("change");
                    }

                    $("#modal_active_status").val(data.active_status);
                    if (data.active_status == 0) {
                        $("#modal_active_status option[value='0']").prop("selected", true);
                        $('#modal_active_status').prop('selectedIndex', 0).trigger("change");
                    } else if (data.active_status == 1) {
                        $("#modal_active_status option[value='1']").prop("selected", true);
                        $('#modal_active_status').prop('selectedIndex', 1).trigger("change");
                    }
                }
            }
        });
    });

    // modal transaction charge type check or uncheck
    $('#modal-percentage').change(function () {
        if ($(this).is(':checked')) {
            $('#modal-fixed').prop('checked', false);
            $('#modal-percentage').prop('checked', true);
        } else {
            $('#modal-fixed').prop('checked', false);
            $('#modal-percentage').prop('checked', false);
        }
    });
    $('#modal-fixed').change(function () {
        if ($(this).is(':checked')) {
            $('#modal-fixed').prop('checked', true);
            $('#modal-percentage').prop('checked', false);
        } else {
            $('#modal-fixed').prop('checked', false);
            $('#modal-percentage').prop('checked', false);
        }
    });

    // // finance settings form validation
    // $('#amount').on('blur keyup', function () {
    //     $('#amount_error').html('');
    // });
    // finance settings edit action 
    $(document).on("submit", "#finance-settings-edit-form", function (event) {
        let form_data = new FormData(this);
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "/system/finance-settings/edit",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == false) {
                    toastr['error']('Failed To Update!', 'Finance Settings', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
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

    // tab action company settings
    $(document).on('click', '#software-setting', function () {
        $('#theme-setup').removeClass("active");
        $('#api-configuration').removeClass("active");
        $('#smtp-setup').removeClass("active");
        $('#company-info').removeClass("active");
        $('.finance-setting').removeClass("active");
        $('#software-setting').addClass("active");
        $('#theme-setup-form').hide();
        $('#api-configuration-form').hide();
        $('#smtp-setup-form').hide();
        $('#company-info-form').hide();
        $('#finance-setting-form').hide();
        $('#software-setting-form').show();
    });
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