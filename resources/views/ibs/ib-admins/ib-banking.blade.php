<?php

use Illuminate\Support\Facades\Auth;
?>
@extends(App\Services\systems\VersionControllService::get_layout('ib'))
@section('title', 'IB Banking')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<style>
    .dataTables_length .form-select {
        background-position: right 3px center;
        background-size: 12px 12px;
        padding-right: 1.25rem;
        margin-top: 3px;
    }

    #bank-account tr,
    #bank-account td:first-child {
        border-left: 3px solid var(--custom-primary);
    }

    #bank-account tr,
    #bank-account th:first-child {
        border-left: 3px solid;
    }

    #bank-account tr,
    #bank-account td {
        background-color: #f7fafc;
        vertical-align: middle;
    }

    #bank-account {
        border-collapse: separate !important;
        border-spacing: 2px 8px;
    }


    td.details-control {
        background-image: url("{{ asset('datatable-icon/plus.png') }}");
        cursor: pointer;
        background-repeat: no-repeat;
        background-position: center;
    }

    tr.details td.details-control {

        background-image: url("{{ asset('datatable-icon/minus.png') }}");
        cursor: pointer;
        background-repeat: no-repeat;
        background-position: center;

    }

    button#bank-account-list-delete-button {
        margin-right: .5rem;
    }

    /* .select2-container--classic .select2-selection--single,
    .select2-container--default .select2-selection--single {
        min-height: 2.714rem;
        padding: 5px;
        border: 1px solid #d8d6de;
        width: 6rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        position: absolute;
        top: 1px;
        width: 0px !important;
    } */
