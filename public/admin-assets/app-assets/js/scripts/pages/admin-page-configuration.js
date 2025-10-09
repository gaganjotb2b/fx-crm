
// input field validation
$('#mt4_server_ip').on('blur keyup', function () {
    $('#mt4_server_ip_error').html('');
});
$('#mt5_server_ip').on('blur keyup', function () {
    $('#mt5_server_ip_error').html('');
});
$('#mt4_manager_login').on('blur keyup', function () {
    $('#mt4_manager_login_error').html('');
});
$('#mt5_manager_login').on('blur keyup', function () {
    $('#mt5_manager_login_error').html('');
});
$('#mt4_manager_password').on('blur keyup', function () {
    $('#mt4_manager_password_error').html('');
});
$('#mt5_manager_password').on('blur keyup', function () {
    $('#mt5_manager_password_error').html('');
});
$('#mt5_api_password').on('blur keyup', function () {
    $('#mt5_api_password_error').html('');
});
$('#mt4_server_type').on('change', function () {
    $('#mt4_server_type_error').html('');
});
$('#mt5_server_type').on('change', function () {
    $('#mt5_server_type_error').html('');
});
$('#mt5_download_link').on('blur keyup', function () {
    $('#mt5_download_link_error').html('');
});
$('#mt4_download_link').on('blur keyup', function () {
    $('#mt4_download_link_error').html('');
});
$('#demo_api_key').on('blur keyup', function () {
    $('#demo_api_key_error').html('');
});
$('#api_url').on('blur keyup', function () {
    $('#api_url_error').html('');
});
$('#live_api_key').on('blur keyup', function () {
    $('#live_api_key_error').html('');
});
// api configuration ajax start
$(document).on("submit", "#api-configuration-form", function (event) {
    let form_data = new FormData(this);
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: '/admin/settings/api_configuration',
        dataType: 'json',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == false) {
                let $errors = '';
                if (data.errors.hasOwnProperty('mt4_server_ip')) {
                    $('#mt4_server_ip_error').html(data.errors.mt4_server_ip[0]);
                }
                if (data.errors.hasOwnProperty('mt5_server_ip')) {
                    $('#mt5_server_ip_error').html(data.errors.mt5_server_ip[0]);
                }
                if (data.errors.hasOwnProperty('mt4_manager_login')) {
                    $('#mt4_manager_login_error').html(data.errors.mt4_manager_login[0]);
                }
                if (data.errors.hasOwnProperty('mt5_manager_login')) {
                    $('#mt5_manager_login_error').html(data.errors.mt5_manager_login[0]);
                }
                if (data.errors.hasOwnProperty('mt4_manager_password')) {
                    $('#mt4_manager_password_error').html(data.errors.mt4_manager_password[0]);
                }
                if (data.errors.hasOwnProperty('mt5_manager_password')) {
                    $('#mt5_manager_password_error').html(data.errors.mt5_manager_password[0]);
                }
                if (data.errors.hasOwnProperty('mt5_api_password')) {
                    $('#mt5_api_password_error').html(data.errors.mt5_api_password[0]);
                }
                if (data.errors.hasOwnProperty('mt4_server_type')) {
                    $('#mt4_server_type_error').html(data.errors.mt4_server_type[0]);
                }
                if (data.errors.hasOwnProperty('mt5_server_type')) {
                    $('#mt5_server_type_error').html(data.errors.mt5_server_type[0]);
                }
                if (data.errors.hasOwnProperty('mt5_download_link')) {
                    $('#mt5-download-link-error').html(data.errors.mt5_download_link[0]);
                }
                if (data.errors.hasOwnProperty('mt4_download_link')) {
                    $('#mt4-download-link-error').html(data.errors.mt4_download_link[0]);
                }
                if (data.errors.hasOwnProperty('demo_api_key')) {
                    $('#demo_api_key_error').html(data.errors.demo_api_key[0]);
                }
                if (data.errors.hasOwnProperty('api_url')) {
                    $('#api_url_error').html(data.errors.api_url[0]);
                }
                if (data.errors.hasOwnProperty('live_api_key')) {
                    $('#live_api_key_error').html(data.errors.live_api_key[0]);
                }
                toastr['error']('Failed To Update!', 'Api Configuration', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
            if (data.status == true) {
                toastr['success'](data.message, 'Api Configuration', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                }).then((willDelete) => {
                    location.reload();
                });
            }
        }
    });
});  //END: click function 

