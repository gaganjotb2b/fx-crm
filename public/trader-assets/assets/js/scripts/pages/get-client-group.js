function get_client_group(platform, account_type, op, client_group = null) {
    let form_data;
    if (op === 'server') {
        form_data = { platform: platform, account_type: account_type, op: op };
    }
    else {
        form_data = { client_group: client_group, account_type: account_type, op: 'client-group' }
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: 'post',
        dataType: 'json',
        url: '/user/trading-account/get-client-group',
        data: form_data,
        success: function (data) {
            if (op === 'server') {
                $("#client-group").html(data.account_type);
                $("#leverage").html(data.leverage);
                $("#platform-logo").attr('src', data.platform_logo);
                
            }
            else {
                $("#leverage").html(data.leverage);
            }
          
            // if(data.account_type){
            //     if (document.getElementById('client-group')) {
            //         var client_group = document.getElementById('client-group');
            //         const clientChoice = new Choices(client_group);
            //     }
            // }

            // if(data.leverage){
            //     if (document.getElementById('leverage')) {
            //         var leverage = document.getElementById('leverage');
            //         const leverageChoice = new Choices(leverage);
            //     }
            // }
            
        }
    });
}

