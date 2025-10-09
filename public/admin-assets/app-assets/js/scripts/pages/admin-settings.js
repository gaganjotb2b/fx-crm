// system nav controll
var dt_announcement_settings;
// get data by ajax 
dt_announcement_settings = $('.announcement-table').DataTable({
    language: {
        search: "",
        lengthMenu: " _MENU_ "
    },
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/admin/settings/announcement/fetch_data",
        "data": function (d) {
            return $.extend({}, d, {

            });
        }
    },
    "columns": [
        { "data": "title" },
        { "data": "dashboard" },
        { "data": "status" },
        { "data": "date" },
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
    dt_announcement_settings.draw();
});

// form validation
$('#title').on('blur keyup', function () {
    $('.error-msg').html('');
});
$('#comment').on('blur keyup', function () {
    $('.error-msg').html('');
});
// add announcement callback
function announcementCallBack(data) {
    $('#saveBtn').prop('disabled', false);
    if (data.success) {
        notify('success', data.message, 'Announcement');
        dt_announcement_settings.draw();
    } else {
        notify('error', 'Please fix the following errors', 'Announcement');
        $.validator("smtp-setup-form", data.errors);
    }
}

// get announcement by id
$(document).on('click', '#announcement-edit-button', function (event) {
    let id = $(this).data('id');
    $("#announcement_id").val(id);
    var announcement_id = $('#announcement_id').val();
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "GET",
        url: "/admin/settings/announcement/get_data_by_id/" + announcement_id,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.success == false) {
                notify('error', data.message, 'Announcement');
            }
            if (data.success == true) {
                $("#modal_title").val(data.title);
                $("#modal_comment").val(data.comment);
                if (data.dashboard == "all") {
                    $("#modal_dashboard option[value='all']").prop("selected", true);
                    $('#modal_dashboard').prop('selectedIndex', 0).trigger("change");
                } else if (data.transaction_type == "ib") {
                    $("#modal_dashboard option[value='ib']").prop("selected", true);
                    $('#modal_dashboard').prop('selectedIndex', 1).trigger("change");
                } else if (data.transaction_type == "trader") {
                    $("#modal_dashboard option[value='trader']").prop("selected", true);
                    $('#modal_dashboard').prop('selectedIndex', 2).trigger("change");
                } else if (data.transaction_type == "staff") {
                    $("#modal_dashboard option[value='staff']").prop("selected", true);
                    $('#modal_dashboard').prop('selectedIndex', 3).trigger("change");
                }
                if (data.status == 0) {
                    $("#modal_status option[value=0]").prop("selected", true);
                    $('#modal_status').prop('selectedIndex', 0).trigger("change");
                } else if (data.status == 1) {
                    $("#modal_status option[value=1]").prop("selected", true);
                    $('#modal_status').prop('selectedIndex', 1).trigger("change");
                }
            }
        }
    });
});

// update announcement callback
function announcementUpdateCallBack(data) {
    $('#updateBtn').prop('disabled', false);
    if (data.success) {
        notify('success', data.message, 'Announcement');
        dt_announcement_settings.draw();
    } else {
        notify('error', 'Please fix the following errors', 'Announcement');
        $.validator("smtp-setup-form", data.errors);
    }
}

// announcement delete 
$(document).on('click', '#announcement-delete-button', function (event) {
    var id = $(this).data('id');
    $('#announcement-delete-id').val(id);
});
// update announcement callback
function announcementDeleteCallBack(data) {
    $('#deleteBtn').prop('disabled', false);
    if (data.success) {
        notify('success', data.message, 'Announcement');
        dt_announcement_settings.draw();
    } else {
        Swal.fire({
            icon: "error",
            title: "Error found!",
            html: $errors,
            customClass: {
                confirmButton: "btn btn-danger"
            }
        });
    }
}
