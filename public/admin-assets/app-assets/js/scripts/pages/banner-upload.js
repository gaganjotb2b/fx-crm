(function (window, document, $) {

    // datatable banners
    // get banner for 160x600
    // -------------------------------------------------------------------------------
    function banner_datatable(banner_size, use_for) {
        var cd = (new Date()).toISOString().split('T')[0];
        return $('.banners-table-160-600').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            "ajax": {
                "url": "/admin/settings/banner-datatable/" + banner_size + "/" + use_for,
            },
            "columns": [
                {
                    data: "banner_1",
                },
                {
                    data: "banner_2",
                },
                {
                    data: "banner_3",
                },
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
    var table = banner_datatable('160x600', 'ib');
    $(document).on('click', '#ib-banner-details-trigger', function () {
        $('.ib_banner_link1').trigger('click');
    });
    $(document).on('click', '#trader-banner-details-trigger', function () {
        $('.trader_banner_link1').trigger('click');
    });
    // banner files upload by drop zone
    // -----------------------------------------------------------------------------------------------

    Dropzone.autoDiscover = false;
    'use strict';
    function banner_upload(banner_size, banner_column, dropzone_id) {
        var banner_upload = $('#' + dropzone_id);
        var banner_name;
        banner_upload.dropzone({
            paramName: 'file', // The name that will be used to transfer the file
            maxFilesize: 1, // MB
            addRemoveLinks: false,
            dictRemoveFile: ' Trash',
            acceptedFiles: 'image/*',
            maxFiles: 1,
            url: "/admin/settings/banner-upload",
            method: 'post',
            params: { column: banner_column, size: banner_size },
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            },
            success: function (file, response) {
                banner_name = response.name; // <---- here is your filename
                // message on toaster
                if (response.status == true) {
                    Dropzone.forElement('#' + dropzone_id).removeAllFiles(true);
                    notify('success', 'Banner successfully updloaded', 'Banner column ' + banner_column);
                    table.draw();
                }
                else {
                    Dropzone.forElement('#' + dropzone_id).removeAllFiles(true);
                    notify('error', 'Banner upload failed! please try again later', 'Banner column ' + banner_column);
                }
            }
        });
    }
    // tab changes datable---------------------------------------
    $(document).on("click", ".tab-banner-link", function () {
        let banner_size = $(this).data('size');
        let use_for = $(this).data('use_for');
        console.log(banner_size);
        table.clear().destroy();
        table = banner_datatable(banner_size, use_for);
    })
    // upload banner for ib
    // upload banner 160 X 600-------------------------
    banner_upload('160x600', 1, 'dpz_160_600_col_1');
    banner_upload('160x600', 2, 'dpz_160_600_col_2');
    banner_upload('160x600', 3, 'dpz_160_600_col_3');

    // START: 200 x 200------------------------------------------------
    banner_upload('200x200', 1, 'dpz_200_200_col_1');
    banner_upload('200x200', 2, 'dpz_200_200_col_2');
    banner_upload('200x200', 3, 'dpz_200_200_col_3');

    // START: 250 x 250------------------------------------------------
    banner_upload('250x250', 1, 'dpz_250_250_col_1');
    banner_upload('250x250', 2, 'dpz_250_250_col_2');
    banner_upload('250x250', 3, 'dpz_250_250_col_3');

    // START: 300 x 250------------------------------------------------
    banner_upload('300x250', 1, 'dpz_300_250_col_1');
    banner_upload('300x250', 2, 'dpz_300_250_col_2');
    banner_upload('300x250', 3, 'dpz_300_250_col_3');

    // START: 300 x 600------------------------------------------------
    banner_upload('300x600', 1, 'dpz_300_600_col_1');
    banner_upload('300x600', 2, 'dpz_300_600_col_2');
    banner_upload('300x600', 3, 'dpz_300_600_col_3');

    // START: 300 x 1050------------------------------------------------
    banner_upload('300x1050', 1, 'dpz_300_1050_col_1');
    banner_upload('300x1050', 2, 'dpz_300_1050_col_2');
    banner_upload('300x1050', 3, 'dpz_300_1050_col_3');

    // START: 600 x 90------------------------------------------------
    banner_upload('600x90', 1, 'dpz_600_90_col_1');
    banner_upload('600x90', 2, 'dpz_600_90_col_2');
    banner_upload('600x90', 3, 'dpz_600_90_col_3');

    // START: 728 x 90------------------------------------------------
    banner_upload('728x90', 1, 'dpz_728_90_col_1');
    banner_upload('728x90', 2, 'dpz_728_90_col_2');
    banner_upload('728x90', 3, 'dpz_728_90_col_3');

    // START: 980 x 90------------------------------------------------
    banner_upload('980x90', 1, 'dpz_980_90_col_1');
    banner_upload('980x90', 2, 'dpz_980_90_col_2');
    banner_upload('980x90', 3, 'dpz_980_90_col_3');

    // upload banner for trader
    // upload banner 160 X 600-------------------------
    banner_upload('160x600', 1, 'trader_dpz_160_600_col_1');
    banner_upload('160x600', 2, 'trader_dpz_160_600_col_2');
    banner_upload('160x600', 3, 'trader_dpz_160_600_col_3');

    // START: 200 x 200------------------------------------------------
    banner_upload('200x200', 1, 'trader_dpz_200_200_col_1');
    banner_upload('200x200', 2, 'trader_dpz_200_200_col_2');
    banner_upload('200x200', 3, 'trader_dpz_200_200_col_3');

    // START: 250 x 250------------------------------------------------
    banner_upload('250x250', 1, 'trader_dpz_250_250_col_1');
    banner_upload('250x250', 2, 'trader_dpz_250_250_col_2');
    banner_upload('250x250', 3, 'trader_dpz_250_250_col_3');

    // START: 300 x 250------------------------------------------------
    banner_upload('300x250', 1, 'trader_dpz_300_250_col_1');
    banner_upload('300x250', 2, 'trader_dpz_300_250_col_2');
    banner_upload('300x250', 3, 'trader_dpz_300_250_col_3');

    // START: 300 x 600------------------------------------------------
    banner_upload('300x600', 1, 'trader_dpz_300_600_col_1');
    banner_upload('300x600', 2, 'trader_dpz_300_600_col_2');
    banner_upload('300x600', 3, 'trader_dpz_300_600_col_3');

    // START: 300 x 1050------------------------------------------------
    banner_upload('300x1050', 1, 'trader_dpz_300_1050_col_1');
    banner_upload('300x1050', 2, 'trader_dpz_300_1050_col_2');
    banner_upload('300x1050', 3, 'trader_dpz_300_1050_col_3');

    // START: 600 x 90------------------------------------------------
    banner_upload('600x90', 1, 'trader_dpz_600_90_col_1');
    banner_upload('600x90', 2, 'trader_dpz_600_90_col_2');
    banner_upload('600x90', 3, 'trader_dpz_600_90_col_3');

    // START: 728 x 90------------------------------------------------
    banner_upload('728x90', 1, 'trader_dpz_728_90_col_1');
    banner_upload('728x90', 2, 'trader_dpz_728_90_col_2');
    banner_upload('728x90', 3, 'trader_dpz_728_90_col_3');

    // START: 980 x 90------------------------------------------------
    banner_upload('980x90', 1, 'trader_dpz_980_90_col_1');
    banner_upload('980x90', 2, 'trader_dpz_980_90_col_2');
    banner_upload('980x90', 3, 'trader_dpz_980_90_col_3');

    // enable disable banner---------------------------------
    $(document).on("click", ".btn-banner-status", function () {
        let request_for = ($(this).data('status') === 'enable') ? 'disable' : 'enable';
        let reque_url = '/admin/settings/banner-enable-disable';
        let data = { id: $(this).data('id'), request_for: request_for };
        confirm_alert('Are you confirm to ' + request_for, 'If you want to ' + request_for + ', Please click OK', reque_url, data, 'Banner ' + request_for, table);
    })
    // delete banner---------------------------------
    $(document).on("click", ".btn-delete-banner", function () {
        let reque_url = '/admin/settings/banner-delete';
        let data = { id: $(this).data('id') };
        confirm_alert('Are you confirm to delete?', 'If you want to permanently delete, Please click OK', reque_url, data, 'Banner delete', table);
    })
    // enabled drag drop opiton
    $(document).on("mouseenter mouseleave", "#dpz-address-proof", function () {
        if (document_type_err == false && client_type_err == false && client_err == false && status_err == false && id_type_err == false && incode_err == false && issue_date_err == false && expire_date_err == false) {
            $(this).prop("disabled", false);
        }
        else {
            $(this).prop("disabled", true);
        }
    })

    // slide left buttons banner
    // -----------------------------------------------------------------------------
    $(document).on('mouseenter', ".banner-img-container", function () {
        $(this).find('.buttons-banners').animate({
            right: 0
        });
    })
    $(document).on('mouseleave', ".banner-img-container", function () {

        $(this).find('.buttons-banners').animate({
            right: '-64px'
        });
    })
})(window, document, jQuery);