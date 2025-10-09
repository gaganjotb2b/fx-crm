$(function () {
    ('use strict');

    // variables
    var form = $('.validate-form'),
        accountUploadImg = $('#account-upload-img'),//brand logo
        accountUploadImg2 = $('#account-upload-img-2'),//brand logo

        accountUploadBtn = $('#account-upload'),//brand logo
        accountUploadBtn2 = $('#account-upload-2'),//loader logo

        accountUserImage = $('.uploadedAvatar'),//brand logo
        accountUserImage2 = $('.uploadedAvatar-2'),//loader logo

        accountResetBtn = $('#account-reset'),//brand logo reset
        accountResetBtn2 = $('#account-reset-2'),//loader logo reset

        accountNumberMask = $('.account-number-mask'),
        accountZipCode = $('.account-zip-code')

    // Update user photo on click of button
    //   logo 1
    if (accountUserImage) {
        var resetImage = accountUserImage.attr('src');
        accountUploadBtn.on('change', function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (accountUploadImg) {
                    accountUploadImg.attr('src', reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
        });

        accountResetBtn.on('click', function () {
            accountUserImage.attr('src', resetImage);
        });
    }
    // logo 2
    if (accountUserImage2) {
        var resetImage2 = accountUserImage2.attr('src');
        accountUploadBtn2.on('change', function (e) {
            var reader2 = new FileReader(),
                files2 = e.target.files;
            reader2.onload = function () {
                if (accountUploadImg2) {
                    accountUploadImg2.attr('src', reader2.result);
                }
            };
            reader2.readAsDataURL(files2[0]);
        });

        accountResetBtn2.on('click', function () {
            accountUserImage2.attr('src', resetImage2);
        });
    }

});
