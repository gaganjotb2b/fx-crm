@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Bank Setting')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<style>
    .error-msg {
        color: red;
    }

    @media screen and (max-width: 767px) {
        .description-section {
            display: none;
        }
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')

<?php

use App\Models\AdminBank;

$id = '';
$bankCountries = array();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $bankAccountInfo = AdminBank::where('id', '=', $id)->first();
}

?>
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__('page.bank-account')}} {{__('page.settings')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('page.settings')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('page.bank-account')}} {{__('page.settings')}}</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Role cards -->
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 description-section">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{__('page.note')}}:{{__('ib-management.please read carefully')}} </h3>
                            <p class="mb-2">
                                {{__('group-setting.Important notes please read')}}
                            </p>
                            <p class="mb-2">{{__('page.tab_1')}}.</p>
                            <p class="mb-2">{{__('page.tab_2')}}.</p>
                            <p class="mb-2">{{__('page.tab_3')}}.</p>
                            <p class="mb-2">{{__('page.tab_4')}}.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8">
                    <section class="page-blockui">
                        <div class="card p-0">
                            <div class="card-header">
                                <h3>{{__('page.bank-account')}} {{__('page.Setup')}}</h3>
                                <div class="w-40 float-end">
                                    <button type="button" class="btn btn-icon btn-primary tab-add-remove" data-action="remove">
                                        <i data-feather='minus'></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-primary" disabled>
                                        Add/Remove Tab
                                    </button>
                                    <button type="button" class="btn btn-icon btn-primary tab-add-remove" data-action="add">
                                        <i data-feather='plus'></i>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <form action="{{route('admin.add-bank-account-setup')}}" method="post" id="bank-account-setup-form" class="form-block">
                                    @csrf
                                    <input class="valNotReset" name="process" value="add_bank_account" type="hidden" />
                                    <input id="idField" name="id" value="<?= isset($bankAccountInfo->id) ? $bankAccountInfo->id : 0; ?>" type="hidden" />

                                    <div class="mb-1 row">
                                        <label for="full-name" class="col-sm-3 col-form-label">{{__('page.Tab')}} {{__('page.Selection')}}<span class="text-danger">&#9734;</span></label>
                                        <div class="col-sm-9 has-error">
                                            <select id="tab_selection" name="tab_selection" class="form-control mb-md filter-error select2">
                                                <option value="">Select A Tab</option>
                                                @foreach($admin_bank as $row)
                                                <option value="{{$row->tab_selection}}">Tab {{$row->tab_selection}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="tab_name" class="col-sm-3 col-form-label">{{__('page.Tab')}} {{__('page.name')}}</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" value="" id="tab_name" name="tab_name" placeholder="Tab Name" style="margin-top: 10px" />
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="country" class="col-sm-3 col-form-label">{{__('page.bank-country')}} </label>
                                        <div class="col-sm-9">
                                            <select id="bank_country" name="bank_country" class="form-control mb-md filter-error select2">
                                                <option value="">Select bank country</option>
                                                <?php
                                                $countries = App\Models\Country::all();
                                                foreach ($countries as $key => $country) {
                                                    echo '<option  value="' . $country->name . '">' . $country->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="bank_name" class="col-sm-3 col-form-label">{{__('page.bank-name')}} </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="" placeholder="Bank Name" />
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="account_name" class="col-sm-3 col-form-label">{{__('page.account-name')}} <span class="text-danger">&#9734;</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="account_name" name="account_name" value="" placeholder="Account Name" />
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="account_num" class="col-sm-3 col-form-label">{{__('page.account-number')}} <span class="text-danger">&#9734;</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="account_number" name="account_number" value="" placeholder="Account Number" />
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="swift_code" class="col-sm-3 col-form-label " id="swift-code-label">Swift {{__('page.code')}} </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="swift_code" name="code" value="" placeholder="Swift Code" />
                                        </div>
                                    </div>

                                    <div class="mb-1 row d-none">
                                        <label for="ifsc_code" class="col-sm-3 col-form-label">IFSC {{__('page.code')}} </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" value="" placeholder="IFSC Code" />
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="routing_number" class="col-sm-3 col-form-label">{{__('page.Routing_Number')}}</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="routing" name="routing" value="" placeholder="Routing Number" />
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="bank_address" class="col-sm-3 col-form-label">{{__('page.bank-address')}} </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="bank_address" name="bank_address" value="" placeholder="Bank Address" />
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="minimum_deposit" class="col-sm-3 col-form-label">{{__('ad-reports.min')}} {{__('page.deposit')}} </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="minimum_deposit" name="minimum_deposit" value="" placeholder="Minimum Deposit" />
                                        </div>
                                    </div>
                                    <!-- multi currency -->
                                    <div class="mb-1 row">
                                        <label for="currency" class="col-sm-3 col-form-label">Local Currency</label>
                                        <div class="col-sm-9 has-error">
                                            <select id="currency" name="currency" class="form-control mb-md filter-error select2">
                                                <option value="">Select a currency</option>
                                                <?php
                                                $currency_setups = App\Models\CurrencySetup::all();
                                                foreach ($currency_setups as $currency_setup) {
                                                    echo '<option  value="' . $currency_setup->id . '">' . $currency_setup->currency . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="note" class="col-sm-3 col-form-label">{{__('page.note')}}</label>
                                        <div class="col-sm-9">
                                            <textarea type="text" class="form-control" id="note" name="note" placeholder="Note"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <div class="col-sm-6"></div>
                                        <div class="col-sm-6">
                                            <button type="button" style="float: right;" class="btn btn-primary"  id="submitBtn_request" onclick="_run(this)" data-form="bank-account-setup-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createBankCallBack" data-btnid="submitBtn_request">{{__('ad-reports.add')}} {{__('page.bank-account')}} </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!--/ Role cards -->
        </div>
    </div>
</div>
<!-- END: Content-->



@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')

@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
@stop
<!-- END: page vendor js -->

<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/extensions/ext-component-blockui.js')}}"></script>
<script src="{{asset('admin-assets/src/js/core/confirm-alert.js') }}"></script>
<script>
    function createBankCallBack(data) {
        if (data.success == true) {
            notify('success', data.message, "Success");
            $('#bank-account-setup-form')[0].reset();
            $('#bank_country option[value=""]').prop("selected", true).trigger("change");
            $('#currency option[value=""]').prop("selected", true).trigger("change");
        } else {
            notify('error', data.message, "Error");
            $.validator("bank-account-setup-form", data.errors);
        }
    }

    // #tab_selection selection On Change
    $("#tab_selection").change(function() {

        var tabValue = $("#tab_selection").val();
        // Reset Script
        $('#bank-account-setup-form input, #bank_country').val('');
        $('.valNotReset').val('add_bank_account');
        $('.valNotReset2').val('Reset');
        // $('#submitBtn').text('Add Bank Account');

        if (tabValue != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/settings/add-bank-account-setup',
                type: 'POST',
                dataType: 'json',
                data: {
                    process: 'tab_selection',
                    tab_value: tabValue
                },
                success: function(data) {
                    if (data.success == false) {
                        $('#bank-account-setup-form input, #bank_country').val('');
                        $('.valNotReset').val('add_bank_account');
                        $('.valNotReset2').val('Reset');

                        // $('#submitBtn').text('Add Bank Account');
                        notify('error', data.message, "Error");
                    } else {
                        // Data is exist. Insert in data
                        notify('success', data.message, "Success");
                        // $('#submitBtn').text('Update Bank Account Info');

                        // Import Data Into Form Field
                        $('#idField').val(data.id);
                        $('#tab_name').val(data.tab_name);
                        $('#bank_name').val(data.bank_name);
                        $('#account_name').val(data.account_name);
                        $('#account_number').val(data.account_number);
                        $('#swift_code').val(data.swift_code);
                        $('#ifsc_code').val(data.ifsc_code);
                        $('#routing').val(data.routing);
                        $('#bank_country option[value="' + data.bank_country + '"]').prop("selected", true).trigger("change");
                        $('#bank_address').val(data.bank_address);
                        if (data.currency_id) {
                            $('#currency option[value="' + data.currency_id + '"]').prop("selected", true).trigger("change");
                        } else {
                            $('#currency option[value=""]').prop("selected", true).trigger("change");
                        }

                        $('#minimum_deposit').val(data.minimum_deposit);
                        $('#note').val(data.note);

                    }

                },
                error: function(e) {
                    // console.log(e);
                }
            });
        }

    });
    // bank validation
    // on change country and get ifsc if the country is india
    bank_swift($("#bank_country").val(), 'add');
    bank_swift($("#modal-country").text(), 'edit');
    $(document).on("change", "#bank_country", function() {
        var country = $(this).val();
        bank_swift(country, 'add');
    });
    $(document).on("change", "#modal-country", function() {
        var country = $(this).text();
        bank_swift(country, 'edit');
    });

    function bank_swift(country, op) {

        var country = country;
        // console.log(country);
        $.ajaxSetup({
            async: false
        });
        var has_iban = (function() {
            var result;
            var $count = 0;
            var flagsUrl = '{{ URL::asset("json/iban.json") }}';
            $.getJSON(flagsUrl, {}, function(data) {

                $.each(data, function(key, value) {
                    if (country.toLowerCase() === value.country.toLowerCase()) {
                        result = value.country;
                        $count = 1;
                        return false;
                    }

                });

            });
            return $count;
        })();
        if (has_iban == 0) {

            if (country.toLowerCase() === 'india') {
                if (op === 'add') {
                    $("#swift-code-label").text('IFSC Code');
                } else {
                    // console.log('edit');
                    $('#modal-country').closest('form').find(".swift-code-label").text('IFSC Code');
                }


            } else {
                if (op === 'add') {
                    $("#swift-code-label").text('SWIFT Code');
                } else {
                    $("#modal-country").closest('form').find(".swift-code-label").text('SWIFT Code');
                }

            }

        } else {
            if (op === 'add') {
                $("#swift-code-label").text('SWIFT Code');
            } else {
                $('#modal-country').closest('form').find(".swift-code-label").text('SWIFT Code');
            }
        }
    }

    // add or remove bank tab
    $(document).on('click', '.tab-add-remove', function() {
        let action = $(this).data('action');
        var tab_selection = $('#tab_selection').val();
        if (tab_selection == "") {
            tab_selection = "null";
        }
        var message = "";
        var button_color = "";
        if (action == 'add') {
            message = "add";
            button_color = "btn btn-success";
            tab_selection = "null";
        } else {
            message = "remove";
            button_color = "btn btn-danger";
        }
        warning_title = 'Are you sure to ' + message + ' a new bank tab?';
        warning_msg = 'If you want to ' + message + ' bank tab please click OK, otherwise simply click cancel';
        request_for = 'block';

        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,
            showCancelButton: true,
            customClass: {
                confirmButton: button_color,
                cancelButton: 'btn btn-warning'
            },
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/settings/add-or-remove-tab/' + action + '/' + tab_selection,
                    method: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success === true) {
                            notify('success', data.message, 'Admin Bank Tab');
                            location.reload();
                        } else {
                            notify('error', data.message, 'Admin Bank Tab');
                        }
                    }
                })
            }
        });
    });
</script>
@stop
<!-- BEGIN: page JS -->