function crypto_wizer_valid(form_id,wizer_no) {
    let form_data = $("#"+form_id).serializeArray();
    form_data.push({ name: 'wizer_no', value: wizer_no });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    return  $.ajax({
        dataType: 'json',
        method: 'POST',
        url: '/user/deposit/crypto-deposit-request',
        data: form_data,
        success: function(data) {
            if (data.status == false) {
                notify('error', data.message, 'Crypto Deposit');
            }
            $.validator("crypto-deposit-form", data.errors);
            if (data.status==true && wizer_no==1) {
                $("#btn-js-next").trigger("click");
                $("#btn-step-2").prop("disabled",false);
            }
        }
    });
}