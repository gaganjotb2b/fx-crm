$(function () {

    // change area selection options
    // -------------------------------------------------------------------------------
    if ($('#clieant-area').is(':checked')) {
        $("#multiple-countries").slideUp();
    }
    else{
        $("#multiple-countries").slideDown();
    }
    // when change the switch
    $(document).on('change','#clieant-area',function () {
        if ($(this).is(':checked')) {
            $("#multiple-countries").slideUp();
        }
        else{
            $("#multiple-countries").slideDown();
        }
    });

    // change manger goups
    // when change type
    // ---------------------------------------------------------------------------
    $(document).on('change','#type',function () {
        let type = $(this).val();
        console.log(type);
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
        $.ajax({
            type: "GET",
            url: '/admin/manager-settings/get-manager-group-type/'+type,
            dataType: 'json',
            success: function (data) {
                $("#group-type").html(data);
            }
        });
    });

    // first time load the manger group
    // -----------------------------------------------------------------------
    $("#type").trigger("change");
});