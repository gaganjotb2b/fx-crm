// admin registration scripts
$(document).on("submit", "#reg-form", function (event) {
    let form_data = new FormData(this);
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: '/admin/registration',
        dataType: 'json',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == false) {
                let $errors = '';
                if (data.errors.hasOwnProperty('name')) {
                    $errors += "  " + data.errors.name[0] + '<br>';
                }
                if (data.errors.hasOwnProperty('email')) {
                    $errors += "  " + data.errors.email[0] + '<br>';
                }
                if (data.errors.hasOwnProperty('password')) {
                    if (data.errors.password[0] != 'The password and password confirmation must match.') {
                        $errors += 'Minimum eight characters, at least one uppercase, lowercase letter and special character' + '<br>';
                    } else {
                        $errors += "  " + data.errors.password[0] + '';
                    }
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error found!',
                    html: $errors,
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            }
            if (data.status == true) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Completed',
                    html: data.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
                setTimeout(function () {
                    window.location.href = "/admin";
                }, 1000 * 2);
            }
        }
    });
});  //END: click function 