var datatable = $('.datatables-ajax').fetch_data({
    url: '/admin/settings/trader_setting/create-all-dt',
    columns: [
        { data: 'action' },
        { data: 'title' },
    ],
    perpage: 3
});

// submit request to reset 
$(document).on('click', '#create-all-permission', function () {

    Swal.fire({
        title: 'Create trader permission',
        text: 'Are You Confirm to reset trader permission ?',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Reset permission',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
            $(".swal2-html-container").text("Please wait while we create permission...")
            return fetch('/admin/settings/trader_setting/create-all')
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
                notify('error', result.value.message, 'Trader Permission');
            } else {
                datatable.draw()
                notify('success', result.value.message, 'Trader permission');
            }
        }
    })
})

// update settings
$(document).on('change', '.switch-trader-settings', function () {
    let id = $(this).val();
    let $this = $(this);
    Swal.fire({
        title: 'Update trader permission',
        text: 'Are You Confirm to reset trader permission ?',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Yes',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
            $(".swal2-html-container").text("Please wait while we create permission...")
            return fetch('/admin/settings/trader_setting/create-all?op=update&id=' + id)
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
                notify('error', result.value.message, 'Trader permission');
            } else {
                datatable.draw()
                notify('success', result.value.message, 'Trader permission');
            }
        } else {
            if ($($this).is(":checked")) {
                $($this).prop('checked', false);
            }
            else if ($($this).is(":not(:checked)")) {
                $($this).prop('checked', true);
            }
        }
    })
})

