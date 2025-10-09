/**
 * DataTables Advanced
 */

// 'use strict';

// Advanced Search Functions Starts
// --------------------------------------------------------------------
// jQuery.noConflict();

(function ($) {
    // Datepicker for advanced filter
    var separator = ' - ',
        rangePickr = $('.flatpickr-range'),
        dateFormat = 'MM/DD/YYYY';
    var options = {
        autoUpdateInput: false,
        autoApply: true,
        locale: {
            format: dateFormat,
            separator: separator
        },
        opens: $('html').attr('data-textdirection') === 'rtl' ? 'left' : 'right'
    };

    //
    if (rangePickr.length) {
        rangePickr.flatpickr({
            mode: 'range',
            dateFormat: 'm/d/Y',
            onClose: function (selectedDates, dateStr, instance) {
                var startDate = '',
                    endDate = new Date();
                if (selectedDates[0] != undefined) {
                    startDate =
                        selectedDates[0].getMonth() + 1 + '/' + selectedDates[0].getDate() + '/' + selectedDates[0].getFullYear();
                    $('.start_date').val(startDate);
                }
                if (selectedDates[1] != undefined) {
                    endDate =
                        selectedDates[1].getMonth() + 1 + '/' + selectedDates[1].getDate() + '/' + selectedDates[1].getFullYear();
                    $('.end_date').val(endDate);
                }
                $(rangePickr).trigger('change').trigger('keyup');
            }
        });
    }

    // Advance filter function
    // We pass the column location, the start date, and the end date
    var filterByDate = function (column, startDate, endDate) {
        // Custom filter syntax requires pushing the new filter to the global filter array
        $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
            var rowDate = normalizeDate(aData[column]),
                start = normalizeDate(startDate),
                end = normalizeDate(endDate);

            // If our date from the row is between the start and end
            if (start <= rowDate && rowDate <= end) {
                return true;
            } else if (rowDate >= start && end === '' && start !== '') {
                return true;
            } else if (rowDate <= end && start === '' && end !== '') {
                return true;
            } else {
                return false;
            }
        });
    };

    // converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
    var normalizeDate = function (dateString) {
        var date = new Date(dateString);
        var normalized =
            date.getFullYear() + '' + ('0' + (date.getMonth() + 1)).slice(-2) + '' + ('0' + date.getDate()).slice(-2);
        return normalized;
    };
    // Advanced Search Functions Ends
    $.fn.z_datatable = function (options) {
        var settings = $.extend({
            dt_filter: false,
            url: '',
            columns: [],
            paginate: false,
            order_col: 0,
            order_dir: 'desc',
            form_el: '.filter-form',
            button_filter: '.btn-filter',
            button_reset: '.btn-reset',
            description_url: false,
            dt_inside: false,
            dt_inside_el: '.dt-inside',
            dt_inside_url: '',
            inside_columns: [],
            description: true,

        }, options);
        // enble rtl
        var isRtl = $('html').attr('data-textdirection') === 'rtl';
        var $this = this;
        var datatable;
        if (this.length) {
            datatable = this.DataTable({
                processing: true,
                serverSide: true,
                columns: settings.columns,
                searching: false,
                lengthChange: false,
                order: [[settings.order_col, settings.order_dir]],
                ajax: {
                    url: settings.url,
                    data: function (d) {

                        $(settings.form_el + ' input, ' + settings.form_el + ' select').each(
                            function (index, obj) {
                                let input_name = $(obj).attr('name');
                                d[input_name] = $(obj).val();
                            }
                        );
                    }
                },
                columnDefs: [
                    {
                        className: 'control',
                        orderable: true,
                        targets: 0
                    },
                    {
                        // Label
                        targets: -1,
                        render: function (data, type, full, meta) {
                            var $status_number = full['status'];
                            var $status = {
                                1: { title: 'Current', class: 'badge-light-primary' },
                                2: { title: 'Professional', class: ' badge-light-success' },
                                3: { title: 'Rejected', class: ' badge-light-danger' },
                                4: { title: 'Resigned', class: ' badge-light-warning' },
                                5: { title: 'Applied', class: ' badge-light-info' }
                            };
                            if (typeof $status[$status_number] === 'undefined') {
                                return data;
                            }
                            return (
                                '<span class="badge rounded-pill ' +
                                $status[$status_number].class +
                                '">' +
                                $status[$status_number].title +
                                '</span>'
                            );
                        }
                    }
                ],
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                orderCellsTop: true,

                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                                var data = row.data();
                                return 'Details of ' + data['full_name'];
                            }
                        }),
                        type: 'column',
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                                
                                return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                    ? '<tr data-dt-row="' +
                                    col.rowIdx +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>'
                                    : '';
                            }).join('');
                            feather.replace();
                            return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
                        }
                    }
                },
                language: {
                    paginate: {
                        // remove previous & next text from pagination
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    }
                },
                drawCallback: function (settings) {
                    feather.replace();
                }
            });
            // click filter button
            $(document).on('click', settings.button_filter, function () {
                datatable.draw();
            })
            $(document).on("click", settings.button_reset, function () {
                $(settings.form_el).trigger('reset');
                datatable.draw();
            })
            // datatable description
            jQuery(document).on('click', '.dt-description', function () {
                let description_obj = $(this);
                let button_text = $(this).find('.dt-text').text();
                $(this).find('.dt-text').html('<div class="spinner-border text-primary spinner-border-sm my-auto" role="status"><span class="visually-hidden">Loading...</span></div>');
                if ($(this).closest('tr').next('tr').hasClass('description')) {
                    $(this).closest('tr').next('.description').remove();
                    $(description_obj).find('.dt-text').html(button_text);

                }
                else {
                    if (settings.description === true) {
                        $.ajax({
                            url: (settings.description_url == false) ? settings.url + "?op=description" : settings.description_url,
                            dataType: 'json',
                            method: settings.method,
                            success: function (data) {
                                $(description_obj).closest('tr').after(data);
                                setTimeout(() => {
                                    $(description_obj).find('.dt-text').html(button_text);
                                }, 500);
                                if (settings.dt_inside === true) {
                                    $(description_obj).closest('tr').next('.description').find(settings.dt_inside_el).inside_dt({
                                        url: settings.dt_inside_url,
                                        columns: settings.inside_columns,
                                        parent_id: $(description_obj).data('id'),
                                    });
                                }

                            }
                        });
                    }
                }
                if ($(this).find('.feather').hasClass('feather-plus')) {
                    $(this).find('.feather').replaceWith(feather.icons['minus'].toSvg());
                } else {
                    $(this).find('.feather').replaceWith(feather.icons['plus'].toSvg());
                }

            })
            return datatable;
        }
    }
    // Responsive Table
    // --------------------------------------------------------------------
    // datatable inside
    $.fn.inside_dt = function (options) {
        var settings = $.extend({
            filter: false,
            url: '',
            columns: [],
            paginate: false,
            order_col: 0,
            order_dir: 'desc',
            form_el: '.filter-form',
            button_filter: '.btn-filter',
            button_reset: '.btn-reset',
            description_url: false,
            parent_id: ''
        }, options);
        // console.log(settings);
        // var table_inside = this.DataTable({
        //     "processing": true,
        //     serverSide: true,
        //     columns: settings.columns,
        //     ajax: {
        //         url: settings.url,
        //     }
        // });
        var table_inside = this.z_datatable({
            columns: settings.columns,
            url: settings.url + "?parent_id=" + settings.parent_id,
            order_col: settings.order_col,
            order_dir: settings.order_dir,
            form_el: settings.form_el,
            dt_inside: false,
            description: false,
        })
        //********************************************************* */
        // $.ajax({
        //     url: settings.url,
        //     data: settings.columns,
        //     dataType: 'JSON',
        //     success: function (data) {
        //         console.log(data);
        //     }
        // });
        // return table_inside;
    }


    // Filter form control to default size for all tables
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm').removeClass('form-control-sm');
    //ending datatable

    // ******************************************************************************************************
    // form submit functins
    // ******************************************************************************************************
    // function notify($status, $meessage, $title) {
    //     var isRtl = $('html').attr('data-textdirection') === 'rtl';
    //     toastr[$status]($meessage, $title, {
    //         closeButton: true,
    //         tapToDismiss: false,
    //         progressBar: true,
    //         hideDuration: 3000,
    //         rtl: isRtl
    //     });
    // }
    // // form submit by ajax
    // $.fn.form_submit = function (options) {
    //     var settings = $.extend({
    //         file: false,
    //         datatable: false,
    //         url: false,
    //         method: 'POST',
    //         form_id: 'form',
    //         title: 'Notification'
    //     }, options)
    //     let request_url = '';
    //     let button_text = 'Submit';
    //     let $this = this;
    //     this.on('click', function () {
    //         button_text = $(this).text();
    //         let loader_id = $(this).data('loader');
    //         let loader = $("#" + loader_id).data('loader');
    //         $(this).html(loader);
    //         $("#" + settings.form_id).submit();
    //     })
    //     $(document).on('submit', "#" + settings.form_id, function (e) {
    //         let request_url = '';
    //         if (settings.url == false) {
    //             request_url = $(this).attr('action');
    //         } else {
    //             request_url = settings.url;
    //         }
    //         let form_data = new FormData(this);
    //         e.preventDefault();
    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });

    //         $.ajax({
    //             url: request_url,
    //             method: settings.method,
    //             processData: false,
    //             contentType: false,
    //             dataType: 'json',
    //             data: form_data,
    //             success: function (data) {
    //                 if (data.status) {
    //                     notify('success', data.message, settings.title);
    //                     $("#" + settings.form_id).trigger('reset');
    //                     // check has any datatable
    //                     if (settings.datatable != false) {
    //                         settings.datatable.draw();
    //                     }
    //                 }
    //                 else {
    //                     notify('error', data.message, settings.title);
    //                 }
    //                 $("#" + settings.form_id).z_validation({
    //                     errors: data.errors,
    //                 });
    //                 $this.html(button_text);
    //             }
    //         })
    //     });
    // }

    // // validation 
    // $.fn.z_validation = function (options) {
    //     var settings = $.extend({
    //         errors: [],
    //     }, options);
    //     let $this = this;
    //     // all input validation
    //     let error_element = '<span class="error">This field is required.</span>';
    //     this.find('input').each(function (index, obj) {
    //         let field_name = $(obj).attr('name');
    //         // check has input errors
    //         if (settings.errors.hasOwnProperty(field_name)) {
    //             $("input[name='" + field_name + "']").addClass('is-invalid');
    //             $("input[name='" + field_name + "']").next('.error').remove();
    //             $("input[name='" + field_name + "']").after('<span class="error invalid-feedback">' + settings.errors[field_name][0] + '</span>');
    //         } else {
    //             $("input[name='" + field_name + "']").removeClass('is-invalid');
    //             $("input[name='" + field_name + "']").closest('.input-wrapper').find('.error').remove();
    //         }
    //     })
    // }
})(jQuery);


