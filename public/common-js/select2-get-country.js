// get IB details
$(document).on('keypress', '.select2-search__field', function (e) {
    if (e.which === 13) {
        e.preventDefault();
    }
});

// $('#recipient').select2('destroy')
$("#modern-country").select2({
    tags: false,
    dropdownParent: $('#modal-update-admin'),
    // templateResult: formatOption,
    selectOnClose: true,
    language: {
        noResults: function () {
            return "Enter country to search here";
        }
    },
    ajax: {
        url: "/search/country",
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

$.fn.get_country = function (options) {
    var settings = $.extend({
        modal_id: "#my-modal",
        url: "/search/country",
    }, options);
    this.select2({
        tags: false,
        dropdownParent: $(settings.modal_id),
        // templateResult: formatOption,
        selectOnClose: true,
        language: {
            noResults: function () {
                return "Enter country to search here";
            }
        },
        ajax: {
            url: settings.url,
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
}
// select2 get any data
$.fn.get_select2 = function (options) {
    var settings = $.extend({
        modal_id: "#my-modal",
        url: "",
        data: {},
        placeholder: 'Chose one Bank',
    }, options);
    this.select2({
        tags: false,
        placeholder: settings.placeholder,
        dropdownParent: $(settings.modal_id),
        templateSelection: function (container) {
            // $(container.element).attr("data-source", container.code);
            $('#bank').find(":selected").data('bank', container.code);
            return container.text;
        },
        selectOnClose: true,
        language: {
            noResults: function () {
                return "Enter bank to search here";
            }
        },
        ajax: {
            url: settings.url,
            // type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                let request_data = settings.data;
                request_data['searchTerm'] = params.term;
                return request_data;
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

}