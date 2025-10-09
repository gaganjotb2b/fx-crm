$(function () {
    "use strict";
    const dt_trading_accounts = $("#datatable-trading-accounts");
    // DataTable with buttons
    // --------------------------------------------------------------------
    if (dt_trading_accounts.length) {
        const dt_basic = dt_trading_accounts.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${traders}?op=trader-data-table`,
                data: function (d) {
                    return $.extend({}, d, {
                        info: $("#info").val(),
                        trading_acc: $("#trading_acc").val(),
                    });
                },
            },
            columns: [
                { data: "id" }, // used for sorting so will hide this column
                { data: "name" },
                { data: "account_category" },
                { data: "group_name" },
                { data: "email" },
                { data: "server" },
            ],
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                },
            ],
            order: [[0, "desc"]],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            language: {
                paginate: {
                    previous: "&nbsp;",
                    next: "&nbsp;",
                },
            },
            drawCallback: function (settings) {
                $("#filterBtn").html("FILTER");
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            },
        });
        // Filter operation
        $("#btn-filter").on("click", function (e) {
            dt_basic.draw();
        });
        // reset operation
        $("#btn-reset").on("click", function (e) {
            $(".start_date").val("");
            $(".end_date").val("");
            $("#filter-form").trigger("reset");
            dt_basic.draw();
        });
    }

    // showing trader Details
    $(document).on("click", ".dt-description", function (params) {
        let __this = $(this);
        let userId = $(this).data("userid");
        let accountId = $(this).data("accountid");

        $.ajax({
            url: traders,
            type: "POST",
            dataType: "json",
            data: {
                op: "trader-description",
                userId: userId,
                accountId: accountId,
            },
            success: function (data) {
                if (data.status == true) {
                    if (
                        $(__this).closest("tr").next().hasClass("description")
                    ) {
                        $(__this).closest("tr").next().remove();
                        $(__this)
                            .find(".w")
                            .html(feather.icons["plus"].toSvg());
                    } else {
                        $(__this).closest("tr").after(data.description);
                        $(__this)
                            .closest("tr")
                            .next(".description")
                            .slideDown("slow")
                            .delay(5000);
                        $(__this)
                            .find(".w")
                            .html(feather.icons["minus"].toSvg());
                    }

                    // Inner datatable for comments
                    if (
                        $(__this).closest("tr").next("tr").find(".comment")
                            .length
                    ) {
                        $(__this)
                            .closest("tr")
                            .next("tr")
                            .find(".comment")
                            .DataTable()
                            .clear()
                            .destroy();
                        var dt_comment = $(__this)
                            .closest("tr")
                            .next("tr")
                            .find(".comment")
                            .DataTable({
                                processing: true,
                                serverSide: true,
                                searching: false,
                                lengthChange: false,
                                ajax: {
                                    url: traders,
                                    type: "POST",
                                    data: {
                                        op: "trader-comment",
                                        userId: userId,
                                    },
                                },
                                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                                columns: [
                                    { data: "date" },
                                    { data: "comment" },
                                    { data: "actions", orderable: false },
                                ],
                                order: [[1, "desc"]],
                                language: {
                                    paginate: {
                                        previous: "&nbsp;",
                                        next: "&nbsp;",
                                    },
                                },
                                drawCallback: function (settings) {
                                    var rows = this.fnGetData();
                                    console.log(rows);
                                    if (rows.length !== 0) {
                                        feather.replace();
                                    }
                                },
                            });
                    }
                }
            },
        });
    });

    //  Comments report
    $(document).on("click", ".comment-tab-fill", function () {
        let trader_id = $(this).data("id");
        if ($(this).closest("tr").find(".comment").length) {
            console.log($(this));
            $(this)
                .closest("tr")
                .find(".comment")
                .DataTable()
                .clear()
                .destroy();
            var dt_comment = $(this)
                .closest("tr")
                .find(".comment")
                .DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    lengthChange: false,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    // ajax: {
                    //     url:
                    //         "/admin/client-management/trader-admin-dt-comment-fetch-data/" +
                    //         trader_id,
                    // },
                    ajax: {
                        url: traders,
                        type: "POST",
                        data: {
                            op: "trader-comment",
                            userId: trader_id,
                        },
                    },
                    columns: [
                        { data: "Commented Date", orderable: false },
                        { data: "comment", orderable: false },
                        { data: "actions", orderable: false },
                    ],
                    order: [[1, "desc"]],
                    language: {
                        paginate: {
                            previous: "&nbsp;",
                            next: "&nbsp;",
                        },
                    },
                    drawCallback: function (settings) {
                        var rows = this.fnGetData();
                        console.log(rows);
                        if (rows.length !== 0) {
                            feather.replace();
                        }
                    },
                });
        }
    });

    // Add New Comment
    var comment_table_obj;
    $(document).on("click", ".btn-add-comment", function () {
        comment_table_obj = $(this).closest("tr").find(".comment");
        $(".comment-to").html($(this).data("name"));
        $("#trader-id").val($(this).data("id"));
    });

    $("#primary").on("submit", "#form-add-comment", function (e) {
        e.preventDefault();

        const data = $(this).serialize();
        let formData = $(this).serialize() + "&op=create-comment";
        $.ajax({
            url: traders,
            type: "POST",
            data: formData,

            success: function (data) {
                $("#form-add-comment .error").text("");
                if (data.status == "success") {
                    toastr["success"](data.msg, "Add Comment", {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    comment_table_obj.DataTable().draw();
                    $(".modal").modal("hide");
                    $("#form-add-comment").trigger("reset");
                } else if (data.status == "failed") {
                    toastr["error"](data.msg, "Add Comment", {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
            },
            error: function (data) {
                $("#form-add-comment .error").text("");
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $("#form-add-comment")
                        .find("[name=" + field_name + "]")
                        .closest(".form-element")
                        .after(
                            `<span class="error text-danger">${error}</span>`
                        );
                });
            },
        });
    });

    // update comment
    // --------------------------------------------------------------------
    // get quil data into form
    $(document).on("click", ".btn-update-comment", function () {
        console.log($(this));
        $(".comment-to").html($(this).data("name"));
        $("#trader-id-update").val($(this).data("id"));
        $("#comment-id").val($(this).data("commentid"));
        $("#text_quill_update").html($(this).data("comment"));
        comment_table_obj = $(this).closest(".description").find(".comment");
    });

    $("#comment-edit").on("submit", "#form-update-comment", function (e) {
        e.preventDefault();
        let formData = $(this).serialize() + "&op=update-comment";
        $.ajax({
            url: traders,
            type: "PUT",
            data: formData,

            success: function (data) {
                $("#form-update-comment .error").text("");
                if (data.status == "success") {
                    toastr["success"](data.msg, "Update Comment", {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    comment_table_obj.DataTable().draw();
                    $(".modal").modal("hide");
                    $("#form-update-comment").trigger("reset");
                } else if (data.status == "failed") {
                    toastr["error"](data.msg, "Update Comment", {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
            },
            error: function (data) {
                $("#form-update-comment .error").text("");
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $("#form-update-comment")
                        .find("[name=" + field_name + "]")
                        .closest(".form-element")
                        .after(
                            `<span class="error text-danger">${error}</span>`
                        );
                });
            },
        });
    });

    // delete comment
    var comment_table_obj;
    $(document).on("click", ".btn-delete-comment", function () {
        let id = $(this).data("id");
        comment_table_obj = $(this).closest(".description").find(".comment");
        Swal.fire({
            icon: "warning",
            title: "Are you sure? to delete this!",
            html: "If you want to permanently delete this comment please click OK, otherwise simply click cancel",

            showCancelButton: true,
            customClass: {
                confirmButton: "btn btn-warning",
                cancelButton: "btn btn-danger",
            },
            closeOnCancel: false,
            closeOnConfirm: false,
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    url: traders,
                    method: "DELETE",
                    dataType: "json",
                    data: { id: id, op: "delete-comment" },
                    success: function (data) {
                        if (data.status === true) {
                            toastr["success"](data.message, "Delete Comment", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            comment_table_obj.DataTable().draw();
                        } else {
                            toastr["success"](data.message, "Delete Comment", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                        }
                    },
                });
            } //ending if condition
        }); //ending swite alert
    });

    // change group
    $(document).on("click", ".btn-change-group", function () {
        const accountId = $(this).data("accountid");
        $("#change-group").find(".trader-account-id").val(accountId);
        $("#change-group").modal("show");
    });

    $("#change-group").on("submit", "#change-group-form", function (e) {
        e.preventDefault();
        let formData = $(this).serialize() + "&op=change-group";
        $.ajax({
            url: traders,
            type: "PUT",
            data: formData,

            success: function (data) {
                $("#change-group-form .error").text("");
                if (data.status == true) {
                    notify("success", data.message, "Success!");
                    // comment_table_obj.DataTable().draw();
                    $(".modal").modal("hide");
                    $("#change-group-form").trigger("reset");
                } else if (data.status == false) {
                    notify("error", data.message, "Failed!");
                }
            },
            error: function (data) {
                $("#change-group-form .error").text("");
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $("#change-group-form")
                        .find("[name=" + field_name + "]")
                        .closest(".form-element")
                        .after(
                            `<span class="error text-danger">${error}</span>`
                        );
                });
            },
        });
    });

    // Resend Account Credentials
    $(document).on("click", ".btn-resend-accinfo", function () {
        const accountId = $(this).data("accountid");
        Swal.fire({
            icon: "warning",
            title: "Resend Account Credentials!",
            html:
                "Are you confirm to Resend Account Credentials of Account <b>#" +
                $(this).data("accountno") +
                "</b>",
            showCancelButton: true,
            customClass: {
                confirmButton: "btn btn-warning",
                cancelButton: "btn btn-danger",
            },
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $("#send-mail-pass").modal("show");
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    url: traders,
                    method: "POST",
                    dataType: "json",
                    data: {
                        op: "resend-account-credentials-mail",
                        account_id: accountId,
                    },
                    success: function (inner_data) {
                        if (inner_data.status === true) {
                            toastr["success"](inner_data.message, "Mail send", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            $("#send-mail-pass").modal("hide");
                        } else if (inner_data.status === false) {
                            toastr["error"](inner_data.message, "Mail send", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            $("#send-mail-pass").modal("hide");
                        }
                    },
                });
            }
        });
    });

    // remove from trader
    $(document).on("click", ".btn-remove-from-trader", function () {
        const accountId = $(this).data("accountid");
        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "If you want to remove from trader please click OK, otherwise simply click cancel",

            showCancelButton: true,
            customClass: {
                confirmButton: "btn btn-warning",
                cancelButton: "btn btn-danger",
            },
            closeOnCancel: false,
            closeOnConfirm: false,
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    url: traders,
                    method: "POST",
                    dataType: "json",
                    data: { accountId: accountId, op: "remove-from-trader" },
                    success: function (data) {
                        if (data.status === true) {
                            toastr["success"](
                                data.message,
                                "Remove From Trader",
                                {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    closeButton: true,
                                    tapToDismiss: false,
                                    progressBar: true,
                                    timeOut: 2000,
                                }
                            );
                            $(".btn-remove-from-trader").addClass("d-none");
                            $(".btn-add-as-trader").removeClass("d-none");
                        } else {
                            notify(
                                "error",
                                "The remove from trader operation failed, please try again later.",
                                "Remove from trader operation failed!"
                            );
                        }
                    },
                });
            } //ending if condition
        }); //ending swite alert
    });

    // add as a trader
    $(document).on("click", ".btn-add-as-trader", function () {
        const accountId = $(this).data("accountid");
        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "If you want to add as a trader please click OK, otherwise simply click cancel",

            showCancelButton: true,
            customClass: {
                confirmButton: "btn btn-warning",
                cancelButton: "btn btn-danger",
            },
            closeOnCancel: false,
            closeOnConfirm: false,
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    url: traders,
                    method: "POST",
                    dataType: "json",
                    data: { accountId: accountId, op: "add-as-trader" },
                    success: function (data) {
                        if (data.status === true) {
                            toastr["success"](data.message, "Add As Trader", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            $(".btn-add-as-trader").addClass("d-none");
                            $(".btn-remove-from-trader").removeClass("d-none");
                        } else {
                            notify(
                                "error",
                                "Add as a trader operation failed, please try again later.",
                                "Add as a trader operation failed!"
                            );
                        }
                    },
                });
            } //ending if condition
        }); //ending swite alert
    });

    // change leverage
    $(document).on("click", ".btn-change-leverage", function () {
        const accountId = $(this).data("accountid");
        $.ajax({
            url: traders,
            type: "POST",
            data: { accountId: accountId, op: "get-leverage-data" },
            success: function (data) {
                $("#change-leverage .leverage").html(data);
            },
        });
        $("#change-leverage").find(".trader-account-id").val(accountId);
        $("#change-leverage").modal("show");
    });
    $("#change-leverage").on("submit", "#change-leverage-form", function (e) {
        e.preventDefault();
        let formData = $(this).serialize() + "&op=change-leverage";
        $.ajax({
            url: traders,
            type: "PUT",
            data: formData,

            success: function (data) {
                $("#change-leverage-form .error").text("");
                if (data.status == true) {
                    notify("success", data.message, "Success!");
                    $(".modal").modal("hide");
                    $("#change-leverage-form").trigger("reset");
                } else if (data.status == false) {
                    notify("error", data.message, "Success!");
                }
            },
            error: function (data) {
                $("#change-leverage-form .error").text("");
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $("#change-leverage-form")
                        .find("[name=" + field_name + "]")
                        .closest(".form-element")
                        .after(
                            `<span class="error text-danger">${error}</span>`
                        );
                });
            },
        });
    });

    // create live account
    $(document).on("click", ".btn-create-live-account", function () {
        const accountId = $(this).data("accountid");
        $("#create-live-account .trader-account-id").val(accountId);
        $("#create-live-account").modal("show");
        $("#create-live-account").on("change", "[name=platform]", function () {
            const platform = $(this).val();
            $.ajax({
                url: traders,
                type: "POST",
                data: {
                    platform: platform,
                    op: "get-data-for-live-account",
                    type: "group-data",
                },
                success: function (data) {
                    $("#create-live-account [name=group_id]").html(data);
                },
            });
        });

        $("#create-live-account").on("change", "[name=group_id]", function () {
            const group = $("#create-live-account [name=group_id]").val();
            $.ajax({
                url: traders,
                type: "POST",
                data: {
                    group: group,
                    op: "get-data-for-live-account",
                    type: "leverage-data",
                },
                success: function (data) {
                    $("#create-live-account [name=leverage]").html(data);
                },
            });
        });
    });

    $("#create-live-account").on(
        "submit",
        "#create-live-account-form",
        function (e) {
            e.preventDefault();
            let formData = $(this).serialize() + "&op=create-live-account";
            $.ajax({
                url: traders,
                type: "PUT",
                data: formData,

                success: function (data) {
                    $("#create-live-account-form .error").text("");
                    if (data.status == true) {
                        notify("success", data.message, "Success!");
                        $(".modal").modal("hide");
                        $("#create-live-account-form").trigger("reset");

                        $("#send-mail-pass").modal("show");
                        $.ajax({
                            url: traders,
                            method: "POST",
                            dataType: "json",
                            data: {
                                op: "resend-account-credentials-mail",
                                account_id: data.accountId,
                            },
                            success: function (inner_data) {
                                if (inner_data.status === true) {
                                    notify("success", inner_data.message, "Mail send!");
                                    $("#send-mail-pass").modal("hide");
                                } else if (inner_data.status === false) {
                                    notify("error", inner_data.message, "Mail send!");
                                    $("#send-mail-pass").modal("hide");
                                }
                            },
                        });
                    } else if (data.status == false) {
                        notify("error", data.message, "Error!");
                    }
                },
                error: function (data) {
                    $("#create-live-account-form .error").text("");
                    $.each(
                        data.responseJSON.errors,
                        function (field_name, error) {
                            $("#create-live-account-form")
                                .find("[name=" + field_name + "]")
                                .closest(".form-element")
                                .after(
                                    `<span class="error text-danger">${error}</span>`
                                );
                        }
                    );
                },
            });
        }
    );
});
