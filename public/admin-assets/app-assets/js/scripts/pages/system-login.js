// system login scripts
$(document).on("submit", "#login-form", function (event) {
    let form_data = new FormData(this);
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: '/system/login',
        dataType: 'json',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == false) {
                $('#alert-message').html(data.message).removeClass('d-none').addClass('d-block');
                Swal.fire({
                    icon: 'error',
                    title: 'Error found!',
                    html: data.message,
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            }
            if (data.status == true) {
                toastr['success'](data.message, 'System Login', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
                setTimeout(function () {
                    window.location.href = "/system/dashboard";
                }, 1000 * 2);
            }
        }
    });
});  //END: click function 