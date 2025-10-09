function ShowErrorInSelect2(fromID) {
    // for custom select
    var customInput = $(fromID).find('.al-fixed-input-error');
    for (let i = 0; i < customInput.length; i++) {
        if ($(customInput[i]).find('.has-error').length != 0) {
            $(customInput[i]).css({
                'margin-bottom': '55px'
            });
        } else {
            $(customInput[i]).css({
                'margin-bottom': '0px'
            });
        }
    }
    // for select2 plugins
    var allSelet2 = $(fromID).find('.select2.form-select').closest('.position-relative');
    for (let index = 0; index < allSelet2.length; index++) {
        hasError = $(allSelet2[index]).find('.has-error');
        if (hasError.length != 0) {
            $(allSelet2[index]).addClass('al-fixed-input-error-select2');
        }
        else {
            $(allSelet2[index]).removeClass('al-fixed-input-error-select2');
        }
    }
}

function fm_validation(form_id, errors = null, dropzone = false) {
    if (errors != null) {
        var all_inputs = [];
        $("#" + form_id).find('input[name]').each(function () {
            all_inputs.push($(this).attr('name'));
        });
        $("#" + form_id).find('select[name]').each(function () {
            all_inputs.push($(this).attr('name'));
        });

        $.each(errors, function (key, value) {
            // console.log(key + ": " + value);
            // console.log(key);
            if (jQuery.inArray(key, all_inputs) >= 0) {
                if ($("#" + form_id).find("input[name=" + key).next('.has-error').length || $("#" + form_id).find("select[name=" + key).next('.has-error').length) {
                    $("#" + form_id).find("input[name=" + key).next('.has-error').text(value);
                    $("#" + form_id).find("select[name=" + key).next('.has-error').text(value);
                }
                else {
                    $("#" + form_id).find("input[name=" + key).after('<span class="text-danger has-error">' + value + '<span/>');
                    $("#" + form_id).find("select[name=" + key).after('<span class="text-danger has-error">' + value + '<span/>');
                }
            }
        });
        $("#" + form_id).find('input[name]').each(function () {
            if (!errors.hasOwnProperty($(this).attr('name'))) {
                $($("#" + form_id).find('input[name=' + $(this).attr('name') + ']')).next('.has-error').remove();
                // $($("#" + form_id).find('select[name=' + $(this).attr('name') + ']')).next('.has-error').remove();
            }
        });
        $("#" + form_id).find('select[name]').each(function () {
            if (!errors.hasOwnProperty($(this).attr('name'))) {
                // $($("#" + form_id).find('input[name=' + $(this).attr('name') + ']')).next('.has-error').remove();
                $($("#" + form_id).find('select[name=' + $(this).attr('name') + ']')).next('.has-error').remove();
            }
        });
        if (dropzone == true) {
            var drop_zone_input = [];
            $("#" + form_id).find('.dropzone').each(function () {
                let __this = $(this);
                let drop_zone = $(this).data('field');
                drop_zone_input.push($(this).data('field'));
                $.each(errors, function (key, value) {
                    if ("file_" + drop_zone === key) {
                        if ($(__this).next('.has-error').length) {
                            $(__this).next('.has-error').text(value);
                        }
                        else {
                            $(__this).after('<span class="text-danger has-error">' + value + '<span/>');
                        }
                    }
                    if ("file_" + drop_zone !== key) {
                        // $(__this).next('.has-error').remove();
                        // console.log(key);
                    }
                });
                if (!errors.hasOwnProperty("file_" + drop_zone)) {
                    $(__this).next('.has-error').remove();
                }
            });
        }
    } else {
        $("#" + form_id).find('.has-error').remove();
    }

}
Dropzone.autoDiscover = false;
'use strict';
function file_upload(request_url, auto_upload = false, element, form_id = null, button_id = null, notify_title = null, multiple = false, datatable = null, reload = false) {
    $(document).ready(() => {
        const dropzones = []
        $(element).each(function (i, el) {
            const name = 'file_' + $(el).data('field')
            var myDropzone = new Dropzone(el, {
                url: window.location.pathname,
                autoProcessQueue: auto_upload,
                uploadMultiple: false,
                parallelUploads: 100,
                maxFiles: 100,
                maxFilesize: 1,
                method: 'post',
                paramName: name,
                acceptedFiles: 'image/*,application/pdf',
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                },
                addRemoveLinks: true,
            })
            dropzones.push(myDropzone)
        })
        document.querySelector(button_id).addEventListener("click", function (e) {
            // Make sure that the form isn't actually being sent.
            let loader = $(this).data('loading');
            let btn_text = $(this).text();
            let $this = $(this);
            $(this).html(loader);
            $(this).prop('disabled', true);
            e.preventDefault();
            e.stopImmediatePropagation();
            let form = new FormData($("#" + form_id)[0])
            dropzones.forEach(dropzone => {
                let { paramName } = dropzone.options
                dropzone.files.forEach((file, i) => {

                    form.append(paramName, file)
                })
                dropzone.processQueue();
            })
            $.ajax({
                method: 'POST',
                url: request_url,
                data: form,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == true) {
                        dropzones.forEach(dropzone => {
                            dropzone.removeAllFiles(true);
                        })
                        notify('success', response.message, notify_title);
                        if (multiple == true) {
                            $("#last-amount").text(response.last_transaction.amount);
                            $("#last-currency").text(response.last_transaction.currency);
                            $("#last-local-currency").text(response.last_transaction.local_currency);
                            if (response.last_transaction.currency == "") {
                                $(".currency-field").addClass('d-none');
                            } else {
                                $(".currency-field").removeClass('d-none');
                            }
                            $("#last-txn-id").text(response.last_transaction.transaction_type);
                            let status = '';
                            if (response.last_transaction.approved_status === 'A') {
                                status = 'Approved';
                            } else if (response.last_transaction.approved_status === 'P') {
                                status = 'Pending';
                            } else {
                                status = 'Decline';
                            }
                            $("#last-status").find('.badge').removeClass('badge-success, badge-warning').addClass('badge-dark').text(status);
                            $("#btn-js-next").trigger("click");
                        }
                        setTimeout(() => {
                            $($this).prop('disabled', false);
                        }, 2000);
                        // hide modal if form is inside a modal
                        $($this).closest('.modal').modal('hide');
                        // draw datatable if have
                        if (datatable != null) {
                            console.log('datatatable');
                            $(datatable).DataTable().draw();
                        }
                        if (form_id != null) {
                            $("#"+form_id).trigger('reset');
                        }
                        $("select").val('').trigger('change')

                        if (reload == true) {
                            setTimeout(function() {
                                location.reload(); 
                            }, 1000);
                        }
                    }
                    if (response.status == false) {
                        notify('error', response.message, notify_title);
                        setTimeout(() => {
                            $($this).prop('disabled', false);
                        }, 2000);
                    }
                    $($this).html(btn_text);
                    $($this).text(btn_text);
                    fm_validation(form_id, response.errors, true);
                    ShowErrorInSelect2("#" + form_id);
                }
            });
        });
    })

}