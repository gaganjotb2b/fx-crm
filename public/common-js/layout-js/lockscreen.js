
// display extends popup
// var minutesLabel = document.getElementById("minutes");
var secondsLabel = $("#minutes");
var modalLabel = $("#modalMinutes");
// var totalSeconds = document.getElementById("envSessionTime").textContent;
var totalSeconds = $('#envSessionTime').data('session');
// console.log(totalSeconds);

// setInterval(setTime, 500);
setInterval(() => {
    setTime()
}, 1000);
var i = 0;
function setTime() {
    --totalSeconds;
    if (totalSeconds <= 30) {
        $(modalLabel).html(pad(totalSeconds % 60));
        $(secondsLabel).html(pad(parseInt(totalSeconds / 60)) + ":" + pad(totalSeconds % 60) + " " + "Seconds after Will be lock ");
        if (i == 0) {
            $('#sesstionLockButton').click();
            i = 1;
        }
        $("#modal-second").html(pad(totalSeconds % 60));
        $("#modal-minute").html(pad(parseInt(totalSeconds / 60)));
    }
}
function pad(val) {
    var valString = val + "";
    if (valString.length < 2) {
        return "0" + valString;
    } else {
        return valString;
    }
}
$('#session_button_extent').click(function () {
    window.location.reload();
})