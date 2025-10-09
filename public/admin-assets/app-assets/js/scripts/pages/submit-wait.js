// get session data
function submit_wait(button_selector, session_time=null) {
    wait_operation(button_selector, session_time);    
}

// wait macnisam
// -------------------------------------------------------------------------------------------------------------
function wait_operation(button_selector, session_time=null) {
    let submit_wait = $(button_selector).data('submit_wait');
    let button_label = $(button_selector).data('label');
    let form = $(button_label).data("form");
    let time_left;
    if (session_time!=null) {
        time_left = session_time;
        const button_interval = setInterval(() => {
            if (time_left > 0) {
                time_left--;
                $(button_selector).prop('disabled',true).html('Please wait <span class="badge bg-warning rounded-pill ms-auto">'+getTime(time_left)+'</span>');
            }
            else{
                $(form).trigger('reset');
                $(button_selector).prop('disabled',false).html(button_label);
                clearInterval(button_interval);
            }
        }, 1000);
    } else {
        time_left = submit_wait;
        const button_interval=setInterval(() => {
            if (time_left > 0) {
                time_left--;
                $(button_selector).prop('disabled',true).html('Please wait <span class="badge bg-warning rounded-pill ms-auto">'+getTime(time_left)+'</span>');
            }
            else{
                $(form).trigger('reset');
                $(button_selector).prop('disabled',false).html(button_label);
                clearInterval(button_interval);
            }
        }, 1000);
    }
}




// calculate time from second
// --------------------------------------------------------------------------------------------------------------
function getTime(seconds) {

    //a day contains 60 * 60 * 24 = 86400 seconds
    //an hour contains 60 * 60 = 3600 seconds
    //a minut contains 60 seconds
    //the amount of seconds we have left
    var leftover = seconds;

    //how many full days fits in the amount of leftover seconds
    var days = Math.floor(leftover / 86400);

    //how many seconds are left
    leftover = leftover - (days * 86400);

    //how many full hours fits in the amount of leftover seconds
    var hours = Math.floor(leftover / 3600);

    //how many seconds are left
    leftover = leftover - (hours * 3600);

    //how many minutes fits in the amount of leftover seconds
    var minutes = Math.floor(leftover / 60);

    //how many seconds are left
    leftover = leftover - (minutes * 60);
    return(days + ':' + hours + ':' + minutes + ':' + leftover);
}