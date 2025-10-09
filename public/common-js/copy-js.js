// copy function------------------------
function copy_to_clipboard(containerid) {
  if (document.selection) {
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("copy");
    // navigator.clipboard.writeText(range);
  } else if (window.getSelection) {
    var range = document.createRange();
    range.selectNode(document.getElementById(containerid));
    window.getSelection().addRange(range);
    document.execCommand("copy");
    // navigator.clipboard.writeText(range);
    //   alalert("Text has been copied, now paste in the text-area")
    notify('success', 'Copied Successfully, Please save it your safe zone', 'Copy to Clipboard')
  }
}


// copy to clipboard
$(document).ready(function() {
    $(document).on("focus",".copy_clipboard",function() {
        $(this).select();
        var type = $(this).attr("type");
        try {
            $(this).attr("type", "text");
            document.execCommand("copy");
            $(this).attr("type", type);
        } catch (error) {
        }
    })
});