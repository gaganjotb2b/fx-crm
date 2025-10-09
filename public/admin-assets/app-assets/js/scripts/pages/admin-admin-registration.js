/*=========================================================================================
    File Name: pickers.js
    Description: Pick a date/time Picker, Date Range Picker JS
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: Pixinvent
    Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/
(function (window, document, $) {
    'use strict';
  
    /*******  Flatpickr  *****/
    var basicPickr = $('.flatpickr-basic'),
      timePickr = $('.flatpickr-time'),
      dateTimePickr = $('.flatpickr-date-time'),
      multiPickr = $('.flatpickr-multiple'),
      rangePickr = $('.flatpickr-range'),
      humanFriendlyPickr = $('.flatpickr-human-friendly'),
      disabledRangePickr = $('.flatpickr-disabled-range'),
      inlineRangePickr = $('.flatpickr-inline');
  
    // Default
    if (basicPickr.length) {
      basicPickr.flatpickr();
    }
  
    // Time
    if (timePickr.length) {
      timePickr.flatpickr({
        enableTime: true,
        noCalendar: true
      });
    }
  
    // Date & TIme
    if (dateTimePickr.length) {
      dateTimePickr.flatpickr({
        enableTime: true
      });
    }
  
    // Multiple Dates
    if (multiPickr.length) {
      multiPickr.flatpickr({
        weekNumbers: true,
        mode: 'multiple',
        minDate: 'today'
      });
    }
  
    // Range
    if (rangePickr.length) {
      rangePickr.flatpickr({
        mode: 'range'
      });
    }
  
    // Human Friendly
    if (humanFriendlyPickr.length) {
      humanFriendlyPickr.flatpickr({
        altInput: true,
        altFormat: 'F j, Y',
        dateFormat: 'Y-m-d'
      });
    }
  
    // Disabled Range
    if (disabledRangePickr.length) {
      disabledRangePickr.flatpickr({
        dateFormat: 'Y-m-d',
        disable: [
          {
            from: new Date().fp_incr(2),
            to: new Date().fp_incr(7)
          }
        ]
      });
    }
  
    // Inline
    if (inlineRangePickr.length) {
      inlineRangePickr.flatpickr({
        inline: true
      });
    }
    /*******  Pick-a-date Picker  *****/
    // Basic date
    $('.pickadate').pickadate();
  
    // Format Date Picker
    $('.format-picker').pickadate({
      format: 'mmmm, d, yyyy'
    });
  
    // Date limits
    $('.pickadate-limits').pickadate({
      min: [2019, 3, 20],
      max: [2019, 5, 28]
    });
  
    // Disabled Dates & Weeks
  
    $('.pickadate-disable').pickadate({
      disable: [1, [2019, 3, 6], [2019, 3, 20]]
    });
  
    // Picker Translations
    $('.pickadate-translations').pickadate({
      formatSubmit: 'dd/mm/yyyy',
      monthsFull: [
        'Janvier',
        'Février',
        'Mars',
        'Avril',
        'Mai',
        'Juin',
        'Juillet',
        'Août',
        'Septembre',
        'Octobre',
        'Novembre',
        'Décembre'
      ],
      monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
      weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
      today: "aujourd'hui",
      clear: 'clair',
      close: 'Fermer'
    });
  
    // Month Select Picker
    $('.pickadate-months').pickadate({
      selectYears: false,
      selectMonths: true
    });
  
    // Month and Year Select Picker
    $('.pickadate-months-year').pickadate({
      selectYears: true,
      selectMonths: true
    });
  
    // Short String Date Picker
    $('.pickadate-short-string').pickadate({
      weekdaysShort: ['S', 'M', 'Tu', 'W', 'Th', 'F', 'S'],
      showMonthsShort: true
    });
  
    // Change first weekday
    $('.pickadate-firstday').pickadate({
      firstDay: 1
    });
  
    /*******    Pick-a-time Picker  *****/
    // Basic time
    $('.pickatime').pickatime();
  
    // Format options
    $('.pickatime-format').pickatime({
      // Escape any “rule” characters with an exclamation mark (!).
      format: 'T!ime selected: h:i a',
      formatLabel: 'HH:i a',
      formatSubmit: 'HH:i',
      hiddenPrefix: 'prefix__',
      hiddenSuffix: '__suffix'
    });
  
    // Format options
    $('.pickatime-formatlabel').pickatime({
      formatLabel: function (time) {
        var hours = (time.pick - this.get('now').pick) / 60,
          label = hours < 0 ? ' !hours to now' : hours > 0 ? ' !hours from now' : 'now';
        return 'h:i a <sm!all>' + (hours ? Math.abs(hours) : '') + label + '</sm!all>';
      }
    });
  
    // Min - Max Time to select
    $('.pickatime-min-max').pickatime({
      // Using Javascript
      min: new Date(2015, 3, 20, 7),
      max: new Date(2015, 7, 14, 18, 30)
  
      // Using Array
      // min: [7,30],
      // max: [14,0]
    });
  
    // Intervals
    $('.pickatime-intervals').pickatime({
      interval: 150
    });
  
    // Disable Time
    $('.pickatime-disable').pickatime({
      disable: [
        // Disable Using Integers
        3,
        5,
        7,
        13,
        17,
        21
  
        /* Using Array */
        // [0,30],
        // [2,0],
        // [8,30],
        // [9,0]
      ]
    });
  
    // Close on a user action
    $('.pickatime-close-action').pickatime({
      closeOnSelect: false,
      closeOnClear: false
    });

    // Ending: Date picker

    /************************************
     * submit registration form
     ************************************/
    let formSection = $('.form-block');
    // $(document).on("submit","#admin-reg-form",function (event) {
    //   let form_data = $(this).serializeArray();
    //   event.preventDefault();
    //   $.ajaxSetup({
    //     headers: {
    //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    //   });
    //   $.ajax({
    //     url: '/admin/admin-management/admin-registration',
    //     method: 'POST',
    //     dataType: 'json',
    //     data: form_data,
    //     success: function (data) {
    //       if (data.status === true) {
    //         toastr['success'](data.message,'Admin Added', {
    //           showMethod: 'slideDown',
    //           hideMethod: 'slideUp',
    //           closeButton: true,
    //           tapToDismiss: false,
    //           progressBar: true,
    //           timeOut: 2000,
    //         });
    //         Swal.fire({
    //           icon: 'success',
    //           title: 'New admin added',
    //           html: 'Please asigne atleast one roles to this admin',
    //           customClass: {
    //             confirmButton: 'btn btn-danger'
    //           }
    //         });
    //         formSection.block({
    //           message: '<div class="p-1 bg-danger">Something Went Wrong!</div>',
    //           timeout: 500,
    //           css: {
    //             backgroundColor: 'transparent',
    //             color: '#fff',
    //             border: '0'
    //           },
    //           overlayCSS: {
    //             opacity: 0.25
    //           }
    //         });
    //       }
    //       else{ //if found amy errors
    //         let errors = "<ol>";
    //         if (data.errors.hasOwnProperty('name')) {
    //           errors += '<li>'+data.errors.name+'</li>'
    //         }

    //         if (data.errors.hasOwnProperty('email')) {
    //           errors += '<li>'+data.errors.email+'</li>';
    //         }

    //         if (data.errors.hasOwnProperty('phone')) {
    //           errors += '<li>'+data.errors.phone+'</li>';
    //         }

    //         if (data.errors.hasOwnProperty('country')) {
    //           errors += '<li>'+data.errors.country+'</li>';
    //         }
    //         if (data.errors.hasOwnProperty('admin_group')) {
    //           errors += '<li>'+data.errors.admin_group+'</li>';
    //         }
    //         if (data.errors.hasOwnProperty('transaction_pin')) {
    //           errors += '<li>'+data.errors.transaction_pin+'</li>';
    //         }
          
    //         errors +='</ol>';

    //         Swal.fire({
    //           icon: 'error',
    //           title: 'Registration Failed!',
    //           html: errors,
    //           customClass: {
    //             confirmButton: 'btn btn-danger'
    //           }
    //         });
    //         formSection.block({
    //           message: '<div class="p-1 bg-danger">Something Went Wrong!</div>',
    //           timeout: 500,
    //           css: {
    //             backgroundColor: 'transparent',
    //             color: '#fff',
    //             border: '0'
    //           },
    //           overlayCSS: {
    //             opacity: 0.25
    //           }
    //         });
    //       }
    //     }
    //   });
    // })// Ending: form submit

    // Trigger form submit on click
    $(document).on("click","#save-admin-btn",function () {
      formSection.block({
        message: '<div class="spinner-border text-primary" role="status"></div>',
        // timeout: 1000,
        css: {
          backgroundColor: 'transparent',
          border: '0'
        },
        overlayCSS: {
          opacity: 0.3,
        }
      });
      $("#admin-reg-form").trigger('submit');
    })
    
  })(window, document, jQuery);
  