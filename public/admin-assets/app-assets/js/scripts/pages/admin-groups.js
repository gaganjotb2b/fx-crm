$(function () {

    // Roles Datatable
    /*********************************************************** */
    var cd = (new Date()).toISOString().split('T')[0];
    var datatable = $('#admin-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": true,
        "lengthChange": true,
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
            "url": "/admin/admin-management/get-all-admins",
        },
        "columns": [
            { "data": "name" },
            { "data": "group" },
            { "data": "country" },
            { "data": "status" },
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
    // Filter operation
    $("#btn-filter").on("click", function (e) {
        datatable.draw();
    });
    // reset operation
    $("#btn-reset").on("click", function (e) {
        $(".start_date").val('');
        $(".end_date").val('');
        $("#filter-form").trigger('reset');
        datatable.draw();
    });

    //    datatable descriptions
    // --------------------------------------------------------------------------------------------------------
    $(document).on("click", ".dt-description", function (params) {
        let __this = $(this);
        let admin_id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: '/admin/admin-management/get-all-admin-description/' + admin_id,
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

                        // get role permission table 
                        $('.role-permission-datatable').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "searching": true,
                            "lengthChange": true,
                            "buttons": true,
                            // "dom": 'B<"clear">lfrtip',
                            "destroy": true,
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
                                "url": "/admin/admin-management/fetch-modal-role-permission/" + admin_id,
                            },
                            "columns": [
                                { "data": "name" },
                                { "data": "read" },
                                { "data": "edit" },
                                { "data": "delete" },
                                { "data": "create" },
                            ],
                            "order": [[1, 'desc']],
                            "drawCallback": function (settings) {
                                var rows = this.fnGetData();
                                if (rows.length !== 0) {
                                    feather.replace();
                                }


                            }
                        });
                        // end role permissioin table 


                    }
                }
            }
        })
    });

    // get data into edit offcanvas
    $(document).on("click", ".edit-group", function () {
        $("#group_id").val($(this).data('id'));
        $("#group-name-edit").val($(this).data('name'));
    })

    // select all rights---------------------------------------------
    $(document).on('change', '.select-all-right', function () {
        if ($(this).is(':checked')) {
            $(this).closest('tr').find('.form-check-input').each(function (index, $this) {
                $($this).prop('checked', true);
            });
        }
        else {
            $(this).closest('table').find('.form-check-input').each(function (index, $this) {
                $($this).prop('checked', false);
            });
        }
    });
    // checked all permission for this rights
    // ----------------------------------------------------------------
    $(document).on('change', ".role-checkbox", function () {
        if ($(this).is(":checked")) {
            $(this).closest('tr').find('.form-check-input').each(function (index, $this) {
                $($this).prop('checked', true);
            });
        }
        else {
            $(this).closest('tr').find('.form-check-input').each(function (index, $this) {
                $($this).prop('checked', false);
            });
        }
    });

    /// block unblock-------------------------------------------------
    $(document).on("change click", ".btn-block", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).data('id');
        console.log(id);
        if ($(this).is(":checked") || ($(this).data('request_for') != "" && $(this).data('request_for') === 'block')) {
            warning_title = 'Are you sure? to Block this user!';
            warning_msg = 'If you want to Block this User please click OK, otherwise simply click cancel'
            request_for = 'block'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Unblock this user!';
            warning_msg = 'If you want to Unblock this User please click OK, otherwise simply click cancel'
            request_for = 'unblock'
        }
        let data = { id: id, request_for: request_for };
        let request_url = '/admin/client-management/trader-admin-block-trader';
        confirm_alert(warning_title, warning_msg, request_url, data, 'User ' + request_for, datatable);
    })
    // update modal open-------------------
    $(document).on("click", '.btn-edit-admin', function () {
        let id = $(this).data('id');

        // get_form('admin',id,"admin-update-form-field","fp-human-friendly");
        $.ajax({
            url: '/admin/admin-management/user-get-form/admin-data/' + id,
            dataType: 'JSON',
            method: 'GET',
            success: function (data) {
                $("#modern-email").val(data.email);
                $("#modern-phone").val(data.phone);
                $("#modern-password").val(data.password);
                $("#modern-name").val(data.name);
                // $("#modern-name").val(data.country);
                // $("#modern-name").val(data.gender);
                $("#modern-date-of-birth").val(data.date_of_birth);
                $("#modern-state").val(data.state);
                $("#modern-city").val(data.city);
                $("#modern-zipcode").val(data.zip_code);
                $("#modern-address").val(data.address);
                $("#modern-facebook").val(data.facebook);
                $("#modern-twitter").val(data.twitter);
                $("#modern-telegram").val(data.telegram);
                $("#modern-linkedin").val(data.linkedin);
                $("#modern-skype").val(data.skype);
                $("#modern-whatsapp").val(data.whatsapp);

                $('.user_id').each(function () {
                    $(this).val(data.user_id);
                });
                // controll gender
                $("#modern-gender").val(data.gender).trigger('change');

                var $newOption = $("<option selected='selected'></option>").val(data.country).text(data.country_name)
                $("#modern-country").append($newOption).trigger('change');
            }
        })
        $("#modal-update-admin").modal("show");
    })










    // check uncheck role checkbox
    // ---------------------------------------------------------------------------------------
    $(document).on("change", ".permission-check", function () {
        // console.log('changes');
        let total_permission = $(this).closest("tr").find('.permission-check:checked').length;
        if (total_permission != 0) {
            $(this).closest('tr').find('.role-checkbox').prop("checked", true);
        } else {
            $(this).closest('tr').find('.role-checkbox').prop("checked", false);
        }

    })

    // check uncheck permission checkbox
    // -------------------------------------------------------------------------------------
    $(document).on("change", ".role-checkbox", function () {
        if ($(this).is(':checked')) {
            $(this).closest("tr").find(".permission-check").each(function (index, obj) {
                $(obj).prop("checked", true);
            })
        }
        else {
            $(this).closest("tr").find(".permission-check").each(function (index, obj) {
                $(obj).prop("checked", false);
            })
        }
    })

    // check uncheck all
    // --------------------------------------------------------------------------
    $(document).on("change", "#selectAll", function name() {
        if ($(this).is(':checked')) {
            $(".role-checkbox").each(function (index, obj) {
                $(obj).prop("checked", true);
            })
            $(".permission-check").each(function (index, obj) {
                $(obj).prop("checked", true);
            })
        }
        else {
            $(".role-checkbox").each(function (index, obj) {
                $(obj).prop("checked", false);
            })
            $(".permission-check").each(function (index, obj) {
                $(obj).prop("checked", false);
            })
        }
    })

    // save permission button click function 
    $(document).on("click", ".save-permission, .description .page-item", function () {
        var onClickClass = $(this).attr('data-message');
        var fromID = $(this).closest('form').attr('id');
        var checkRolesSe = $('#' + fromID + ' input[type="checkbox"][name="roles[]"]:checked');
        var unCheckRolesSe = $('#' + fromID + ' input[type="checkbox"][name="roles[]"]:unchecked');
        var unCheckPermissionSe = $('#' + fromID + ' input[type="checkbox"][name="permission[]"]:unchecked');
        var checkPermissionSe = $('#' + fromID + ' input[type="checkbox"][name="permission[]"]:checked');
        var checkRoles = [];
        var unCheckRoles = [];
        var checkPermission = [];
        var unCheckPermission = [];
        var id = $('#' + fromID + ' input[name="id"]').val();
        for (let i = 0; i < checkRolesSe.length; i++) {
            checkRoles[i] = checkRolesSe[i].value;
        }
        for (let i = 0; i < unCheckRolesSe.length; i++) {
            unCheckRoles[i] = unCheckRolesSe[i].value;
        }
        for (let i = 0; i < checkPermissionSe.length; i++) {
            checkPermission[i] = checkPermissionSe[i].value;
        }
        for (let i = 0; i < unCheckPermissionSe.length; i++) {
            unCheckPermission[i] = unCheckPermissionSe[i].value;
        }

        var data = {
            'id': id,
            'checkRoles': checkRoles,
            'unCheckRoles': unCheckRoles,
            'unCheckPermission': unCheckPermission,
            'checkPermission': checkPermission
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/admin-management/assign-perimission-to-role',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    if (onClickClass == 'true') {
                        notify('success', data.message);
                    }
                }
            }
        });
    })

});