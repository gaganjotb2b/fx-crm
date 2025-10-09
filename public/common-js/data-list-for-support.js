// const beforeSend = [];
(function ($) {
    var list_footer = '';
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
            // console.log(Math.ceil(data/limit));
            result = range(1, Math.ceil(data / limit));
            // console.log(result);
            if (typeof (current, adjacents) != "undefined") {
                if ((adjacents = (Math.floor(adjacents / 2) * 2 * 1)) >= 1) {
                    // console.log(adjacents);
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
    
    function data_list_ajax(settings, __this) {
        // console.log('perpage=' + settings.listPerPage);
        // console.log('current=' + settings.current);
        // console.log("limit: =" + ((settings.listPerPage * settings.current) - settings.listPerPage));
        var newUrl = settings.url + '?limit=' + settings.listPerPage + '&current=' + ((settings.listPerPage * settings.current) - settings.listPerPage)+'&'+settings.filterUrl;
        $.ajax({
            url: newUrl,
            method: settings.method,
            dataType: settings.dataType,
            success: function (data) {
                var list_item = "";
                $.each(data.list, function (index, value) {
                    list_item += '<li class="d-flex user-mail mail-read">' + value + '</li>';
                });
                var footer_visuality = 'd-flex'
                if (data.totalRecord==settings.listPerPage) {
                    footer_visuality = 'd-flex';
                }
                list_footer += '<div class="data-list-footer '+footer_visuality+'">';
                list_footer += '<div class="data-list-total">';
                list_footer += '<div class="data-list-count-label">Total </div>';
                list_footer += '<div class="data-list-count">' + data.totalRecord + '</div>';
                list_footer += '<div> Records Found</div>';
                list_footer += '</div >';
                list_footer += '<ul class="lgrp-paginate" data-total="' + data.totalRecord + '" data-listperpage="' + settings.listPerPage + '">';
                list_footer += '<li class="data-list-page-item prev" data-page="prev">&laquo;</li>';
                let list_array = pagination(data.totalRecord, settings.listPerPage, settings.current, 4);
                $.each(list_array, function (index, value) {
                    var list_active = '', paging_dots = '';
                    if (settings.current == value) {
                        list_active = 'dl-active';
                    }
                    if (value === '... ...') {
                        // console.log(index);
                        paging_dots = 'dl-paging-dots';
                    }
                    list_footer += '<li class="data-list-page-item ' + list_active + ' ' + paging_dots + '" data-page="' + value + '">' + value + '</li>';

                })

                list_footer += '<li class="data-list-page-item next" data-page="next">&raquo;</li>';
                list_footer += '</ul>';
                list_footer += '</div> ';

                __this.html(list_item);
                if (__this.next(".data-list-footer").length) {
                    __this.next(".data-list-footer").remove();
                }

                __this.after(list_footer);
                //change disable background for netx
                if ((settings.listPerPage * settings.current) >= data.totalRecord) {
                    __this.next().find(".next").css({
                        'background-color': '#00000087',
                        'cursor': 'context-menu',
                    });
                }
                // change disable background for prev
                if (settings.current == 1) {
                    __this.next().find(".prev").css({
                        'background-color': '#00000087',
                        'cursor': 'context-menu',
                    });
                }
                list_footer = '';
            },
            error: function (data) {
                console.log(data.status + ' ' + data.statusText);
            }
        });
    }
    var list_options = '';
    $.fn.data_list = function (options) {
        var list_item;
        list_options = options;
        var __this = this;
        var settings = $.extend({
            url: '',
            method: 'GET',
            dataType: 'JSON',
            serverSide: false,
            listPerPage: 10,
            current: 1,
            filterUrl : '',
        }, options);

        if (settings.serverSide === true) {
            data_list_ajax(settings, __this)
        }

        return this;
    }
    $.fn.draw_list = function (current = 1) {
        var __this = this;
        var settings = $.extend({
            url: '',
            method: 'GET',
            dataType: 'JSON',
            serverSide: false,
            listPerPage: 10,
            current: current
        }, list_options);

        if (settings.serverSide === true) {
            data_list_ajax(settings, __this);
        }
        return this;
    }
    $(document).on("click", ".data-list-page-item", function () {
        var dataList = $(this).closest(".data-list-footer").prev();
        var current, totalRecord, listPerPage;
        // console.log(dataList);
        current = $(this).data('page');
        if ($(this).data('page') === '... ...') {
            $(this).addClass('dl-paging-dots');
        }
        if ($(this).data('page') === 'next') {
            current = $(this).closest("ul").find('.dl-active').data('page');
            current = current + 1;
        }
        else if ($(this).data('page') === 'prev') {
            current = $(this).closest('ul').find('.dl-active').data('page');
            current = current - 1;
        }
        totalRecord = $(this).closest('ul').data('total');
        listPerPage = $(this).closest('ul').data('listperpage');
        // console.log("current*list" + (((current)*listPerPage)-listPerPage));
        if ((current * listPerPage) - listPerPage < totalRecord && current > 0) {
            dataList.draw_list(current);
        }

    })
}(jQuery));