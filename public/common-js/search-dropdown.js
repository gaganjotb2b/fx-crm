$.fn.get_client = function (options) {
    var settings = $.extend({
        type: '0',
        value: '',
        selector:""
    }, options);
    $.ajax({
        type: "GET",
        url: '/search/get-client/' + settings.type + '/user/' + settings.value,
        dataType: 'json',
        success: function (data) {
            // console.log($(".get-client").find('a').length);
            if ($(".get-client").closest('div').find('a').length) {
                
                $(".get-client").closest('div').find('a').remove();
            }
            // console.log(data);
            $(".get-client").after(data.users);
        }
    });
    return this;
}

$(document).on("input",'.get-client',function () {
    let type = "trader";
    var input, filter, ul, li, a, i;
    // input = document.getElementById("myInput");
    filter = this.value;
    // console.log(filter);
    // div = document.getElementById("myDropdown");
    $(document).get_client({
        type: type,
        value: filter,
        selector:$(this)
    }
    );
    // a = div.getElementsByTagName("a");
})
function filterFunction(type=null) {
    
}
$(document).on("click", ".fill-input", function (event) {
    event.preventDefault();
    let value = $(this).data('value');
    $(this).closest('#myDropdown').find('#myInput').val(value)
    $(this).closest('#myDropdown').find('.fill-input').remove();
});
$(document).on("click", ".fill-input", function (event) {
    event.preventDefault();
    let value = $(this).data('value');
    $(this).closest('#myDropdown2').find('#address-client-email').val(value)
    $(this).closest('#myDropdown2').find('.fill-input').remove();
});
