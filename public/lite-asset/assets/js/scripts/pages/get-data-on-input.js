// get all filter user-----------
var type, data_list, input_data;
//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 700;  //time in ms (5 seconds)
function filter_user(user_type, form_element_id, data_list_id) {
    type = user_type;
    data_list = data_list_id;
    //on keyup, start the countdown
    $(document).on("keyup input", "#" + form_element_id, function () {
        input_data = $(this).val();
        clearTimeout(typingTimer);
        if ($("#" + form_element_id).val()) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        }
    });
}
// end get filter user--------------

//user is "finished typing," do something
function doneTyping() {
    // let account = $(__this).val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: 'post',
        dataType: 'json',
        url: '/user/input-user',
        data: { type: type, input_data: input_data },
        success: function (data) {
            let option = '';
            data.forEach(element => {
                option += '<option value="' + element.email + '"><div class="p-3">' + element.email + '</div></option>'
            });
            $("#" + data_list).html(option);
        }
    });
}