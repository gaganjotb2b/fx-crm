/**
 * DataTables Advanced
 */

'use strict';

// Advanced Search Functions Starts
// --------------------------------------------------------------------

// Filter column wise function
function filterColumn(i, val) {
  if (i == 5) {
    var startDate = $('.start_date').val(),
      endDate = $('.end_date').val();
    if (startDate !== '' && endDate !== '') {
      filterByDate(i, startDate, endDate); // We call our filter function
    }

    $('.dt-advanced-search').dataTable().fnDraw();
  } else {
    $('.dt-advanced-search').DataTable().column(i).search(val, false, true).draw();
  }
}

// Datepicker for advanced filter
var separator = ' - ',
  rangePickr = $('.flatpickr-range'),
  dateFormat = 'MM/DD/YYYY';
var options = {
  autoUpdateInput: false,
  autoApply: true,
  locale: {
    format: dateFormat,
    separator: separator
  },
  opens: $('html').attr('data-textdirection') === 'rtl' ? 'left' : 'right'
};

//
if (rangePickr.length) {
  rangePickr.flatpickr({
    mode: 'range',
    dateFormat: 'm/d/Y',
    onClose: function (selectedDates, dateStr, instance) {
      var startDate = '',
        endDate = new Date();
      if (selectedDates[0] != undefined) {
        startDate =
          selectedDates[0].getMonth() + 1 + '/' + selectedDates[0].getDate() + '/' + selectedDates[0].getFullYear();
        $('.start_date').val(startDate);
      }
      if (selectedDates[1] != undefined) {
        endDate =
          selectedDates[1].getMonth() + 1 + '/' + selectedDates[1].getDate() + '/' + selectedDates[1].getFullYear();
        $('.end_date').val(endDate);
      }
      $(rangePickr).trigger('change').trigger('keyup');
    }
  });
}

// Advance filter function
// We pass the column location, the start date, and the end date
var filterByDate = function (column, startDate, endDate) {
  // Custom filter syntax requires pushing the new filter to the global filter array
  $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
    var rowDate = normalizeDate(aData[column]),
      start = normalizeDate(startDate),
      end = normalizeDate(endDate);

    // If our date from the row is between the start and end
    if (start <= rowDate && rowDate <= end) {
      return true;
    } else if (rowDate >= start && end === '' && start !== '') {
      return true;
    } else if (rowDate <= end && start === '' && end !== '') {
      return true;
    } else {
      return false;
    }
  });
};

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
var normalizeDate = function (dateString) {
  var date = new Date(dateString);
  var normalized =
    date.getFullYear() + '' + ('0' + (date.getMonth() + 1)).slice(-2) + '' + ('0' + date.getDate()).slice(-2);
  return normalized;
};
// Advanced Search Functions Ends

$(function () {
  var isRtl = $('html').attr('data-textdirection') === 'rtl';

  var dt_ajax_table = $('.datatables-ajax'),


    dt_filter_table = $('.dt-column-search'),
    dt_adv_filter_table = $('.dt-advanced-search'),
    dt_responsive_table = $('.dt-responsive'),
    assetPath = '../../../app-assets/';

  var dt_ajax_inner_table = $('.datatable-inner'),
    dt_adv_filter_inner = $('.dt-advanced-search'),
    dt_responsive_inner = $('.dt-responsive');

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  // Ajax Sourced Server-side
  // --------------------------------------------------------------------

  if (dt_ajax_table.length) {

    var cd = (new Date()).toISOString().split('T')[0];
    var dt_ajax_table = dt_ajax_table.DataTable({
      "processing": true,
      "serverSide": true,
      "searching": false,
      "lengthChange": false,
      "buttons": true,
      "dom": 'Bfrtip',
      buttons: [
        {
          extend: 'csv',
          text: 'csv',
          className: 'btn btn-success btn-sm',
          action: serverSideButtonAction
        }
      ],
      "ajax": { "url": "/admin/report/deposit-dt-proccess" },
      "columns": [
        { "data": "name" },
        { "data": "email" },
        { "data": "account" },
        { "data": "transaction_type" },
        { "data": "invoice_id" },
        { "data": "approved_status" },
      ],
      "order": [[1, 'desc']]
    });
  }
  //   ajax sourced server side inner table
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

  // Filter form control to default size for all tables
  $('.dataTables_filter .form-control').removeClass('form-control-sm');
  $('.dataTables_length .form-select').removeClass('form-select-sm').removeClass('form-control-sm');

  //    datatable descriptions
  $(document).on("click", ".dt-description", function (params) {
    let __this = $(this);
    $.ajax({
      type: "GET",
      url: '/admin/report/dt-description-deposit',
      dataType: 'json',
      success: function (data) {
        if (data.status == false) {
          let $errors = '';
          if (data.errors.hasOwnProperty('crm_type')) {
            $errors += "  " + data.errors.crm_type[0] + '<br>';
          }
          if (data.errors.hasOwnProperty('mt5_download_link')) {
            $errors += "  " + data.errors.mt5_download_link[0] + '';
          }

          Swal.fire({
            icon: 'error',
            title: 'Error found!',
            html: $errors,
            customClass: {
              confirmButton: 'btn btn-danger'
            }
          });
        }
        if (data.status == true) {
          if ($(__this).closest("tr").next().hasClass("description")) {
            $(__this).closest("tr").next().remove();
            $(__this).find('.w').html(feather.icons['plus'].toSvg());
          } else {
            $(__this).closest('tr').after(data.description);
            $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
            // $(__this).find('svg').remove();
            $(__this).find('.w').html(feather.icons['minus'].toSvg());

            // Inner datatable
            if ($('.datatable-inner').length) {
              $('.datatable-inner').DataTable().clear().destroy();
              var dt_inner = $('.datatable-inner').dataTable({
                processing: true,
                "filter": false,
                "bLengthChange": false,
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: '/admin/report/deposit-dt-inner-proccess',
                language: {
                  paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                  }
                }
              });
            }
          }
        }
      }
    })
  })
});
