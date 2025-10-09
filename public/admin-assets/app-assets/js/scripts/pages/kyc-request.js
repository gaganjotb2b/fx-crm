
(function (window, document, $) {
    $(document).ready(function(){
        $("#kycUserVerify").on("change",function(e){
            var user_id = $(this).attr("data-target");
            var status = $(this).prop("checked");
            var data = {
                "userid": user_id,
                "status": status,
            };

            $.ajax({
                url: '/admin/kyc-management/kyc-status',
                method: 'POST',
                data,
                dataType: 'json',
                success: function (res) {
                    console.log(res);
                     if (res.success === true) {
                        notify('success', res.messages, 'KYC Status Verified');
                     } else {
                        notify('error', res.messages, 'KYC Status Unverified');
                     }
                }
            });
        })
    });
    $(document).on('click', ".kyc-modal", function () {
        $("#userDescriptionModel").modal("show");
        var id = $(this).data('id');
        var table_id = $(this).data('table_id');
        $('#front_part').attr("src","");
        $('#front_part_pdf').attr("src","");
        $('#backpart_part_pdf').attr("src","");
        $('#backpart_part').attr("src","");
        $(".loader").toggleClass("d-none d-block")
        $('.loaderParent').css('min-height','50vh');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/kyc-management/kyc-request-description/' + id,
            method: 'GET',
            dataType: 'json',

            success: function (data) {
                // console.log(data);

                if (data.group_name == 'id proof') {
                    $(".loader").toggleClass("d-none d-block")

                    $('#profile-tab-fill').show();
                    //pdf
                    if (data.front_part_file_type == 'pdf') {
                        $('#front_part_pdf').attr("src", data.front_part).show();
                        $('#front_part').hide();
                        $('.loaderParent').css('min-height','0vh');
                    } else {
                        $('#front_part').attr("src", data.front_part).show();
                        $('#front_part_pdf').hide();
                        $('.loaderParent').css('min-height','0vh');
                    }
                    if (data.back_part_file_type == 'pdf') {
                        $(".loader").toggleClass("d-none d-block")
                        $('#backpart_part_pdf').attr("src", data.back_part).show();
                        $('#backpart_part').hide();
                        $('.loaderParent').css('min-height','0vh');
                    } else {
                        $('#backpart_part').attr("src", data.back_part).show();
                        $('#backpart_part_pdf').hide();
                        $('.loaderParent').css('min-height','0vh');
                    }

                    // $('#front_part').attr("src", data.front_part);
                    // $('#backpart_part').attr("src", data.back_part);
                    $('#pricingModalTitleId').css('display','block');
                    $('#pricingModalTitleAdd').css('display','none');

                    $('#id_proof').css('display','block');
                    $('#add_proof').css('display','none').empty();
                } else if (data.group_name === 'address proof') {
                    $('#add_proof').css('display','block');
                    $('#id_proof').css('display','none').empty();
                    $('#profile-tab-fill').hide();
                    //pdf
                   if (data.front_part_file_type == 'pdf') {
                        $('#front_part_pdf').attr("src", data.front_part).show();
                        $('#front_part').hide();
                    } else {
                        $('#front_part').attr("src", data.front_part).show();
                        $('#front_part_pdf').hide();
                    }
                    // $('#front_part').attr("src", data.front_part);
                    $('#pricingModalTitleAdd').css('display','block');
                    $('#pricingModalTitleId').css('display','none');
                }

                //show value in input text
                $('#group_name').val(data.group_name);
                $("#update_name").val(data.name);
                $('#update_phone').val(data.phone);
                $('#update_issue_date').val(data.issue_date);
                $('#update_expire_date').val(data.exp_date);
                $('#update_sex').val(data.gender);
                 $('#update_document_type option').each(function(){
                  var text = $(this).text();
                  if(text == data.document_name)
                  {
                    $(this).prop("selected",true);
                  }
                  else{
                      $(this).removeAttr("selected");
                  }
                });
                $('#update_document_type').select2();
                $('#update_idNumber').val(data.id_number);
                $('#update_state').val(data.state);
                $('#update_address').val(data.address);
                $('#update_city').val(data.city);
                $('#update_zip_code').val(data.zip_code);
                $('#update_date_birth').val(data.dob);

                $('#user-status').html(data.status);
                $('#user_name').text(data.name);
                $('#user-email').text(data.email);
                $('#user-phone').text(data.phone);
                $('#user-city').text(data.city);
                $('#user-state').text(data.state);
                $('#user-address').text(data.address);
                $('#user-zip-code').text(data.zip_code);
                $('#user-issue_date').text(data.issue_date);
                $('#user-exp_date').text(data.exp_date);
                $('#user-doc_type').text(data.document_name);
                $('#user-idNumber').text(data.id_number);
                $('#user-country').text(data.country);
                $('#user-dob').text(data.dob);
                $('#user-issuer-country').text(data.country);
                $('#sex').text(data.gender);

                if (data.document_name === "adhar card") {
                    $('#update_issue_date').addClass('d-none');
                    $('#update_expire_date').addClass('d-none');
                    $('.adhar_input_field').addClass('d-none').css('padding-bottom', 0);
                    $('.modal-issue-date').addClass('d-none');
                    $('.modal-expire-date').addClass('d-none');

                } else {
                    $('#update_issue_date').removeClass('d-none');
                    $('#update_expire_date').removeClass('d-none');
                    $('.adhar_input_field').removeClass('d-none');
                    $('.modal-issue-date').removeClass('d-none');
                    $('.modal-expire-date').removeClass('d-none');
                }

                //Address Proof
                $('#update_nameAdd').val(data.name);
                $('#update_phoneAdd').val(data.phone);
                $('#update_issue_dateAdd').val(data.issue_date);
                $('#update_expire_dateAdd').val(data.exp_date);
                $('#update_sexAdd').val(data.gender);
                $('#update_document_typeAdd option').each(function(){
                  var text = $(this).text();
                  if(text == data.document_name)
                  {
                        $(this).prop("selected",true);
                  }
                  else{
                      $(this).removeAttr("selected");
                  }
                });
                $('#update_document_typeAdd').select2();
                $('#update_idNumberAdd').val(data.id_number);
                $('#update_stateAdd').val(data.state);
                $('#update_addressAdd').val(data.address);
                $('#update_cityAdd').val(data.city);
                $('#update_zip_codeAdd').val(data.zip_code);
                $('#update_date_birthAdd').val(data.dob);

                var hidden = data.user_kyc_sts;
                if (hidden != 0) {
                    document.getElementById('approve_button').style.visibility = 'hidden';
                    document.getElementById('edit_button').style.visibility = 'hidden';


                }
                else {
                    document.getElementById('approve_button').style.visibility = 'visible';
                    document.getElementById('edit_button').style.visibility = 'visible';

                }
                if (data.kyc_status == '1') {
                    document.getElementById('kycUserVerify').setAttribute('checked', 'checked');
                } else {
                    document.getElementById('kycUserVerify').removeAttribute('checked');
                }
            }

        });
    });
})(window, document, jQuery);
// -----------------------------
(function (window, document, $) {

    $(document).on('click', ".kyc-modal", function () {
        $("#userDescriptionModel").modal("show");

        var id = $(this).data('id');
        var table_id = $(this).data('table_id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/kyc-management/kyc-description-update',
            method: 'POST',
            dataType: 'json',
            success: function (data) {


                if (data.document_name == "adhar card") {
                    $('.modal-issue-date').addClass('d-none');
                    $('.modal-expire-date').addClass('d-none');
                } else {
                    $('.modal-issue-date').removeClass('d-none');
                    $('.modal-expire-date').removeClass('d-none');
                }
            }
        });
    });
})(window, document, jQuery);
/*<!---------------Approve Data request operation------------------!>*/
function kycApproveRequest() {
    var id = $('#approve_id').val();
    var table_id = $('#table_id').val();
    let warning_title = "";
    let warning_msg = "";
    let request_for;

    warning_title = 'Are you sure? to Approve this user!';
    warning_msg = 'If you want to Approve this User please click OK, otherwise simply click cancel';
    request_for = 'block';

    Swal.fire({
        icon: 'warning',
        title: warning_title,
        html: warning_msg,

        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-warning',
            cancelButton: 'btn btn-danger'
        },
    }).then((willDelete) => {
        if (willDelete.isConfirmed) {
            $('#send-mail-pass').modal('toggle');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/kyc-management/kyc-approve-request/' + id + '/' + table_id,
                method: 'POST',
                dataType: 'json',
                data: { id: id, request_for: request_for },
                success: function (data) {
                    if (data.success === true) {
                        toastr['success'](data.message, 'Mail send', {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,

                        });
                        $('#send-mail-pass').modal('toggle');
                        $('#userDescriptionModel').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: data.success_title,
                            html: data.message,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }

                        }).then((willDelete) => {
                            const table = $("#kyc_report_tbl").DataTable();
                            table.draw();
                        });
                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Mail sending failed!',
                            html: data.message,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })
        }
    });
}

// User Update Profile
function update_profile(e) {
    let obj = $(e);
    var id = obj.data('id');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/admin/kyc-management/kyc-request-profile-view/' + id,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#country').html(data.country_option);
            $('#name').val(data.user_info.name);
            $('#state').val(data.user_info.state);
            $('#zip').val(data.user_info.zip_code);
            $('#city').val(data.user_info.city);
            $('#issue_date').val(data.issue_date);
            $('#expire_date').val(data.exp_date);
            $('#dob').val(data.dob);
            $('#address').val(data.user_info.address);
        }
    });
}




