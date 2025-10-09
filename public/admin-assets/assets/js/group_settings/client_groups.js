$(function () {
    "use strict";

    var dt_basic_table = $(".datatables-basic");

    // DataTable with buttons
    // --------------------------------------------------------------------

    if (dt_basic_table.length) {
        var dt_basic = dt_basic_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: clientGroupData,
            columns: [
                // { data: "responsive_id" },
                // { data: "id" }, // used for sorting so will hide this column
                {
                    data: "server",
                },
                {
                    data: "book",
                },
                {
                    data: "group_id",
                },
                {
                    data: "group_name",
                },
                {
                    data: "max_leverage",
                },
                {
                    data: "min_deposit",
                },
                {
                    data: "visibility",
                },
                {
                    data: "",
                },
            ],
            columnDefs: [
                {
                    // Actions
                    targets: -1,
                    title: "Actions",
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            `<a href="#" class="client-group-edit me-2 btn-edit-group" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#edit-client-group" data-bs-placement="top" title="Edit Group">` +
                            feather.icons["edit"].toSvg({
                                class: "font-small-4",
                            }) +
                            "</a>" +
                            `<a href="javascript:;" class="client-group-delete me-2 d-none" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#delete-client-group" data-bs-placement="top" title="Delete Group">` +
                            feather.icons["trash"].toSvg({
                                class: "font-small-4",
                            }) +
                            "</a>"
                        );
                    },
                },
            ],
            order: [[0, "desc"]],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: "&nbsp;",
                    next: "&nbsp;",
                },
            },
        });
    }

    // Delete Record
    $(".datatables-basic tbody").on("click", ".client-group-delete", function () {
        // console.log($(this).data('id'))
        // console.log('clicked')
        $("#delete-client-group #delete_client-group-id").val($(this).data('id'));
    });

    $("#delete-client-group").on("submit", "form", function (e) {
        e.preventDefault();
        console.log('submitted')
        const id = $(this).find('#delete_client-group-id').val();
        $.ajax({
            type: "DELETE",
            url: `${addClientGroup}/${id}`,
            data: { id },
            dataType: "JSON",

            success: function (data) {
                // console.log(data)
                if (data.status == "success") {
                    Swal.fire("Success!", `${data.msg}`, "success");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                } else if (data.status == "failed") {
                    Swal.fire("Failed!", `${data.msg}`, "error");
                }
            },
        });
    });

    $(".get-group-button").on("click", function (e) {
        e.preventDefault();
        const id = null;
        $.ajax({
            url: '/admin/client-groups/get',
            dataType: "JSON",
            method: "POST",

            success: function (data) {
                // console.log(data)
                if (data.status == "success") {
                    Swal.fire("Success!", `${data.msg}`, "success");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                } else if (data.status == "failed") {
                    Swal.fire("Failed!", `${data.msg}`, "error");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                }
            },
        });
    });
});
