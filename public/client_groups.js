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
                    data: "account_category",
                },
                {
                    data: "max_leverage",
                },
                {
                    data: "min_deposit",
                },
                {
                    data: "",
                },
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: "control",
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                },
                {
                    targets: 1,
                    visible: false,
                },
                {
                    // Actions
                    targets: -1,
                    title: "Actions",
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            `<a href="${addClientGroup}/${full.id}/edit" class="client-group-edit me-1" data-id="${full.id}">` +
                            feather.icons["edit"].toSvg({
                                class: "font-small-4",
                            }) +
                            "</a>" +
                            `<a href="javascript:;" class="client-group-delete" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#delete-client-group">` +
                            feather.icons["trash"].toSvg({
                                class: "font-small-4",
                            }) +
                            "</a>"
                        );
                    },
                },
            ],
            order: [[1, "desc"]],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return "Details of " + data["full_name"];
                        },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== "" // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIdx +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                "<td>" +
                                col.title +
                                ":" +
                                "</td> " +
                                "<td>" +
                                col.data +
                                "</td>" +
                                "</tr>"
                                : "";
                        }).join("");

                        return data
                            ? $('<table class="table"/>').append(
                                "<tbody>" + data + "</tbody>"
                            )
                            : false;
                    },
                },
            },
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
        $("#delete-client-group #delete_client-group-id").val($(this).data('id'));
    });

    $("#delete-client-group").on("submit", "form", function (e) {
        e.preventDefault();
        const id = $(this).find('#delete_client-group-id').val();
        $.ajax({
            type: "DELETE",
            url: `${addClientGroup}/${id}`,
            data: { id },
            dataType: "JSON",

            success: function (data) {
                console.log(data)
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
});
