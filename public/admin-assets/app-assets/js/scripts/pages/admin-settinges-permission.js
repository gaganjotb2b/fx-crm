(function (window, document, $) { 
    let formSection = $('.right-form');
    
    // Roles Datatable
    /*********************************************************** */
    var cd = (new Date()).toISOString().split('T')[0];
    var datatable = $('.role-datatable').DataTable({
      "processing": true,
      "serverSide": true,
      "searching": true,
      "lengthChange": true,
      // scrollY: '40vh',
      // scrollCollapse: true,
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
        "url": "/system/admin-management/get-all-admins",
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
  
  //   assign role permission
  // display on modal
  /***************************************************************** */
  $(document).on("click",".asign-permission",function () {
    let id = $(this).data('id');
  
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
        "url": "/admin/admin-management/fetch-modal-role-permission/"+id,
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
        $("#available-permission").html(settings.json.role_name);
        $(".to-name").html(settings.json.name);
       
      }
    });
  
  })
  // add new role
  /************************************************************ */
    formSection = $('.role-form');
    // check uncheck role checkbox
    // ---------------------------------------------------------------------------------------
    $(document).on("change",".permission-check",function () {
      // console.log('changes');
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
  })(window, document, jQuery);