</style>
@endsection
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <h5 class="mb-0" style="float:left">{{ __('page.my-bank-accounts') }}</h5>
                        <button type="button" class="btn bg-gradient-primary btn-block mb-3 pl-5" data-bs-toggle="modal" data-bs-target="#exampleModalMessage" style="float: right;">+
                            {{ __('page.add-new') }}</button>
                        <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            {{ __('page.add-new-bank-account') }}
                                        </h5>
                                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('ib.ib-admin.ib-banking-add') }}" method="post" enctype="multipart/form-data" id="add-bank-account-form">
                                            @csrf
                                            <div class="form-group">
                                                <label for="country" class="col-form-label">{{ __('page.bank-country') }}:</label>
                                                <select class="form-select choice-material" name="country" id="country">
                                                    <option value="">Select a country</option>
                                                    @foreach ($countries as $country)
                                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="bank-name" class="col-form-label">{{ __('page.bank-name') }}:</label>
                                                <input type="text" class="form-control" value="" name="bank_name" id="bank-name">
                                            </div>
                                            <div class="form-group">
                                                <label for="account-name" class="col-form-label"><span class="text-danger">*</span>{{ __('page.account-name') }}:</label>
                                                <input type="text" class="form-control" value="" name="account_name" id="account-name">
                                            </div>
                                            <div class="form-group">
                                                <label for="account-number" class="col-form-label"><span class="text-danger">*</span>{{ __('page.account-number') }}:</label>
                                                <input type="text" class="form-control" value="" name="account_number" id="account-number">
                                            </div>
                                            <div class="form-group">
                                                <label for="swift-code" class="col-form-label" id="swift-code-label">{{ __('page.swift-code') }}:</label>
                                                <input type="text" class="form-control" value="" name="code" id="swift-code">
                                            </div>
                                            <!-- <div class="form-group d-none">
                                                <label for="bank_iban" class="col-form-label">{{ __('page.ifsc-code') }}:</label>
                                                <input type="text" class="form-control" value="" name="bank_iban" id="bank-iban">
                                            </div> -->
                                            <div class="form-group d-none">
                                                <label for="bank_iban" class="col-form-label">{{ __('page.ifsc-code') }}:</label>
                                                <input type="text" class="form-control" value="" name="bank_iban" id="bank-iban">
                                            </div>
                                            <div class="form-group">
                                                <label for="bank-address" class="col-form-label">{{ __('page.bank-address') }}:</label>
                                                <textarea class="form-control" name="bank_address" id="bank-address"></textarea>
                                            </div>
                                            <!-- multi currency -->
                                            @if(\App\Services\BankService::is_multicurrency('all'))
                                            <div class="form-group">
                                                <label for="currency" class="col-form-label fg">Currency:</label>
                                                <select class="form-select choice-colors" name="currency" id="currency">
                                                    <option value="">Select a currency</option>
                                                    <?php
                                                    $currency_setups = App\Models\CurrencySetup::all();
                                                    foreach ($currency_setups as $currency_setups) {
                                                        echo '<option  value="' . $currency_setups->id . '">' . $currency_setups->currency . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            @endif
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-gradient-secondary close-modal" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                                        <button type="button" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" class="btn bg-gradient-primary" id="addBankBtn" onclick="_run(this)" data-el="fg" data-form="add-bank-account-form" data-callback="addBankAccountCallBack" data-btnid="addBankBtn">{{ __('page.submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="bank-account table table-flush datatables-ajax w-100" id="bank-account">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>{{ __('page.bank-name') }}</th>
                                    <th>{{ __('page.account-number') }}</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th>{{ __('page.date') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Delete Modal -->
        <div class="modal fade" id="bank-account-list-delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('page.user-bank-account') }}</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" name="id" id="bank-account-list-delete-id" value="">
                    <div class="modal-body">
                        <h6 class="text-center">
                            {{ __('page.do-you-really-want-to-delete-these-records-this-process-cannot-be-undone') }}
                        </h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                        <button type="submit" class="btn bg-gradient-primary" data-bs-dismiss="modal" id="bank-account-list-delete">{{ __('page.confirm') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Delete Modal -->

        <!-- Start Edit Modal -->
        <div class="modal fade" id="bank-account-list-edit-modal" tabindex="-1" role="dialog" aria-labelledby="bank-account-list-edit-modal-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('page.edit-bank-account') }}</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('ib.ib-admin.ib-banking-edit') }}" method="post" enctype="multipart/form-data" id="edit-bank-account-form">
                            @csrf
                            <div class="form-group">
                                <label for="modal-country" class="col-form-label">{{ __('page.bank-country') }}:</label>
                                <select class="form-select choice-material" name="country" id="modal-country">
                                    <!--<optgroup label="Select Country">-->
                                    <!--<option value="{{ $country->id }}">{{ $country->name }}</option>-->
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                    <!--</optgroup>-->
                                </select>
                            </div>
                            <input type="hidden" id="update_id" name="update_id" value="">
                            <div class="form-group">
                                <label for="modal-bank-name" class="col-form-label"><span class="text-danger">*</span>{{ __('page.bank-name') }}:</label>
                                <input type="text" class="form-control" value="" name="bank_name" id="modal-bank-name">
                            </div>
                            <div class="form-group">
                                <label for="modal-account-name" class="col-form-label">{{ __('page.account-name') }}</label>
                                <input type="text" class="form-control" value="<?= Auth()->user()->name ?>" name="account_name" id="modal-account-name" readonly>
                            </div>
                            <div class="form-group">
                                <label for="modal-account-number" class="col-form-label"><span class="text-danger">*</span>{{ __('page.account-number') }}:</label>
                                <input type="text" class="form-control" value="" name="account_number" id="modal-account-number">
                            </div>
                            <div class="form-group">
                                <label for="modal-swift-code" class="col-form-label swift-code-label">{{ __('page.swift-code') }}:</label>
                                <input type="text" class="form-control" value="" name="code" id="modal-swift-code">
                            </div>
                            <!-- <div class="form-group d-none">
                                <label for="modal-bank-iban" class="col-form-label">{{ __('page.ifsc-code') }}:</label>
                                <input type="text" class="form-control" value="" name="bank_iban" id="modal-bank-iban">
                            </div> -->
                            <div class="form-group">
                                <label for="modal-bank-address" class="col-form-label">{{ __('page.bank-address') }}:</label>
                                <textarea class="form-control" name="bank_address" id="modal-bank-address"></textarea>
                            </div>
                            <!-- multi currency -->
                            <!-- @if(\App\Services\BankService::is_multicurrency('all')) -->
                            <div class="form-group">
                                <label for="modal-currency" class="col-form-label fg">Currency:</label>
                                <!-- <select class="form-select choice-modal" name="modal_currency" id="modal-currency"> -->
                                <select class="form-select" name="modal_currency" id="modal-currency">
                                    <option value="">Select a currency</option>
                                    <?php
                                    $currency_setups = App\Models\CurrencySetup::all();
                                    foreach ($currency_setups as $currency_setups) {
                                        echo '<option value="' . $currency_setups->id . '">' . $currency_setups->currency . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- @endif -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary close-modal" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                        <button type="button" class="btn bg-gradient-primary" id="editBankBtn" onclick="_run(this)" data-el="fg" data-form="edit-bank-account-form" data-callback="editBankAccountCallBack" data-btnid="editBankBtn" data-loading="<i class='fa-spin fas fa-circle-notch'></i>">{{ __('page.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Edit Modal -->
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>

@stop
@section('page-js')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script>
    // disable bank eddit button
    $(document).on('click', '#editBankBtn', function() {
        $(this).prop('disabled', true);
    })
    // datatable start
    function format(d) {

        return d.extra;
    }

    var dt = $('.bank-account').DataTable({
        language: {
            search: "",
            lengthMenu: " _MENU_ ",

            paginate: {
                // remove previous & next text from pagination
                previous: "<",
                next: ">",
            },
        },
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ib/ib-admin/ib-banking/fetch-data",
            "data": function(d) {
                return $.extend({}, d, {});
            }
        },
        "columns": [{
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },

            {
                "data": "bank_name"
            },
            {
                "data": "bank_ac_number"
            },
            {
                "data": "status"
            },
            {
                "data": "created_at"
            },
        ],
        "drawCallback": function(settings) {
            $("#filterBtn").html("FILTER");
        },
        "order": [
            [1, 'desc']
        ]
    });
    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('.bank-account tbody').on('click', 'tr td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = dt.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice(idx, 1);
        } else {
            tr.addClass('details');
            row.child(format(row.data())).show();

            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });

    // On each draw, loop over the `detailRows` array and show any child rows
    dt.on('draw', function() {
        $.each(detailRows, function(i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
    // bank account list fetch data end
    // ------------------------------------------
    // disable button
    $(document).on('click', '#addBankBtn', function() {
        $(this).prop('disabled', true);
    });

    // add new bank account call back
    function addBankAccountCallBack(data) {
        if (data.success) {
            dt.draw();
            notify('success', data.message, 'Bank Account');
            $('.close-modal').trigger('click');
            $('#add-bank-account-form').trigger('reset');
        } else {
            notify('error', "Fill The Required Field", 'Bank Account');
        }
        $("#addBankBtn").prop('disabled', false);
        $.validator("add-bank-account-form", data.errors);
    }

    // bank account delete modal
    $(document).on("click", ".btn-bank-delete", function(event) {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/ib/ib-admin/ib-banking-list-delete',
            data: {
                id: id
            },
            click: false,
            title: 'Delete Bank account',
            message: 'Are you confirm to delete this bank account? If your delete this bank account, you cannot be undo of this process...',
            button_text: 'Delete',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Delete bank account');
            } else {
                notify('error', data.message, 'Delete bank account');
            }
            dt.draw();
        });
    });
    // bank account delete action



    // edit bank account modal fetch data
    $(document).on('click', '#bank-account-list-edit-button', function(event) {
        let id = $(this).data('id');
        $("#id").val(id);
        $.ajax({
            type: "GET",
            url: "/ib/ib-admin/ib-banking/edit/fetch-data/" + id,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.status == false) {
                    Swal.fire({
                        icon: "error",
                        title: "Error found!",
                        html: $errors,
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
                if (data.status == true) {
                    $("#modal-bank-name").val(data.bank_name);
                    $("#modal-account-name").val(data.account_name);
                    $("#modal-account-number").val(data.account_number);
                    $("#modal-swift-code").val(data.swift_code);
                    $("#modal-bank-iban").val(data.bank_iban);
                    $("#modal-bank-address").val(data.bank_address);
                    $('#modal-currency option[value="' + data.currency + '"]').prop("selected", true).trigger("change");
                    $("#modal-country").html(data.country);
                    $("#update_id").val(data.update_id);
                }
            }
        });
    });

    // edit bank account modal form submit
    function editBankAccountCallBack(data) {
        if (data.success) {
            dt.draw();
            notify('success', data.message, 'Bank Account');
            $('.close-modal').trigger('click');
        } else {
            notify('error', "Fill The Required Field", 'Bank Account');
        }
        $('#editBankBtn').prop('disabled', false);
    }

    // bank validation
    // on change country and get ifsc if the country is idia
    bank_swift($("#country").find('option:selected').text(), 'add')
    bank_swift($("#modal-country").find('option:selected').text(), 'edit')
    $(document).on("change", "#country", function() {
        var country = $(this).find('option:selected').text();
        bank_swift(country, 'add');
    });
    $(document).on("change", "#modal-country", function() {
        var country = $(this).find('option:selected').text();
        bank_swift(country, 'edit');
    });

    function bank_swift(country, op) {
        var flagsUrl = '{{ URL::asset("json/iban.json") }}';

        $.getJSON(flagsUrl, {}, function(data) {
            var hasIban = false;

            // Check if the country has an IBAN
            $.each(data, function(key, value) {
                if (country.toLowerCase() === value.country.toLowerCase()) {
                    hasIban = true;
                    return false; // Exit the loop early since we found a match
                }
            });

            // Set the label based on the result and operation
            var labelText = (hasIban && op === 'add') ? 'SWIFT Code' : 'IFSC Code';

            // Update the label based on the country and operation
            if (country.toLowerCase() === 'india') {
                labelText = 'IFSC Code';
            }

            if (op === 'add') {
                console.log(op);
                $("#swift-code-label").text(labelText);
            } else {
                $('#modal-country').closest('form').find(".swift-code-label").text(labelText);
            }
        });
    }
</script>
@endsection