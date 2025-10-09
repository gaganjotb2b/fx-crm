$(function () {
    "use strict";
    const dt_trading_accounts = $("#datatable-trading-accounts");
    // DataTable with buttons
    // --------------------------------------------------------------------
    // if (dt_trading_accounts.length) {
    const dt_basic = dt_trading_accounts.DataTable({
        processing: true,
        serverSide: true,
        buttons: true,
        dom: 'B<"clear">lfrtip',
        buttons: [
            {
                extend: "csv",
                text: "csv",
                className: "btn btn-success btn-sm",
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                },
                action: serverSideButtonAction,
            },
            {
                extend: "excel",
                text: "excel",
                className: "btn btn-warning btn-sm",
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                },
                action: serverSideButtonAction,
            },
        ],
        ajax: {
            url: `${traders}?op=trader-data-table`,
            data: function (d) {
                return $.extend({}, d, {
                    finance: $("#finance").val(),
                    platform: $("#platform").val(),
                    verification_status: $("#verification-status").val(),
                    info: $("#info").val(),
                    manager: $("#manager").val(),
                    ib_info: $("#ib_info").val(),
                    leverage: $("#leverage").val(),
                    trading_acc: $("#trading_acc").val(),
                });
            },
        },
        columns: [
            { data: "id" }, // used for sorting so will hide this column
            { data: "account_number" },
            { data: "leverage" },
            { data: "group_name" },
            { data: "email" },
            { data: "server" },
            { data: "approve_status" },
        ],
        columnDefs: [
            {
                targets: 0,
                visible: false,
            },
        ],
        order: [[0, "desc"]],
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
    // }
    // data table export function --------------------------------------
    $(document).on("change", "#fx-export", function () {
        if ($(this).val() === "csv") {
            console.log("HI");
            $(".buttons-csv").trigger("click");
        }
        if ($(this).val() === "excel") {
            console.log($(this).val());

            $(".buttons-excel").trigger("click");
        }
    });

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

                    // Inner datatable for deposits
                    if (
                        $(__this).closest("tr").next("tr").find(".deposit")
                            .length
                    ) {
                        $(__this)
                            .closest("tr")
                            .next("tr")
                            .find(".deposit")
                            .DataTable()
                            .clear()
                            .destroy();
                        var dt_deposit = $(__this)
                            .closest("tr")
                            .next("tr")
                            .find(".deposit")
                            .DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: traders,
                                    type: "POST",
                                    data: {
                                        op: "trader-deposit",
                                        userId: userId,
                                        accountId: accountId,
                                    },
                                },
                                columns: [
                                    { data: "id" }, // used for sorting so will hide this column
                                    { data: "SL" },
                                    { data: "amount" },
                                    { data: "type" },
                                    { data: "status" },
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
                            });
                    }
                }
            },
        });
    });

    //  withdraw report
    $(document).on("click", ".withdraw-tab-fill", function () {
        const userId = $(this).data("id");
        const accountId = $(this).data("accountid");

        if ($(this).closest("tr").find(".withdraw").length) {
            $(this)
                .closest("tr")
                .find(".withdraw")
                .DataTable()
                .clear()
                .destroy();
            var dt_withdraw = $(this)
                .closest("tr")
                .find(".withdraw")
                .DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: traders,
                        type: "POST",
                        data: {
                            op: "trader-withdraw",
                            userId: userId,
                            accountId: accountId,
                        },
                    },
                    columns: [
                        { data: "id" }, // used for sorting so will hide this column
                        { data: "SL" },
                        { data: "amount" },
                        { data: "type" },
                        { data: "status" },
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
                });
        }
    });

    //  bonus report
    $(document).on("click", ".bonus-tab-fill", function () {
        const userId = $(this).data("id");
        const accountId = $(this).data("accountid");

        if ($(this).closest("tr").find(".bonus").length) {
            $(this).closest("tr").find(".bonus").DataTable().clear().destroy();
            var dt_bonus = $(this)
                .closest("tr")
                .find(".bonus")
                .DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: traders,
                        type: "POST",
                        data: {
                            op: "trader-bonus",
                            userId: userId,
                            accountId: accountId,
                        },
                    },
                    columns: [
                        { data: "id" }, // used for sorting so will hide this column
                        { data: "SL" },
                        { data: "bonus_amount" },
                        { data: "pkg_name" },
                        { data: "end_date" },
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
                });
        }
    });

    //  trades report
    $(document).on("click", ".trade-tab-fill", function () {
        const accountId = $(this).data("accountid");

        if ($(this).closest("tr").find(".trade").length) {
            $(this).closest("tr").find(".trade").DataTable().clear().destroy();
            var dt_trade = $(this)
                .closest("tr")
                .find(".trade")
                .DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: traders,
                        type: "POST",
                        data: {
                            op: "trade-list",
                            accountId: accountId,
                        },
                    },
                    columns: [
                        { data: "ticket" },
                        { data: "account_no" },
                        { data: "symbol" },
                        { data: "volume" },
                        { data: "open_time" },
                        { data: "close_time" },
                        { data: "open_price" },
                        { data: "close_price" },
                        { data: "profit" },
                    ],
                    order: [[0, "desc"]],
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        paginate: {
                            previous: "&nbsp;",
                            next: "&nbsp;",
                        },
                    },
                });
        }
    });

    // Action or security setttings
    // operation block/unblock
    $(document).on("change", ".block-unblock-swtich", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = "Are you sure? to Block this user!";
            warning_msg =
                "If you want to Block this User please click OK, otherwise simply click cancel";
            request_for = "block";
        } else if ($(this).is(":not(:checked)")) {
            warning_title = "Are you sure? to Unblock this user!";
            warning_msg =
                "If you want to Unblock this User please click OK, otherwise simply click cancel";
            request_for = "unblock";
        }
        Swal.fire({
            icon: "warning",
            title: warning_title,
            html: warning_msg,

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
                    method: "POST",
                    dataType: "json",
                    data: {
                        op: "block-unblock",
                        id: id,
                        request_for: request_for,
                    },
                    success: function (data) {
                        if (data.status === true) {
                            toastr["success"](
                                data.message,
                                data.success_title,
                                {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    closeButton: true,
                                    tapToDismiss: false,
                                    progressBar: true,
                                    timeOut: 2000,
                                }
                            );
                        } else {
                            toastr["error"](data.message, data.success_title, {
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
            } else {
                if ($(this).is(":checked")) {
                    $(this).prop("checked", false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop("checked", true);
                }
            }
        }); //ending swite alert
    });

    // enable / disable IB Commission Operation
    $(document).on("change", ".ib-commission-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        console.log(id);
        if ($(this).is(":checked")) {
            warning_title = "Are you sure? to Enable IB Commission Operation!";
            warning_msg =
                "If you want to Enable IB Commission Operation. please click OK, otherwise simply click cancel";
            request_for = "enable";
        } else if ($(this).is(":not(:checked)")) {
            warning_title = "Are you sure? to Disable IB Commission Operation!";
            warning_msg =
                "If you want to Disable IB Commission Operation. please click OK, otherwise simply click cancel";
            request_for = "disable";
        }
        console.log(request_for);
        Swal.fire({
            icon: "warning",
            title: warning_title,
            html: warning_msg,

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
                    // url: "/admin/client-management/trader-admin-google-two-step",
                    method: "POST",
                    dataType: "json",
                    data: {
                        op: "ib-commission-operation-trader",
                        id: id,
                        request_for: request_for,
                    },
                    success: function (data) {
                        if (data.status === true) {
                            notify(
                                "success",
                                data.message,
                                "IB Commission Operation"
                            );
                        } else {
                            notify(
                                "error",
                                data.message,
                                "IB Commission Operation"
                            );
                        }
                    },
                });
            } else {
                if ($(this).is(":checked")) {
                    $(this).prop("checked", false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop("checked", true);
                }
            }
        }); //ending swite alert
    });

    // Enable / Disable deposit operation
    $(document).on("change", ".deposit-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = "Are you sure? to Enable Deposit Operation!";
            warning_msg =
                "If you want to Enable Deposit Operation. please click OK, otherwise simply click cancel";
            request_for = "enable";
        } else if ($(this).is(":not(:checked)")) {
            warning_title = "Are you sure? to Disable Deposit Operation!";
            warning_msg =
                "If you want to Disable Deposit Operation. Please click OK, otherwise simply click cancel";
            request_for = "disable";
        }
        Swal.fire({
            icon: "warning",
            title: warning_title,
            html: warning_msg,

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
                    method: "POST",
                    dataType: "json",
                    data: {
                        op: "deposit-operation-trader",
                        id: id,
                        request_for: request_for,
                    },
                    success: function (data) {
                        if (data.status === true) {
                            notify("success", data.message, data.success_title);
                        } else {
                            notify("error", data.message, data.success_title);
                        }
                    },
                });
            } else {
                if ($(this).is(":checked")) {
                    $(this).prop("checked", false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop("checked", true);
                }
            }
        }); //ending swite alert
    });

    // Enable / Disable withdraw operation
    $(document).on("change", ".withdraw-switch", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        if ($(this).is(":checked")) {
            warning_title = "Are you sure? to Enable Withdraw Operation!";
            warning_msg =
                "If you want to Enable Withdraw Operation. please click OK, otherwise simply click cancel";
            request_for = "enable";
        } else if ($(this).is(":not(:checked)")) {
            warning_title = "Are you sure? to Disable Withdraw Operation!";
            warning_msg =
                "If you want to Disable Withdraw Operation. Please click OK, otherwise simply click cancel";
            request_for = "disable";
        }
        Swal.fire({
            icon: "warning",
            title: warning_title,
            html: warning_msg,

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
                    method: "POST",
                    dataType: "json",
                    data: {
                        op: "withdraw-operation-trader",
                        id: id,
                        request_for: request_for,
                    },
                    success: function (data) {
                        if (data.status === true) {
                            notify("success", data.message, data.success_title);
                        } else {
                            notify("error", data.message, data.success_title);
                        }
                    },
                });
            } else {
                if ($(this).is(":checked")) {
                    $(this).prop("checked", false);
                } else if ($(this).is(":not(:checked)")) {
                    $(this).prop("checked", true);
                }
            }
        }); //ending swite alert
    });


    // change investor password
    $(document).on("click", ".change-investor-password-btn", function () {
        $("#password-change-modal .trader-account-id").val(
            $(this).data("accountid")
        );
        $("#password-change-modal .password-change-op").val(
            "investor-password"
        );
    });

    $(document).on("submit", "#change-password-form", function (event) {
        let formData = $(this).serialize() + "&op=change-password";
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            method: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if (data.status === true) {
                    // success toaster
                    toastr["success"](data.message, "Password change", {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                        // rtl: isRtl
                    });
                    $("#send-mail-pass").modal("show");
                    $("#password-change-modal").modal("toggle");
                    // sending mail
                    const op =
                        data.op == "master-password"
                            ? "change-master-password-mail"
                            : "change-investor-password-mail";
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        data: {
                            op: op,
                            trader_account_id: data.traderAccountId,
                        },
                        success: function (inner_data) {
                            if (inner_data.status === true) {
                                toastr["success"](
                                    inner_data.message,
                                    "Mail send",
                                    {
                                        showMethod: "slideDown",
                                        hideMethod: "slideUp",
                                        closeButton: true,
                                        tapToDismiss: false,
                                        progressBar: true,
                                        timeOut: 2000,
                                    }
                                );
                                $("#send-mail-pass").modal("hide");
                            } else if (inner_data.status === false) {
                                toastr["error"](
                                    inner_data.message,
                                    "Mail send",
                                    {
                                        showMethod: "slideDown",
                                        hideMethod: "slideUp",
                                        closeButton: true,
                                        tapToDismiss: false,
                                        progressBar: true,
                                        timeOut: 2000,
                                    }
                                );
                                $("#send-mail-pass").modal("hide");
                            }
                        },
                    });
                } else {
                    notify(
                        "error",
                        inner_data.message,
                        "Update operation failed!"
                    );
                }
            },
            error: function (data) {
                console.log(data);
                $("#change-password-form .error").text("");
                $.each(data.responseJSON.errors, function (field_name, error) {
                    $("#change-password-form")
                        .find("[name=" + field_name + "]")
                        .closest("div")
                        .after(
                            `<span class="error text-danger">${error}</span>`
                        );
                });
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
                        { data: "commented_date", orderable: false },
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
                            toastr["error"](data.message, "Delete Comment", {
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
    $(document).on('click', '#save-change-group-btn', function () {
        $(this).prop('disabled', true).html($(this).data('loading'));
    })
    // $("#change-group").on("submit", "#change-group-form", function (e) {
    //     e.preventDefault();
    //     let formData = $(this).serialize() + "&op=change-group";
    //     $.ajax({
    //         url: traders,
    //         type: "PUT",
    //         data: formData,

    //         success: function (data) {
    //             $("#change-group-form .error").text("");
    //             if (data.status == true) {
    //                 notify("success", data.message, "Success!");
    //                 // comment_table_obj.DataTable().draw();
    //                 $(".modal").modal("hide");
    //                 dt_basic.draw();
    //                 $("#change-group-form").trigger("reset");
    //             } else if (data.status == false) {
    //                 notify("error", data.message, "Failed!");
    //             }
    //             $('#save-change-group-btn').prop('disabled', false).html('Save');
    //         },
    //         error: function (data) {
    //             $("#change-group-form .error").text("");
    //             $.each(data.responseJSON.errors, function (field_name, error) {
    //                 $("#change-group-form")
    //                     .find("[name=" + field_name + "]")
    //                     .closest(".form-element")
    //                     .after(
    //                         `<span class="error text-danger">${error}</span>`
    //                     );
    //             });
    //         },
    //     });
    // });

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
                            setTimeout(function () {
                                location.reload(true);
                            }, 4000);
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

    // // add as a trader
    // $(document).on("click", ".btn-add-as-trader", function () {
    //     const accountId = $(this).data("accountid");
    //     Swal.fire({
    //         icon: "warning",
    //         title: "Are you sure?",
    //         html: "If you want to add as a trader please click OK, otherwise simply click cancel",

    //         showCancelButton: true,
    //         customClass: {
    //             confirmButton: "btn btn-warning",
    //             cancelButton: "btn btn-danger",
    //         },
    //         closeOnCancel: false,
    //         closeOnConfirm: false,
    //     }).then((willDelete) => {
    //         if (willDelete.isConfirmed) {
    //             $.ajaxSetup({
    //                 headers: {
    //                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
    //                         "content"
    //                     ),
    //                 },
    //             });
    //             $.ajax({
    //                 url: traders,
    //                 method: "POST",
    //                 dataType: "json",
    //                 data: { accountId: accountId, op: "add-as-trader" },
    //                 success: function (data) {
    //                     if (data.status === true) {
    //                         toastr["success"](data.message, "Add As Trader", {
    //                             showMethod: "slideDown",
    //                             hideMethod: "slideUp",
    //                             closeButton: true,
    //                             tapToDismiss: false,
    //                             progressBar: true,
    //                             timeOut: 2000,
    //                         });
    //                         $(".btn-add-as-trader").addClass("d-none");
    //                         $(".btn-remove-from-trader").removeClass("d-none");
    //                     } else {
    //                         notify(
    //                             "error",
    //                             "Add as a trader operation failed, please try again later.",
    //                             "Add as a trader operation failed!"
    //                         );
    //                     }
    //                 },
    //             });
    //         } //ending if condition
    //     }); //ending swite alert
    // });

    // change leverage
    $(document).on("click", ".btn-change-leverage", function () {
        const accountId = $(this).data("accountid");
        console.log(accountId);
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
    $(document).on('click', '#save-change-leverage-btn', function () {
        $(this).prop('disabled', true).html($(this).data('loading'));
    });
    // $("#change-leverage").on("submit", "#change-leverage-form", function (e) {
    //     e.preventDefault();
    //     let formData = $(this).serialize() + "&op=change-leverage";
    //     $.ajax({
    //         url: traders,
    //         type: "PUT",
    //         data: formData,

    //         success: function (data) {
    //             $("#change-leverage-form .error").text("");
    //             if (data.status == true) {
    //                 notify("success", data.message, "Success!");
    //                 $(".modal").modal("hide");
    //                 $("#change-leverage-form").trigger("reset");
    //                 dt_basic.draw();
    //             } else if (data.status == false) {
    //                 notify("error", data.message, "Success!");
    //             }
    //             $('#save-change-leverage-btn').prop('disabled', false).html('Save');
    //         },
    //         error: function (data) {
    //             $("#change-leverage-form .error").text("");
    //             $.each(data.responseJSON.errors, function (field_name, error) {
    //                 $("#change-leverage-form")
    //                     .find("[name=" + field_name + "]")
    //                     .closest(".form-element")
    //                     .after(
    //                         `<span class="error text-danger">${error}</span>`
    //                     );
    //             });
    //         },
    //     });
    // });
});
// store credit
// --------------------------------------------------------------------------------------------
$(document).on("click", ".btn-manage-credit", function () {
    let user_id = $(this).data("user");
    const accountNo = $(this).data("accountno");
    $("#change-credit").find(".trader-id").val(user_id);
    $("#change-credit").find("#credit-trading_account").val(accountNo);
    $("#change-credit")
        .find("#trading-account1")
        .val($(this).data("accountid"));
    $("#change-credit").modal("show");

    $.ajax({
        type: "GET",
        url: "/admin/finance/credit-get-trading-account/" + user_id,
        dataType: "json",
        success: function (data) {
            $("#change-credit .trader-name").html(`For - ${data.users.name}`);
        },
    });
});
submit_wait("#btn-add-credit");
function createCallBack(data) {
    if (data.status == true) {
        toastr["success"](data.message, "Credits store", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            closeButton: true,
            tapToDismiss: false,
            progressBar: true,
            timeOut: 2000,
        });
        $("#admin_credit_form")[0].reset();
        // // sending mail---------------------------------
        // Swal.fire({
        //     title: "Add credit mail",
        //     text: "Sending mail for add new credit to your account",
        //     inputAttributes: {
        //         autocapitalize: "off",
        //     },
        //     showCancelButton: true,
        //     confirmButtonText: "Send Email",
        //     showLoaderOnConfirm: true,
        //     preConfirm: (login) => {
        //         $(".swal2-html-container").text(
        //             "We Sending Email, Please Wait....."
        //         );
        //         return fetch(
        //             `/admin/finance/send-add-credit-mail/` +
        //             data.account_id +
        //             "/request/" +
        //             data.credit_id
        //         )
        //             .then((response) => {
        //                 if (!response.ok) {
        //                     throw new Error(response.statusText);
        //                 }
        //                 return response.json();
        //             })
        //             .catch((error) => {
        //                 Swal.showValidationMessage(`Request failed: ${error}`);
        //             });
        //     },
        //     allowOutsideClick: () => !Swal.isLoading(),
        // }).then((result) => {
        //     if (result.isConfirmed) {
        //         if (result.value.status == false) {
        //             toastr["error"](result.value.message, "Add Credit Email", {
        //                 showMethod: "slideDown",
        //                 hideMethod: "slideUp",
        //                 closeButton: true,
        //                 tapToDismiss: false,
        //                 progressBar: true,
        //                 timeOut: 2000,
        //             });
        //         } else {
        //             toastr["success"](
        //                 result.value.message,
        //                 "Add Credit Email",
        //                 {
        //                     showMethod: "slideDown",
        //                     hideMethod: "slideUp",
        //                     closeButton: true,
        //                     tapToDismiss: false,
        //                     progressBar: true,
        //                     timeOut: 2000,
        //                 }
        //             );
        //         }
        //     }
        // }); //<----sweet alart------------
        // $(".swal2-confirm").trigger("click");

        // end: sending mail-----------------------
    } else {
        toastr["error"](data.message, "Credits store", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            closeButton: true,
            tapToDismiss: false,
            progressBar: true,
            timeOut: 2000,
        });
    }
    $.validator("admin_credit_form", data.errors);
    submit_wait("#btn-add-credit", data.submit_wait);
}

// store fund
// --------------------------------------------------------------------------------------------
$(document).on("click", ".btn-manage-fund", function () {
    let user_id = $(this).data("user");
    const accountNo = $(this).data("accountno");
    $("#manage-fund").find(".trader-id").val(user_id);
    $("#manage-fund").find("#fund-trading_account").val(accountNo);
    $("#manage-fund").find("#trading-account2").val($(this).data("accountid"));
    $("#manage-fund").modal("show");

    $.ajax({
        type: "GET",
        url: "/admin/finance/get-client-finance/" + user_id,
        dataType: "json",
        success: function (data) {
            console.log(data);
            if (data.status == true) {
                $("#manage-fund .trader-name").html(`For - ${data.user_name}`);
            }
        },
    }); //END: get client
});
submit_wait("#submit-request");
function createFundCallBack(data) {
    if (data.status == true) {
        notify("success", data.message, "Fund Management");
        // let url = "/admin/finance/fund-management-email/" + data.account_id + "/credit/" + data.credit_id + "/transaction/" + data.type;
        // send_mail("Fund management", "Please wait while we sending email", url, true);
        $("#fund-add-form").trigger("reset");
    } else {
        notify("error", data.message, "Fund Management");
    }
    $.validator("fund-add-form", data.errors, "Fund Management");
    submit_wait("#submit-request", data.submit_wait);
}

// Check Account Balance and equity -------------------------------------------------------
$(document).on("click", ".check-account-balance", function () {
    const elements = $(this).closest('.dt-details').find(".check-account-balance");
    const accountId = $(this).data("accountid");
    [...elements].forEach((el) => {
        el.innerHTML = `<div class="loader"></div>`;
    });

    $.ajax({
        url: traders,
        type: "POST",
        data: { accountId: accountId, op: "check-account-balance" },
        success: function (data) {
            [...elements].forEach((el, i) => {
                el.innerHTML = `<span>$${i ? data.equity : data.balance}</span>`;
            });
        },
    });
});
