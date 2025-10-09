$(function () {
    "use strict";

    var dt_basic_table = $(".datatables-basic");

    // DataTable with buttons
    // --------------------------------------------------------------------

    if (dt_basic_table.length) {
        var dt_basic = dt_basic_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: ibGroupData,
            columns: [
                { data: "responsive_id" },
                { data: "id" }, // used for sorting so will hide this column
                {
                    data: "group_name",
                },
                {
                    data: "status",
                },
                {
                    data: "created_at",
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
                            `<a href="#" class="ib-group-edit me-1" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#edit-ib-group">` +
                            feather.icons["edit"].toSvg({
                                class: "font-small-4",
                            }) +
                            "</a>" +
                            `<a href="javascript:;" class="ib-group-delete" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#delete-ib-group">` +
                            feather.icons["trash"].toSvg({
                                class: "font-small-4",
                            }) +
                            "</a>"
                        );
                    },
                },
            ],
            order: [[1, "desc"]],
            dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            buttons: [
                {
                    text:
                        feather.icons["plus"].toSvg({
                            class: "me-50 font-small-4",
                        }) + "Add New Ib Group",
                    className: "create-new btn btn-primary",
                    attr: {
                        "data-bs-toggle": "modal",
                        "data-bs-target": "#add-ib-group",
                    },
                    init: function (api, node, config) {
                        $(node).removeClass("btn-secondary");
                        $(node).parent().removeClass("btn-group");
                        setTimeout(function () {
                            $(node)
                                .closest(".dt-buttons")
                                .removeClass("btn-group")
                                .addClass("d-inline-flex");
                        }, 50);
                    },
                },
            ],
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
        // $("div.head-label").html(
        //     '<h6 class="mb-0">DataTable with Buttons</h6>'
        // );
    }
    // Add New record
    $("#add-ib-group").on("submit", "form", function (e) {
        e.preventDefault();

        const data = $(this).serialize();
        console.log(data);
        $.ajax({
            type: "POST",
            url: addIbGroup,
            data: data,
            dataType: "JSON",

            success: function (data) {
                if (data.status == "success") {
                    $(".add-new-ib-group .error").text("");
                    Swal.fire("Success!", `${data.msg}`, "success");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                    $("#add-ib-group .add-new-ib-group").trigger("reset");
                } else if (data.status == "failed") {
                    Swal.fire("Failed!", `${data.msg}`, "error");
                }
            },
            error: function (data) {
                $(".add-new-ib-group .error").text("");
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $(".add-new-ib-group")
                        .find("[name=" + field_name + "]")
                        .closest(".form-element")
                        .after(
                            `<span class="error text-danger">${error}</span>`
                        );
                });
            },
        });
    });

    // Edit Record
    $(".datatables-basic tbody").on("click", ".ib-group-edit", function () {
        const id = $(this).data("id");
        $.ajax({
            url: `${addIbGroup}/${id}`,
            data: { id },
            dataType: "JSON",
            success: function (data) {
                $("#edit-ib-group #edit_ib-group-id").val(data.id);
                $("#edit-ib-group #edit_status").val(data.status).change();
                $("#edit-ib-group #edit_group-name").val(data.group_name);
            },
        });
    });

    $("#edit-ib-group").on("submit", "form", function (e) {
        e.preventDefault();
        const data = $(this).serialize();
        const id = $(this).find("#edit_ib-group-id").val();
        $.ajax({
            type: "PUT",
            url: `${addIbGroup}/${id}`,
            data: data,
            dataType: "JSON",

            success: function (data) {
                console.log(data);
                if (data.status == "success") {
                    $(".edit-ib-group-form .error").text("");
                    Swal.fire("Success!", `${data.msg}`, "success");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                } else if (data.status == "failed") {
                    Swal.fire("Failed!", `${data.msg}`, "error");
                }
            },
            error: function (data) {
                $(".edit-ib-group-form .error").text("");
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $(".edit-ib-group-form")
                        .find("[name=" + field_name + "]")
                        .closest(".form-element")
                        .after(
                            `<span class="error text-danger">${error}</span>`
                        );
                });
            },
        });
    });

    // Delete Record

    $(".datatables-basic tbody").on("click", ".ib-group-delete", function () {
        $("#delete-ib-group #delete_ib-group-id").val($(this).data("id"));
    });

    $("#delete-ib-group").on("submit", "form", function (e) {
        e.preventDefault();
        const id = $(this).find("#delete_ib-group-id").val();
        $.ajax({
            type: "DELETE",
            url: `${addIbGroup}/${id}`,
            data: { id },
            dataType: "JSON",

            success: function (data) {
                console.log(data);
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
