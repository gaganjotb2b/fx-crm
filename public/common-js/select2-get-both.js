// get IB details
$(document).on('keypress', '.select2-search__field', function (e) {
    if (e.which === 13) {
        e.preventDefault();
    }
});

// $('#recipient').select2('destroy')
$(".select2-both").select2({
    tags: false,
    // dropdownParent: $('#sub-ib-modal'),
    templateResult: formatOption,
    selectOnClose: true,
    language: {
        noResults: function () {
            return "Enter Email to search here";
        }
    },
    ajax: {
        url: "/search/client/users/both",
        // type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                searchTerm: params.term // search term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    }
});
// option description for select2
function formatOption(option) {
    var $option = $(
        '<div><strong>' + option.text + '</strong></div><div>' + option.title +
        '</div><div><strong>Name: </strong>' + option.name + '</div>'
    );
    return $option;
};