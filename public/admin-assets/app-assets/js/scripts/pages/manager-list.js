$(function () {

    // manager Datatable
    /*********************************************************** */
    var cd = (new Date()).toISOString().split('T')[0];
    var manager_datatable = $('#manager-list').fetch_data({
        url: "/admin/manager-settings/get-manager-datatable",
        columns: [
            { "data": "name" },
            { "data": "manager_type" },
            { "data": "group" },

            { "data": "country" },
            { "data": "status" },
            { "data": "actions" },
        ],
    });
    // Filter operation
    $("#btn-filter").on("click", function (e) {
        manager_datatable.draw();
    });

    // reset operation
    $("#btn-reset").on("click", function (e) {
        $(".start_date").val('');
        $(".end_date").val('');
        $("#filter-form").trigger('reset');
        manager_datatable.draw();
    });

    //    datatable descriptions
    // --------------------------------------------------------------------------------------------------------
    $(document).on("click", ".dt-description", function (params) {
        let __this = $(this);
        let manager = $(this).data('id');
        $.ajax({
            type: "GET",
            url: '/admin/manager-settings/get-manager-datatable-description/' + manager,
            dataType: 'json',
            success: function (data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
                        $(__this).find('.w').html(feather.icons['minus'].toSvg());

                        // Inner datatable
                        if ($(__this).closest('tr').next('.description').find('.accessible-user').length) {
                            $(__this).closest('tr').next('.description').find('.accessible-user').DataTable().clear().destroy();
                            var cd = (new Date()).toISOString().split('T')[0];
                            var users = $(__this).closest('tr').next('.description').find('.accessible-user').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "searching": false,
                                "lengthChange": false,
                                // "dom": 'Bfrtip',
                                "ajax": {
                                    "url": "/admin/manager-settings/get-manager-datatable-description-users/" + manager,
                                    "data": function (d) {
                                        return $.extend({}, d, $(__this).closest('tr').next('.description').find(".form-asigne-users").serializeObject());
                                    }
                                },
                                "columns": [
                                    { "data": "name" },
                                    { "data": "type" },
                                    { "data": "date" },
                                    { "data": "email" },
                                    { "data": "actions" },
                                ],
                                "order": [[1, 'desc']],
                                "drawCallback": function (settings) {
                                    var rows = this.fnGetData();
                                    if (rows.length !== 0) {
                                        feather.replace();
                                    }
                                }
                            });

                        }
                    }
                }
            }
        });
    });

    // Filter operation
    // ----------------------------------------------------------------
    $(document).on("click", ".btn-filter", function (e) {
        $(this).closest('.tab-pane').find('.accessible-user').DataTable().draw();
    });

    //   select all users for asigned to manager
    // ------------------------------------------------------------
    $(document).on("change", ".select-all-users", function () {
        if ($(this).is(':checked')) {
            $(this).closest('.tab-pane').find('.assigneable-user').each(function (index, __this) {
                $(__this).prop("checked", true);
            });
        }
        else {
            $(this).closest('.tab-pane').find('.assigneable-user').each(function (index, __this) {
                $(__this).prop("checked", false);
            });
        }
    });

    // save selected for asigning to managers
    // --------------------------------------------------------------------

    $(document).on("submit", ".user-assign-form", function (event) {
        let form_data = $(this).serializeArray();
        let $this = $(this);
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/admin/manager-settings/assigne-user-to-manager',
            dataType: 'json',
            data: form_data,
            success: function (data) {
                if (data.status == true) {
                    notify('success', data.message, 'Assign Clieent')
                }
                if (data.status == false) {
                    notify('error', data.message, 'Assign Clieent')
                }

                $(".btn-asigning-users").each(function (index, oabject) {
                    $(this).html('Save change').prop('disabled', false);
                })
            }
        });
    })

    // click form button and submit form
    // -----------------------------------------------------------------------------
    $(document).on("click", ".btn-asigning-users", function () {
        $(this).prop('disabled', true);
        $(this).html($(this).data('loading'));
        $(this).closest(".tab-pane").find(".user-assign-form").trigger("submit");
    });
    // when select trader ib unselected
    // assigne user to manager filter
    // -----------------------------------------------------------------------------
    $(document).on('change', ".trader-users", function () {
        if ($(this).is(':checked')) {
            $(this).closest('.row').find('.ib-users').prop('checked', false);
        }
    })
    // when ib selected, trader unselected
    // ----------------------------------------------------------------------------------
    $(document).on('change', ".ib-users", function () {
        if ($(this).is(':checked')) {
            $(this).closest('.row').find('.trader-users').prop('checked', false);
        }
    })
    // checked deselected user
    // ------------------------------------------------------------------------------
    $(document).on('change', '.assigneable-user', function () {
        if ($(this).is(':checked')) {
            $(this).closest('td').find('.deselected-user').prop('checked', false);
        }
        else {
            $(this).closest('td').find('.deselected-user').prop('checked', true);
        }
    })
    // unchecked selecte all
    // checked hidden deleted all
    // -------------------------------------------------------------------------------------
    $(document).on('change', '.select-all-users', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.tab-pane').find('.deselected-user').each(function (index, __this) {
                $(__this).prop("checked", false);
            });
        }
        else {
            $(this).closest('.tab-pane').find('.deselected-user').each(function (index, __this) {
                $(__this).prop("checked", true);
            });
        }
    });

    // datatable for ib`s
    // ib tab datatable
    // ----------------------------------------------------------------------------------------
    $(document).on('click', '.ib-tab', function () {
        let manager = $(this).data('id');
        $(this).closest('td').find('.ib').DataTable().clear().destroy();
        var datatable = $(this).closest('td').find('.ib').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "lengthChange": false,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
            ],
            "ajax": {
                "url": "/admin/manager-settings/get-manager-datatable-description-ib/" + manager,
            },
            "columns": [
                { "data": "name" },
                { "data": "user_type" },
                { "data": "joining_date" },
                { "data": "email" },
                { "data": "actions" },
            ],
            "order": [[1, 'desc']],
            "drawCallback": function (settings) {
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            }
        });
    })
    // datatable for traders`s
    // trader tab datatable
    // ----------------------------------------------------------------------------------------
    $(document).on('click', '.trader-tab', function () {
        let manager = $(this).data('id');
        $(this).closest('td').find('.trader').DataTable().clear().destroy();
        var datatable = $(this).closest('td').find('.trader').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "lengthChange": false,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
            ],
            "ajax": {
                "url": "/admin/manager-settings/get-manager-datatable-description-trader/" + manager,
            },
            "columns": [
                { "data": "name" },
                { "data": "user_type" },
                { "data": "joining_date" },
                { "data": "email" },
                { "data": "actions" },
            ],
            "order": [[1, 'desc']],
            "drawCallback": function (settings) {
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            }
        });
    });
    // manager tab
    // datatable----------------------------------------------------------------------------
    var datatable_manager_in;
    $(document).on('click', '.manager-tab', function () {
        let __this = $(this);
        let manager = $(this).data('id');
        $(this).closest('td').find('.datatable-inner.manager').DataTable().clear().destroy();
        datatable_manager_in = $(this).closest('td').find('.datatable-inner.manager').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "lengthChange": false,
            "buttons": true,
            // "dom": 'B<"clear">lfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
            ],
            "ajax": {
                "url": "/admin/manager-settings/get-manager-datatable-description-manager/" + manager,
                "data": function (d) {
                    return $.extend({}, d, $(__this).closest('tr').find(".inseide-filterform.manager-filter").serializeObject());
                }
            },
            "columns": [
                { "data": "name" },
                { "data": "user_type" },
                { "data": "joining_date" },
                { "data": "email" },
                { "data": "actions" },
            ],
            "order": [[1, 'desc']],
            "drawCallback": function (settings) {
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            }
        });
        // $(datatable_manager_in.closest('form').find('.btn-filter-manager.specific-button')).on("click",function () {
        //     datatable_manager_in.draw();
        // })
    });
    // $(document).on("click", '.btn-filter-manager.specific-button', function (event) {
    //     if ($(event.target).hasClass('specific-button')) {
    //         console.log('ok');
    //         datatable_manager_in.draw();
    //     }
    // });
    $(document).on("click", ".specific-button", function (e) {
        $(this).closest('.tab-pane').find('.datatable-inner.manager').DataTable().draw();
    });
    // Edit manager
    // ------------------------------------------------------------------------------
    // get manager info
    $(document).on("click", ".edit-manager-info", function () {
        let manager_id = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: '/admin/manager-settings/get-manager-info/' + manager_id,
            dataType: 'json',
            success: function (data) {
                $("#manager-infos").html(data.data);
                $("#display-manager-group").html(data.manager_group);
                feather.replace();
            }
        });
    })
    // disable user/manager 
    // ---------------------------------------------------------------------------------
    $(document).on("click", ".btn-disable", function () {
        let manager_id = $(this).data('id');
        Swal.fire({
            icon: 'warning',
            title: 'Disable Manager!',
            html: 'Are You Confirm? To disable this',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-warning'
            }
        }).then((willDisable) => {
            if (willDisable.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/manager-settings/disable-manager',
                    method: 'POST',
                    dataType: 'json',
                    data: { manager_id: manager_id },
                    success: function (data) {
                        if (data.status === true) {
                            toastr['success'](data.message, 'Disable Manager', {
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            manager_datatable.draw();
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Disabled operation failed!',
                                html: 'The manager disable operation failed, please try again later!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })
            }
        });
    })
    // Enable user/manager 
    // ---------------------------------------------------------------------------------
    $(document).on("click", ".btn-enable", function () {
        let manager_id = $(this).data('id');
        Swal.fire({
            icon: 'warning',
            title: 'Disable Manager!',
            html: 'Are You Confirm? To enable this',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-warning'
            }
        }).then((willDisable) => {
            if (willDisable.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/manager-settings/enable-manager',
                    method: 'POST',
                    dataType: 'json',
                    data: { manager_id: manager_id },
                    success: function (data) {
                        if (data.status === true) {
                            toastr['success'](data.message, 'Enable Manager', {
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            manager_datatable.draw();
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Enable operation failed!',
                                html: 'The manager enable operation failed, please try again later!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })
            }
        });
    })
    // Block user/manager 
    // ---------------------------------------------------------------------------------
    $(document).on("click", ".btn-block", function () {
        let manager_id = $(this).data('id');
        Swal.fire({
            icon: 'warning',
            title: 'Block Manager!',
            html: 'Are You Confirm? To block this',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-warning'
            }
        }).then((willDisable) => {
            if (willDisable.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/manager-settings/block-manager',
                    method: 'POST',
                    dataType: 'json',
                    data: { manager_id: manager_id },
                    success: function (data) {
                        if (data.status === true) {
                            toastr['success'](data.message, 'Block Manager', {
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            manager_datatable.draw();
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Block operation failed!',
                                html: 'The manager Block operation failed, please try again later!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })
            }
        });
    })
});