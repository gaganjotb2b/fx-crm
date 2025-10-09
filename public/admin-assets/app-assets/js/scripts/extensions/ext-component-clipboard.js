/*=========================================================================================
    File Name: ext-component-clipboard.js
    Description: Copy to clipboard
    --------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

'use strict';

var userText = $('#copy-to-clipboard-input');
var btnCopy = $('#btn-copy'),
    isRtl = $('html').attr('data-textdirection') === 'rtl';

// copy text on click
btnCopy.on('click', function () {
    userText.select();
    document.execCommand('copy');
    toastr['success']('', 'Copied to clipboard!', {
        rtl: isRtl
    });
});
// custom function by reza
//04/04/2023
$.fn.copy_clipboard = function (options) {
    var settings = $.extend({
        copy_el: '.copy-input',

    }, options);
    this.on('click', function () {
        $(settings.copy_el).select();
        document.execCommand('copy');
        toastr['success']('', 'Copied to clipboard!', {
            rtl: isRtl
        });
    });
}
