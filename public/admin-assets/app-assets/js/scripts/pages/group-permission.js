
$(function () {
    
    // manager Datatable
    /*********************************************************** */
    var cd = (new Date()).toISOString().split('T')[0];
    var manager_datatable = $('#group-list').DataTable({
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
              columns: [ 0, 1, 2, 3, 4 ]
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
        "url": "/admin/manager-settings/group-permission-datatable",
      },
      "columns": [
        { "data": "group_name" },
        { "data": "type" },
        { "data": "status" },
        // { "data": "actions" },
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
      manger_datatable.draw();
    });
    // reset operation
    $("#btn-reset").on("click", function (e) {
        $(".start_date").val('');
        $(".end_date").val('');
        $("#filter-form").trigger('reset');
        manger_datatable.draw();
    });

    //    datatable descriptions
  // --------------------------------------------------------------------------------------------------------
    $(document).on("click", ".dt-description", function (params) {
        let __this = $(this);
        let groupID = $(this).data('id');
        $.ajax({
            type: "GET",
            url: '/admin/manager-settings/group-des-with-right/' + groupID,
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
                                    columns: [ 0, 1, 2, 3, 4 ]
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
                              "url": "/admin/admin-management/fetch-group-role-permission/"+groupID,
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
        });
    });

    // Filter operation
    // ----------------------------------------------------------------
    $(document).on("click",".btn-filter", function (e) {
        $(this).closest('tr').find('.accessible-user').DataTable().draw();
    });

    // Export functions
    /********************************************************************* */
    // datatable export function
  $(document).on("change", "#fx-export", function () {
    if ($(this).val() === 'csv') {
      $(".buttons-csv").trigger('click');
    }
    if ($(this).val() === 'excel') {
      $(".buttons-excel").trigger('click');
    }

  });
  function serverSideButtonAction(e, dt, node, config) {

    var me = this;
    var button = config.text.toLowerCase();
    if (typeof $.fn.dataTable.ext.buttons[button] === "function") {
      button = $.fn.dataTable.ext.buttons[button]();
    }
    var len = dt.page.len();
    var start = dt.page();
    dt.page(0);

    // Assim que ela acabar de desenhar todas as linhas eu executo a função do botão.
    // ssb de serversidebutton
    dt.context[0].aoDrawCallback.push({
      "sName": "ssb",
      "fn": function () {
        $.fn.dataTable.ext.buttons[button].action.call(me, e, dt, node, config);
        dt.context[0].aoDrawCallback = dt.context[0].aoDrawCallback.filter(function (e) { return e.sName !== "ssb" });
      }
    });
    dt.page.len(999999999).draw();
    setTimeout(function () {
      dt.page(start);
      dt.page.len(len).draw();
    }, 500);
  }

  


   


    // select all rights
    // administrator access
    // ---------------------------------------------------------------
    // $(document).on('change','.select-all-right',function () {
    //     if ($(this).is(':checked')) {
    //         $(this).closest('table').find('.form-check-input').each(function (index, $this) {
    //             $($this).prop('checked',true);
    //         });
    //     }
    //     else{
    //         $(this).closest('table').find('.form-check-input').each(function (index, $this) {
    //             $($this).prop('checked',false);
    //         });
    //     }
    // });

    // checked all permission for this rights
    // ----------------------------------------------------------------
    // $(document).on('change',".role-checkbox",function () {
    //     if ($(this).is(":checked")) {
    //         $(this).closest('tr').find('.form-check-input').each(function (index, $this) {
    //             $($this).prop('checked',true);
    //         });
    //     }
    //     else{
    //         $(this).closest('tr').find('.form-check-input').each(function (index, $this) {
    //             $($this).prop('checked',false);
    //         });
    //     }
    // });

    
    // Edit manager
    // ------------------------------------------------------------------------------
    // get manager info
    $(document).on("click",".edit-manager-info",function () {
        let manager_id = $(this).data('id');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "GET",
          url: '/admin/manager-settings/get-manager-info/'+manager_id,
          dataType: 'json',
          success: function (data) {
            $("#manager-infos").html(data);
          }
        });
    })
    // form submit edit manager
    // -----------------------------------------------------------------------------
    $(document).on("submit","#edit-manager-info-form",function () {
        let form_data = $(this).serializeArray();
        event.preventDefault();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
            type: "POST",
            url: '/admin/manager-settings/edit-manager',
            dataType: 'json',
            data: form_data,
            success: function (data) {
                if (data.status == true) {
                    toastr['success'](data.message, 'Manager Group',{
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
                if (data.status == false) {
                    let error_list = '<ol>';
                    if (data.message) {
                      error_list +='<li>'+data.message+'</li>';
                    }
                    error_list +='</ol>';
                    Swal.fire({
                        icon: 'error',
                        title: 'Manager Group!',
                        html: error_list,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    })
                }
            }
        });
    })

    // form submit by onclick
    // ----------------------------------------------------------------------------
    $(document).on("click",".btn-save-edit-manager",function () {
      $(this).closest("#edit-manager-info-form").trigger("submit");
    });

    
    // disable user/manager 
    // ---------------------------------------------------------------------------------
    $(document).on("click",".btn-disable",function () {
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
                toastr['success'](data.message, 'Disable Manager',{
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
    $(document).on("click",".btn-enable",function () {
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
                toastr['success'](data.message, 'Enable Manager',{
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
    $(document).on("click",".btn-block",function () {
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
                toastr['success'](data.message, 'Block Manager',{
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




      // check uncheck role checkbox
  // ---------------------------------------------------------------------------------------
  $(document).on("change",".permission-check",function () {
    // console.log('this');
    let total_permission = $(this).closest("tr").find('.permission-check:checked').length;
    if (total_permission != 0) {
        $(this).closest('tr').find('.role-checkbox').prop("checked",true);
    }else{
        $(this).closest('tr').find('.role-checkbox').prop("checked",false);
    }
    
  })

  // check uncheck permission checkbox
  // -------------------------------------------------------------------------------------
  $(document).on("change",".role-checkbox",function () {
    if ($(this).is(':checked')) {
      $(this).closest("tr").find(".permission-check").each(function (index, obj) {
        $(obj).prop("checked",true);
      })
    }
    else {
      $(this).closest("tr").find(".permission-check").each(function (index, obj) {
        $(obj).prop("checked",false);
      })
    }
  })

  // check uncheck all
  // --------------------------------------------------------------------------
  $(document).on("change","#selectAll",function name() {
    if ($(this).is(':checked')) {
      $(".role-checkbox").each(function (index, obj) {
        $(obj).prop("checked",true);
      })
      $(".permission-check").each(function (index, obj) {
        $(obj).prop("checked",true);
      })
    }
    else {
      $(".role-checkbox").each(function (index, obj) {
        $(obj).prop("checked",false);
      })
      $(".permission-check").each(function (index, obj) {
        $(obj).prop("checked",false);
      })
    }
  })

  // save permission button click function 
  $(document).on("click",".save-permission, .description .page-item",function () {
    var onClickClass = $(this).attr('data-message');
    var fromID = $(this).closest('form').attr('id');
    var checkRolesSe = $('#'+fromID+' input[type="checkbox"][name="roles[]"]:checked');
    var unCheckRolesSe = $( '#'+fromID+' input[type="checkbox"][name="roles[]"]:unchecked');
    var unCheckPermissionSe = $( '#'+fromID+' input[type="checkbox"][name="permission[]"]:unchecked');
    var checkPermissionSe = $( '#'+fromID+' input[type="checkbox"][name="permission[]"]:checked');
    var checkRoles = [];
    var unCheckRoles = [];
    var checkPermission = [];
    var unCheckPermission = [];
    var id = $( '#'+fromID+' input[name="id"]').val();
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
        'id' : id,
        'checkRoles' : checkRoles,
        'unCheckRoles' : unCheckRoles,
        'unCheckPermission' : unCheckPermission,
        'checkPermission' : checkPermission
    }


    $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: '/admin/admin-management/assign-group-perimission-to-role',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function (data) {
           if(data.status){
            if(onClickClass == 'true'){
              notify('success', data.message);
            }
           }
           else{
            notify('info', data.message);
          }
        }
    });
  })




});