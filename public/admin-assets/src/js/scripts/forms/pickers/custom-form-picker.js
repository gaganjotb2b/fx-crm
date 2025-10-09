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
    
      var humanFriendlyPickr = $('.flatpickr-human-friendly');
  
   
    // Human Friendly
    if (humanFriendlyPickr.length) {
      humanFriendlyPickr.flatpickr({
        altInput: true,
        altFormat: 'F j, Y',
        dateFormat: 'Y-m-d'
      });
    }
  
    /*******  Pick-a-date Picker  *****/
    
    
  })(window, document, jQuery);
  