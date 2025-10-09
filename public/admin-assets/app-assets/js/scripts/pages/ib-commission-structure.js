

$(function () {
    // data table ib commission structure
    // ------------------------------------------------------------------------------
    let ib_level = $("#add-ib-commission-structure").data('ib_level');
    let columns = [
        { "data": "currency" },
        { "data": "timing" },
        { "data": "total" },
        { "data": "actions" },
    ];
    var last_lement;
    if (columns.length > 1) {
        last_lement = columns.pop();
        for (let i = 1; i <= ib_level; i++) {
            columns.push({ "data": "level_" + i });
        }
    }
    columns.push(last_lement);

    var cd = (new Date()).toISOString().split('T')[0];
    var commission_datatable = $('#ib-commission-structure').fetch_data({
        columns: columns,
        search:true,
        url: "/admin/ib-management/ib-commission-structure-dt?trader_group=" + $('#client_group').val() + "&ib_group=" + $('[name=customOptionsCheckableRadios]:checked').val(),
        // order_col: 1,
        order_dir: 'desc',
        form_el: false,
        dt_inside: false,
    });
    $("#ib-commission-structure").fetch_description({
        url: '/admin/ib-management/ib-commission-structure-dt/description',
        feather: true,
    });
    // create form field
    // ------------------------------------------------------
    $(document).on("click", "#add-ib-commission-structure", function () {
        let ib_level = $(this).data('ib_level');
        $("#ib-com-field").slideDown();
    })
    // END: Create form field

    // START: Delete form field
    // ----------------------------------------------------------------------------
    $(document).on('click', '.btn-delete-form-field', function () {
        $("#ib-com-field").slideUp();
    })
    // edit ib commission structure------------------------------------------------------------
    $(document).on("click", ".edit-ib-commission-structure", function () {
        let ib_level = $(this).data('ib_level');
        let ib_commission = $(this).data('commission');
        // let ib_symbol = $(this).data('symbol');
        // let ib_timing = $(this).data('timing');
        let id = $(this).data('id');


        // $("#fp-time").val(ib_timing);
        $("#structure-id").val(id);
        $("#structure-op").val('edit');
        // *************************************************************
        let edit_field = $("#ib-com-field").clone();
        // get prevous data
        let prevous_data = commission_datatable.row($(this).closest('tr')).data();
        // console.log(prevous_data);
        let $i = 0;
        $.each(prevous_data, function name(index, object) {
            if ($i == 0) {
                edit_field.find('th').eq($i).find('.form-control').val($(object).find('span').text())
            } else {
                edit_field.find('th').eq($i).find('.form-control').val(object)
            }
            if ($i == parseInt(ib_level + 2)) {
                return false;
            }
            $i++;
        })
        // display edit toutchspin

        edit_field.attr('id', "#ib-com-field-edit");
        edit_field.find('.btn-structure-save').attr('id', "submit-request2");
        edit_field.find('.btn-structure-save').data('btnid', "submit-request2");

        edit_field.find('.bootstrap-touchspin-up').addClass('bootstrap-touchspin-up-cu')
        edit_field.find('.bootstrap-touchspin-down').addClass('bootstrap-touchspin-down-cu')
        edit_field.find('.btn-delete-form-field').addClass('btn-delete-form-field-cu')

        // $("#ib-com-field").slideDown();
        $(this).closest('tr').replaceWith(edit_field);
        edit_field.slideDown();

    });
    // edit toutchspin up
    $(document).on("click", ".bootstrap-touchspin-up-cu", function () {
        let current_value = parseFloat($(this).closest('.input-group').find('.form-control').val());
        let setp = parseFloat($(this).closest('.input-group').find('.form-control').data('bts-step'));
        let sum = parseFloat((current_value + setp), 2).toFixed(2);
        $(this).closest('.input-group').find('.form-control').val(sum);
        // get_total(this)
    })
    // edit toutchspin down
    $(document).on("click", ".bootstrap-touchspin-down-cu", function () {
        let current_value = parseFloat($(this).closest('.input-group').find('.form-control').val());
        let setp = parseFloat($(this).closest('.input-group').find('.form-control').data('bts-step'));
        let sum = parseFloat((current_value - setp), 2).toFixed(2);
        $(this).closest('.input-group').find('.form-control').val(sum);
        // get_total(this)
    })
    $(document).on('click', '.btn-delete-form-field-cu', function () {
        empty_input();
        commission_datatable.draw();
    })

    // delete ib commission structure-----------------------------------------
    $(document).on('click', ".btn-delete", function () {
        let request_url = '/admin/ib-management/ib-commission-structure-delete';
        let data = { id: $(this).data('id') }
        confirm_alert('Are you confirm to delete? commission structure.', 'If you wnat to permanantly delete this please click ok', request_url, data, 'Commision Structure delete', commission_datatable);
    })
    // enable disable ib commission structure---------------------------------
    $(document).on('click', ".btn-enable-disable", function () {
        let request_url = '/admin/ib-management/ib-commission-structure-block-unblock';
        let data = { id: $(this).data('id'), request_for: $(this).data('request_for') }
        confirm_alert('Are you confirm to ' + $(this).data('request_for') + '? commission structure.', 'If you wnat to  enable/disable this please click ok', request_url, data, 'Commision Structure ' + $(this).data('request_for'), commission_datatable);
    })

    // get total
    // $(document).on('input', '.ib-levels , .ib-com-total', function () {
    //     // get_total(this)
    // });

    function get_total(_this) {
        let $sum = 0;
        $(_this).closest('tr').find('.ib-levels').each(function (index, obj) {
            $sum += parseFloat($(obj).val());
        });

        // $(_this).closest('tr').find('.ib-com-total').val($total.toFixed(2));
        // $(_this).closest('tr').find('.total-hidden').val($total.toFixed(2));
        let $total = $(_this).closest('tr').find('.ib-com-total').val();
        if ($total < $sum) {
            // alert('Sum  of IB Level Commision must be less than or Equal to Total!')
            Swal.fire({
                icon: 'warning',
                title: 'Please fix this problem first!',
                html: 'Sum  of Commision must be less than or Equal to Total! If you want to save with this problem, simply click ok & continue',
                customClass: {
                    confirmButton: 'btn btn-danger'
                }
            });
        }
    }

});
$(window).on('load', function () {
    empty_input();
});

function empty_input() {
    $('.ib-levels,.ib-com-total, .total-hidden').each(function (index, obj) {
        $(obj).val(0);
    })
    $("#ib-com-field").find("#symbol").val("")
    $("#ib-com-field").find("#fp-time").val("")
}