(function (window, undefined) {
    'use strict';

    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */
    function notify($status, $meessage, $title) {
        var isRtl = $('html').attr('data-textdirection') === 'rtl';
        toastr[$status]($meessage, $title, {
            closeButton: true,
            tapToDismiss: false,
            progressBar: true,
            hideDuration: 3000,
            rtl: isRtl
        });
    }


    // form submit by ajax
    $.fn.form_submit = function (options, callback) {
        var settings = $.extend({
            file: false,
            dropzone: false,
            datatable: false,
            url: false,
            method: 'POST',
            form_id: 'form',
            title: 'Notification',
            multiple: true,
            reset: true,
        }, options)
        let request_url = '';
        let button_text = 'Submit';
        let $this = this;
        this.on('click', function () {
            button_text = $(this).text();
            let loader_id = $(this).data('loader');
            let loader = $("#" + loader_id).data('loader');
            $(this).html(loader);
            $("#" + settings.form_id).submit();
        });
        // dropzonde file upload start
        if (settings.dropzone !== false) {
            var dropzones = [];
            $(settings.dropzone).each(function (i, el) {
                const name = $(el).data('name')
                var myDropzone = new Dropzone(el, {
                    url: window.location.pathname,
                    autoProcessQueue: false,
                    uploadMultiple: false,
                    parallelUploads: false,
                    maxFiles: 1,
                    method: 'post',
                    paramName: name,
                    acceptedFiles: 'image/*,application/pdf',
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                    },
                    addRemoveLinks: true,
                });
                dropzones.push(myDropzone)
            });
        }
        // end dropzone
        $(document).on('submit', "#" + settings.form_id, function (e) {
            let request_url = '';
            if (settings.url == false) {
                request_url = $(this).attr('action');
            } else {
                request_url = settings.url;
            }
            let form_data = new FormData(this);
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // append dropzone file
            if (settings.dropzone !== false) {
                dropzones.forEach(dropzone => {
                    let { paramName } = dropzone.options
                    dropzone.files.forEach((file, i) => {

                        form_data.append(paramName + '[' + i + ']', file)
                    })
                    dropzone.processQueue();
                })
            }
            $.ajax({
                url: request_url,
                method: settings.method,
                processData: false,
                contentType: false,
                dataType: 'json',
                data: form_data,
                success: function (data) {
                    if (data.status) {
                        notify('success', data.message, settings.title);
                        if (settings.reset == true) {
                            $("#" + settings.form_id).trigger('reset');
                        }

                        // check has any datatable
                        if (settings.datatable !== false) {
                            settings.datatable.draw();
                        }
                        // check if dropzone have
                        if (settings.dropzone !== false) {
                            dropzones.forEach(dropzone => {
                                dropzone.removeAllFiles(true);
                            });
                        }
                    }
                    else {
                        notify('error', data.message, settings.title);
                    }
                    $("#" + settings.form_id).z_validation({
                        errors: data.errors,
                    });
                    $this.html(button_text);
                    // callback functions
                    if (typeof callback == 'function') { // make sure the callback is a function
                        callback(data); // brings the scope to the callback
                    }
                }
            })
        });
    }

    // validation 
    $.fn.z_validation = function (options) {
        var settings = $.extend({
            errors: [],
        }, options);
        let $this = this;
        // all input validation
        let error_element = '<span class="error">This field is required.</span>';
        this.find('input').each(function (index, obj) {
            let field_name = $(obj).attr('name');
            // check has input errors
            if (settings.errors.hasOwnProperty(field_name)) {
                $($this).find("input[name='" + field_name + "']").addClass('is-invalid');
                $($this).find("input[name='" + field_name + "']").next('.error').remove();
                $($this).find("input[name='" + field_name + "']").after('<span class="error invalid-feedback">' + settings.errors[field_name][0] + '</span>');

            } else {
                $($this).find("input[name='" + field_name + "']").removeClass('is-invalid');
                $($this).find("input[name='" + field_name + "']").closest('.input-wrapper').find('.error').remove();

            }
        });
        this.find('textarea').each(function (index, obj) {
            let field_name = $(obj).attr('name');
            // check has input errors
            if (settings.errors.hasOwnProperty(field_name)) {

                // for textarea
                $($this).find("textarea[name='" + field_name + "']").addClass('is-invalid');
                $($this).find("textarea[name='" + field_name + "']").next('.error').remove();
                $($this).find("textarea[name='" + field_name + "']").after('<span class="error invalid-feedback">' + settings.errors[field_name][0] + '</span>');
            } else {

                // for textarea
                $($this).find("textarea[name='" + field_name + "']").removeClass('is-invalid');
                $($this).find("textarea[name='" + field_name + "']").closest('.input-wrapper').find('.error').remove();
            }
        });
        // dropzone
        this.find('.dropzone').each(function (index, obj) {
            let field_name = $(obj).data('name');
            // check has input errors
            if (settings.errors.hasOwnProperty(field_name)) {

                // for dropzone
                $($this).find("div[data-name='" + field_name + "']").addClass('is-invalid');
                $($this).find("div[data-name='" + field_name + "']").next('.error').remove();
                $($this).find("div[data-name='" + field_name + "']").after('<span class="error invalid-feedback">' + settings.errors[field_name][0] + '</span>');
            } else {

                // for div dropzone
                $($this).find("div[data-name='" + field_name + "']").removeClass('is-invalid');
                $($this).find("div[data-name='" + field_name + "']").closest('.input-wrapper').find('.error').remove();
            }
        });
        // select2
        this.find('select').each(function (index, obj) {
            if ($(obj).hasClass('select2-hidden-accessible')) {
                let field_name = $(obj).attr('name');
                // check has input errors
                if (settings.errors.hasOwnProperty(field_name)) {
                    // for select2
                    $($this).find("select[name='" + field_name + "'] , select[name='" + field_name + "\\[\\]']").next('.select2-container').addClass('is-invalid');
                    $($this).find("select[name='" + field_name + "'] , select[name='" + field_name + "\\[\\]']").next('.select2-container').next('.error').remove();
                    $($this).find("select[name='" + field_name + "'] , select[name='" + field_name + "\\[\\]']").next('.select2-container').after('<span class="error invalid-feedback">' + settings.errors[field_name][0] + '</span>');

                } else {
                    // for select one
                    $($this).find("select[name='" + field_name + "'] , select[name='" + field_name + "\\[\\]']").next('.select2-container').removeClass('is-invalid');
                    $($this).find("select[name='" + field_name + "'] , select[name='" + field_name + "\\[\\]']").next('.select2-container').closest('.input-wrapper').find('.error').remove();
                }
            } else {
                let field_name = $(obj).attr('name');
                // check has input errors
                if (settings.errors.hasOwnProperty(field_name)) {
                    // for dropzone
                    $($this).find("select[name='" + field_name + "']").addClass('is-invalid');
                    $($this).find("select[name='" + field_name + "']").next('.error').remove();
                    $($this).find("select[name='" + field_name + "']").after('<span class="error invalid-feedback">' + settings.errors[field_name][0] + '</span>');

                } else {
                    // for select one
                    $($this).find("select[name='" + field_name + "']").removeClass('is-invalid');
                    $($this).find("select[name='" + field_name + "']").closest('.input-wrapper').find('.error').remove();
                }
            }

        });
    }

    // ************************************************************
    // get subcategory by category
    //=============================================================>
    $.fn.sub_category = function (options) {
        // 	var data_option;
        var settings = $.extend({
            url: '',
            data: {},
            element: '#category',
            modal: false,
        }, options);
        // select2 search item 
        $(document).on('keypress', '.select2-search__field', function (e) {
            if (e.which === 13) {
                e.preventDefault();
            }
        });

        this.select2('destroy')
        this.select2({
            tags: false,
            dropdownParent: (settings.modal !== false) ? settings.modal : '',
            templateResult: formatOption,
            selectOnClose: true,
            language: {
                noResults: function () {
                    return "Type here";
                }
            },
            ajax: {
                url: settings.url,
                // type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search_term: params.term, // search term
                        category_id: $(settings.element).val()
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
                '<div><strong>' + option.text + '</strong></div>'
            );
            return $option;
        };
    }
    // get selec2 options
    $.fn.select2_options = function (options) {
        // 	var data_option;
        var settings = $.extend({
            url: '',
            element: '#category',
            modal: false,
        }, options);
        // select2 search item 
        $(document).on('keypress', '.select2-search__field', function (e) {
            if (e.which === 13) {
                e.preventDefault();
            }
        });

        this.select2('destroy')
        this.select2({
            tags: false,
            dropdownParent: (settings.modal !== false) ? settings.modal : '',
            templateResult: formatOption,
            selectOnClose: true,
            language: {
                noResults: function () {
                    return "Type here";
                }
            },
            ajax: {
                url: settings.url,
                // type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search_term: params.term, // search term
                        parent_id: $(settings.element).val()
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
                '<div><strong>' + option.text + '</strong></div>'
            );
            return $option;
        };
    }

    //make plugins for load post
    // get range
    function range(start, end) {
        var array = new Array();
        for (let i = start; i <= end; i++) {
            array.push(i);
        }
        return array;
    }
    function pagination(data, limit = null, current = null, adjacents = null) {
        let result = new Array();
        if (typeof (data, limit) != 'undefined') {
            result = range(1, Math.ceil(data / limit));

            if (typeof (current, adjacents) != "undefined") {
                if ((adjacents = (Math.floor(adjacents / 2) * 2 * 1)) >= 1) {
                    let last = (Math.max(0, Math.min((result.length) - adjacents, parseInt(current) - Math.ceil(adjacents / 2)))) + adjacents;
                    // last = last-2;
                    if (current == 1 & (data / limit) < adjacents) {
                        last = Math.ceil(data / limit);
                    }
                    result = result.slice(Math.max(0, Math.min((result.length) - adjacents, parseInt(current) - Math.ceil(adjacents / 2))), last);
                    // console.log(last);
                    if ((limit * last) < data) {
                        result.push('... ...');
                    }
                }
            }
        }
        // console.log(result);
        return result;
    }
    var element;
    function data_list_ajax(settings, __this) {
        $(__this).html('');
        $(__this).find(settings.item).remove();
        $.ajax({
            url: settings.ajax + '?limit=' + settings.per_page + '&current=' + ((settings.per_page * settings.current) - settings.per_page) + "&order_col=" + settings.order_col + "&order_dir=" + settings.order_dir,
            method: settings.method,
            dataType: settings.dataType,
            data: settings.data,
            success: function (data) {
                $.each(data.items, function (index, value) {
                    const elements = element.clone(true);
                    // element = elements;
                    // console.log(elements);
                    const id = $(elements).attr('id');
                    $(elements).find(settings.title).text(value.title);
                    $(elements).find(settings.image).attr('src', value.image);
                    $(elements).find(settings.price).text(value.price);
                    $(elements).find(settings.description).text(value.description);
                    $(elements).attr('id', id + index);
                    $(elements).appendTo(__this);
                });
                // remove cloned item
                // $(__this).find(settings.item).css({
                // 	'display': 'none'
                // });

                var footer_visuality = 'd-none';
                if (data.total_record > settings.per_page) {
                    footer_visuality = 'd-block';
                }
                const paging = $(settings.paging).clone(true, true);
                $(settings.paging).attr('data-total', data.total_record);
                $(settings.paging).attr('data-listperpage', settings.per_page);
                let list_array = pagination(data.total_record, settings.per_page, settings.current, 4);
                const list_prev = paging.children('.page-item:first-child').clone(true, true);
                const list_last = paging.children('.page-item:last-child').clone(true, true);
                $(settings.paging).empty();
                list_prev.appendTo(settings.paging).addClass('data-list-page-item').attr('data-page', 'prev');;

                $.each(list_array, function (index, value) {
                    var list_active = '', paging_dots = '';
                    const list_paging = paging.children('.page-item:nth-child(2)').clone(true, true);
                    $(list_paging).find('a').text(value);
                    $(list_paging).attr('data-page', value);
                    list_paging.removeClass('active').addClass('data-list-page-item');
                    if (settings.current == value) {
                        list_paging.addClass('active');
                    }
                    if (value === '... ...') {
                        paging_dots = 'dl-paging-dots';
                    }
                    list_paging.appendTo(settings.paging);

                })
                list_last.appendTo(settings.paging).addClass('data-list-page-item').attr('data-page', 'next');

                // if (__this.next(".pagination").length) {
                // 	__this.next(".pagination").remove();
                // }

                // //change disable background for netx
                // if ((settings.per_page * settings.current) >= data.total_record) {
                // 	__this.next().find(".next").css({
                // 		'background-color': '#00000087',
                // 		'cursor': 'context-menu',
                // 	});
                // }
                // // change disable background for prev
                // if (settings.current == 1) {
                // 	__this.next().find(".prev").css({
                // 		'background-color': '#00000087',
                // 		'cursor': 'context-menu',
                // 	});
                // }
                // list_footer = '';
            },
            error: function (data) {
                console.log(data.status + ' ' + data.statusText);
            }
        });
    }
    var list_options = '';
    var dataList;
    $.fn.dynamic_paging = function (options) {
        var __this = this;
        dataList = this;
        list_options = options;
        const settings = $.extend({
            ajax: '',
            method: 'GET',
            dataType: 'JSON',
            per_page: 10,
            item: '#dynamic-item',
            image: 'img',
            image_multiple: false,
            title: '.item-title',
            description: '.item-description',
            price: '.item-price',
            ratings: '.item-ratings',
            paging: '.item-paging',
            order_col: 'id',
            order_dir: 'DESC',
            current: 1,
            data: ''
        }, options);
        element = __this.find(settings.item).clone(true, true);
        data_list_ajax(settings, __this)
        return this;
    }
    $.fn.draw_list = function (current = 1) {
        var __this = this;
        var settings = $.extend({
            ajax: '',
            method: 'GET',
            dataType: 'JSON',
            serverSide: false,
            per_page: 10,
            current: current,
            item: '#dynamic-item',
            image: 'img',
            image_multiple: false,
            title: '.item-title',
            description: '.item-description',
            price: '.item-price',
            ratings: '.item-ratings',
            paging: '.item-paging',
            order_col: 'id',
            order_dir: 'DESC',
            data: '',
        }, list_options);

        data_list_ajax(settings, __this);
        return this;
    }
    // pagination click
    $(document).on("click", ".data-list-page-item", function () {
        // var dataList = $(this).closest(".pagination").prev();
        let current_page, total_record, per_page;
        current_page = $(this).find('a').text();
        if ($(this).data('page') === '... ...') {
            $(this).addClass('dl-paging-dots');
        }
        // console.log($(this).data('page'));
        if ($(this).data('page') === 'next') {
            current_page = $(this).closest("ul").find('.active').find('a').text();
            current_page = (parseInt(current_page) + 1);
            console.log(current_page);
        }
        else if ($(this).data('page') === 'prev') {
            current_page = $(this).closest('ul').find('.active').find('a').text();
            current_page = current_page - 1;
        }
        total_record = $(this).closest('ul').data('total');
        per_page = $(this).closest('ul').data('listperpage');
        // console.log("current_page*list" + (((current_page) * per_page) - per_page));
        if ((((current_page * per_page) - per_page) < total_record) && current_page > 0) {
            dataList.draw_list(current_page);
        }

    });

    // serialize object functin
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(window);

// dropzone file upload
