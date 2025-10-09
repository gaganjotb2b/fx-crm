// enter key form submission handler
$(document).on("keydown", "form", function(event) {
    return event.key != "Enter";
});