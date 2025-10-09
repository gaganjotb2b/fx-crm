//right click disable
document.addEventListener("contextmenu", function (e) {
    e.preventDefault();
}, false);
//ctrl+shit+i off
document.addEventListener("keydown", function (event) {
    if (event.ctrlKey && event.shiftKey && event.code === "KeyI") {
        event.preventDefault();
    }
    if (event.ctrlKey && event.shiftKey && event.code === "KeyJ") {
        event.preventDefault();
    }

    if (event.ctrlKey && event.shiftKey && event.code === "KeyK") {
        event.preventDefault();
    }
   
});
 //off F12
 document.addEventListener("keydown", function (event) {
    if (event.keyCode === 123) {
        event.preventDefault();
    }
});