// smtp setup form validation
$('#mail_driver').on('blur keyup', function () {
    $('#mail_driver_error').html('');
});
$('#host').on('blur keyup', function () {
    $('#host_error').html('');
});
$('#port').on('blur keyup', function () {
    $('#port_error').html('');
});
$('#mail_user').on('blur keyup', function () {
    $('#mail_user_error').html('');
});
$('#mail_password').on('blur keyup', function () {
    $('#mail_password_error').html('');
});
$('#mail_encryption').on('blur keyup', function () {
    $('#mail_encryption_error').html('');
});
// smtp setup function 
$(document).on("submit", "#smtp-setup-form", function (event) {
    let form_data = new FormData(this);
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: '/admin/settings/smtp_setup',
        dataType: 'json',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == false) {
                let $errors = '';
                if (data.errors.hasOwnProperty('mail_driver')) {
                    $('#mail_driver_error').html(data.errors.mail_driver[0]);
                }
                if (data.errors.hasOwnProperty('host')) {
                    $('#host_error').html(data.errors.host[0]);
                }
                if (data.errors.hasOwnProperty('port')) {
                    $('#port_error').html(data.errors.port[0]);
                }
                if (data.errors.hasOwnProperty('mail_user')) {
                    $('#mail_user_error').html(data.errors.mail_user[0]);
                }
                if (data.errors.hasOwnProperty('mail_password')) {
                    $('#mail_password_error').html(data.errors.mail_password[0]);
                }
                if (data.errors.hasOwnProperty('mail_encryption')) {
                    $('#mail_encryption_error').html(data.errors.mail_encryption[0]);
                }
                if (data.errors.hasOwnProperty('whatsapp')) {
                    $('#whatsapp_error').html(data.errors.whatsapp[0]);
                }
                toastr['error']('Failed To Update!', 'SMTP Setup', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
            if (data.status == true) {
                toastr['success'](data.message, 'SMTP Setup', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                }).then((willDelete) => {
                    location.reload();
                });
            }
        }
    });
});  //END: click function 

// conpany setup form validation
$('#com_name').on('blur keyup', function () {
    $('#com_name_error').html('');
});
$('#com_email_1').on('blur keyup', function () {
    $('#com_email_1_error').html('');
});
$('#com_phone_1').on('blur keyup', function () {
    $('#com_phone_1_error').html('');
});
$('#copyright').on('blur keyup', function () {
    $('#copyright_error').html('');
});
$('#support-email').on('blur keyup', function () {
    $('#support-email-error').html('');
});
$('#auto-email').on('blur keyup', function () {
    $('#auto-email-error').html('');
});
// company information setup function 
// $(document).on("submit", "#company-info-form", function (event) {
//     let form_data = new FormData(this);
//     event.preventDefault();
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });
//     $.ajax({
//         type: "POST",
//         url: '/admin/settings/company_setup_add',
//         dataType: 'json',
//         data: form_data,
//         cache: false,
//         contentType: false,
//         processData: false,

//         success: function (data) {
//             if (data.status == false) {
//                 let $errors = '';
//                 if (data.errors.hasOwnProperty('com_name')) {
//                     $('#com_name_error').html(data.errors.com_name[0]);
//                 }
//                 if (data.errors.hasOwnProperty('com_email_1')) {
//                     $('#com_email_1_error').html('The company primary email field is required!');
//                 }
//                 if (data.errors.hasOwnProperty('com_phone_1')) {
//                     $('#com_phone_1_error').html('The company primary phone field is required!');
//                 }
//                 if (data.errors.hasOwnProperty('copyright')) {
//                     $('#copyright_error').html(data.errors.copyright[0]);
//                 }
//                 if (data.errors.hasOwnProperty('support_email')) {
//                     $('#support-email-error').html(data.errors.support_email[0]);
//                 }
//                 if (data.errors.hasOwnProperty('auto_email')) {
//                     $('#auto-email-error').html(data.errors.auto_email[0]);
//                 }

//                 toastr['error']('Failed To Update!', 'Conpany Setup', {
//                     showMethod: 'slideDown',
//                     hideMethod: 'slideUp',
//                     closeButton: true,
//                     tapToDismiss: false,
//                     progressBar: true,
//                     timeOut: 2000,
//                 });
//             }
//             if (data.status == true) {
//                 toastr['success'](data.message, 'Company Setup', {
//                     showMethod: 'slideDown',
//                     hideMethod: 'slideUp',
//                     closeButton: true,
//                     tapToDismiss: false,
//                     progressBar: true,
//                     timeOut: 2000,
//                 }).then((willDelete) => {
//                     location.reload();
//                 });
//             }
//         }
//     });
// });  //END: click function 
// software settings function 
$(document).on("submit", "#software-setting-form", function (event) {
    let form_data = new FormData(this);
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: '/admin/settings/software_setting',
        dataType: 'json',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == false) {
                toastr['error']('Failed To Update!', 'Software Settings', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
            if (data.status == true) {
                toastr['success'](data.message, 'Software Settings', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                }).then((willDelete) => {
                    location.reload();
                });
            }
        }
    });
});  //END: click function 

