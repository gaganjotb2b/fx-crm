
var dt_cp_settings;
dt_cp_settings = $('#currency-pair-table').DataTable({
    "processing": true,
    "serverSide": true,
    "searching": false,
    "retrieve": true,
    "paging": false,
    "lengthChange": true,
    "buttons": true,
    "dom": 'B<"clear">lfrtip',
    "ajax": {
        "url": "/admin/settings/currency-pair-get",
        "data": function (d) {
            return $.extend({}, d, {
                "symbol": $("#symbol").val(),
                "title": $("#title").val(),
                "ib_rebate": $("#ib_rebate").val(),
                "active_status": $("#active_status").val(),
            });
        }
    },
    "columns": [
        { "data": "serial" },
        { "data": "symbol" },
        { "data": "title" },
        { "data": "ib_rebate" },
        { "data": "active_status" },
        { "data": "action" },
    ],

    "drawCallback": function (settings) {
        $("#filterBtn").html("FILTER");
        var rows = this.fnGetData();
        if (rows.length !== 0) {
            feather.replace();
        }
    }
});
$('#filterBtn').click(function (e) {
    dt_cp_settings.draw();
});

$(document).on("click", "#resetBtn", function (event) {
    $("#filterForm")[0].reset();
    dt_cp_settings.draw();
});

// currency pair add
$(document).on("submit", "#currency-pair-form", function (event) {
    let form_data = new FormData(this);
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "/admin/settings/currency-pair-add",
        dataType: "json",
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == false) {
                notify('error', "Fix The Following Error!", 'Currency Pair');
                $.validator("currency-pair-form", data.errors);
            }
            if (data.status == true) {
                notify('success', "Successfully Added", 'Currency Pair');
                dt_cp_settings.draw();
                $("#currency-pair-form")[0].reset();
                $('#close-btn').trigger('click');
            }
        }
    });
});  //END: click function 
// passing id to currency pair settings delete modal
$(document).on("click", "#currency-pair-delete-button", function (event) {
    let id = $(this).data('id');
    $('#currency-pair-delete-id').val(id);
});
// currency pair delete action
$(document).on("click", "#currency-pair-delete", function (event) {
    let id = $('#currency-pair-delete-id').val();
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "/admin/settings/currency-pair-delete/" + id,
        dataType: "json",
        // data: { 'id': id },
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == false) {
                notify('error', data.message, 'Currency Pair');
            }
            if (data.status == true) {
                notify('success', data.message, 'Currency Pair');
                dt_cp_settings.draw();
            }
        }
    });
});  //END: click function 

// passing id to currency pair settings edit modal
$(document).on("click", "#currency-pair-edit-modal-button", function (event) {
    let id = $(this).data('id');
    $('#currency_pair_id').val(id);
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "/admin/settings/currency-pair/modal-fetch-data/" + id,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == true) {
                $('#modal-symbol').val(data.symbol);
                $('#modal-title').val(data.title);
                if (data.ib_rebate == 'yes') {
                    $("#modal_ib_rebate option[value='yes']").prop("selected", true);
                    $('#modal_ib_rebate').prop('selectedIndex', 1).trigger("change");
                } else {
                    $("#modal_ib_rebate option[value='no']").prop("selected", true);
                    $('#modal_ib_rebate').prop('selectedIndex', 0).trigger("change");
                }
                if (data.active_status == 0) {
                    $("#modal-status option[value='0']").prop("selected", true);
                    $('#modal-status').prop('selectedIndex', 0).trigger("change");
                } else {
                    $("#modal-status option[value='1']").prop("selected", true);
                    $('#modal-status').prop('selectedIndex', 1).trigger("change");
                }
            }
        }
    });
});
// update currency pair callback
function currencyPairUpdateCallBack(data) {
    if (data.success) {
        notify('success', data.message, 'Currency Pair');
        $('#currency-pair-edit-form').modal('toggle');
        dt_cp_settings.draw();
    } else {
        notify('error', data.message, 'Currency Pair');
    }
    $.validator("currency-pair-edit-modal-form", data.errors);
}