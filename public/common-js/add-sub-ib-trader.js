 // remove select2 errors by hide modal
 $('.modal').on('hidden.bs.modal, shown.bs.modal', function() {
    $(this).find('.error-msg').remove();
    $(".select2-whith-des").empty().trigger("change");
}); 

 
 //show ib id in modal
 $(document).on("click", ".manage-sub-ib-btn", function () {
     let __this = $(this);
     let trader_subIB = $(this).data('ib_id');
     $('#show_sub_ib_id').val(trader_subIB);

 })

 $(document).on("click", ".manage-trader-added-btn", function () {
    let __this = $(this);
    let trader_subIB = $(this).data('ib_id');
    $('#show_trader_id').val(trader_subIB);

})

// $('#sub_ib_add').on('select2:clearing', function (e) {
//     $('#sub_ib_add').val(null).trigger('change');
//   });

 // select 2 with options descriptions
 $(function () {
     $(document).on('keypress', '.select2-search__field', function (e) {
         if (e.which === 13) {
             e.preventDefault();
         }
     });

     $('#sub_ib_add').select2('destroy')
     $("#sub_ib_add").select2({
         tags: false,
         dropdownParent: $('#sub-ib-modal'),
         templateResult: formatOption,
         selectOnClose: true,
         language: {
             noResults: function () {
                 return "Enter Email to search here";
             }
         },
         ajax: {
             url: "/search/ib/users/sub-ib",
             // type: "post",
             dataType: 'json',
             delay: 250,
             data: function (params) {
                 return {
                     searchTerm: params.term // search term
                 };
             },
             processResults: function (response) {
                 return {
                     results: response
                 };
             },
             cache: true
         }
     });
     // select2 for trader add
     $('#trader_added_filed').select2('destroy')
     $("#trader_added_filed").select2({
         tags: false,
         dropdownParent: $('#trader-added-modal'),
         templateResult: formatOption,
         language: {
            noResults: function () {
                return "Enter Email to search here";
            }
        },
         ajax: {
             url: "/search/ib/users/trader",
             // type: "post",
             dataType: 'json',
             delay: 250,
             data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
             },
             processResults: function(response) {
                 return {
                     results: response
                 };
             },
             cache: true
         }
     });
     // option description for select2
     function formatOption(option) {
         var $option = $(
             '<div><strong>' + option.text + '</strong></div><div>' + option.title +
             '</div><div>Has Parent IB: ' + option.parent + '</div>'
         );
         return $option;
     };
 });

 //callback function for sub ib add
 function added_sub_ib_call_back(data) {
     if (data.status == true) {
         notify('success', data.message, 'Client add');
         $('#sub-ib-modal').modal('hide');
         $('#trader-added-modal').modal('hide');
         dt.draw();
     } else {
         notify('error', data.message, 'Client add');
        
     }
     $.validator("added-sub-ib-form", data.errors);
 }


  /*<!---------------Delete Trader------------------!>*/
  function delete_trader(e) {
    var obj = $(e);
    // var dt_ajax_table = $(__this).closest('tr').find('.sub-ib').DataTable;
    var trader_id = obj.data('ib_id');
    // console.log(trader_id);
    let warning_title = "";
    let warning_msg = "";
    let request_for;

    warning_title = 'Are you sure? to delete this user!';
    warning_msg = 'If you want to delete this User please click OK, otherwise simply click cancel'
    request_for = 'block'

    Swal.fire({
        icon: 'warning',
        title: warning_title,
        html: warning_msg,

        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-warning',
            cancelButton: 'btn btn-danger'
        },
        closeOnCancel: false,
        closeOnConfirm: false,
    }).then((willDelete) => {
        if (willDelete.isConfirmed) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/ib-management/delete-trader',
                method: 'POST',
                dataType: 'json',
                data: {
                    trader_id: trader_id,
                },
                success: function(data) {
                    if (data.success === true) {

                        Swal.fire({
                            icon: 'success',
                            title: data.success_title,
                            html: data.message,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then((willDelete) => {
                            obj.closest('.trader-list').DataTable().draw();
                        });
                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to delete user',
                            html: data.message,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })

        }

    });
}
/*<!---------------Delete Trader------------------!>*/


  /*<!---------------Delete sub IB------------------!>*/
  function delete_sub_ib(e) {
    var obj = $(e);
    // var dt_ajax_table = $(__this).closest('tr').find('.sub-ib').DataTable;


    var sub_id = obj.data('ib_id');
    // console.log(sub_id);
    let warning_title = "";
    let warning_msg = "";
    let request_for;

    warning_title = 'Are you sure? to delete this user!';
    warning_msg = 'If you want to delete this User please click OK, otherwise simply click cancel'
    request_for = 'block'

    Swal.fire({
        icon: 'warning',
        title: warning_title,
        html: warning_msg,

        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-warning',
            cancelButton: 'btn btn-danger'
        },
        closeOnCancel: false,
        closeOnConfirm: false,
    }).then((willDelete) => {
        if (willDelete.isConfirmed) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/ib-management/delete-sub-ib',
                method: 'POST',
                dataType: 'json',
                data: {
                    sub_id: sub_id,
                    request_for: request_for
                },
                success: function(data) {
                    if (data.success === true) {

                        Swal.fire({
                            icon: 'success',
                            title: data.success_title,
                            html: data.message,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then((willDelete) => {
                            // const table = $("").DataTable();
                            // table.draw();
                            obj.closest('.sub-ib').DataTable().draw();
                        });
                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to delete user',
                            html: data.message,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })

        }

    });
}
/*<!---------------Delete Sub  IB------------------!>*/
