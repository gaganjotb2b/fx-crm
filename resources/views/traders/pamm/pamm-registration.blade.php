@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Pamm Registration')
@section('page-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!-- font awsome  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/registration.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/color.css') }}" />
<style>
    .error-msg {
        color: red !important;
    }

    .form_wrapper .input_field>span {
        border-right: 0px solid var(--font-color);
    }

    .error-msg {
        width: auto !important;
        padding: 10px 0;
        margin-top: 26px;
        font-size: 14px;
    }

    .custom-btn:hover {
        transform: inherit !important;
    }


    .equity:hover {
        transform: inherit !important;
    }


    .multiselect-dropdown-list-wrapper {
        background: #fff !important;
    }

    .toast-message span {
        color: #28C76F !important;
    }

    .toast-message span {
        color: #fff !important;
    }


</style>
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="main-content mt-5">
    <div class="container main-container">
        <div class="row" style="margin-top: 40px;">
            <div class="col-sm-5">
                <div class="card text-left" style="background: transparent;">
                    <div class="card-body">
                        <h4 class="card-title">Important Note For Social Trade Registration</h4>
                        <hr class="mt-4 mb-4">
                        <p class="card-text ">
                        <ul class="note_ul">
                            <li>
                                Start with just $25
                            </li>
                            <hr>
                            <li>
                                Get up to 50% commission
                            </li>
                            <hr>
                            <li>
                                Trade on ECN or ECN Zero conditions
                            </li>
                            <hr>
                            <li>
                                Show your profile on the Strategy Managers Ranking page
                            </li>
                        </ul>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="form_wrapper">
                    <div class="form_container">
                        <div class="title_container">
                            <h2>Social Trading Registration</h2>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="avatar avatar-xl m-r-xs">
                                    <img src="{{ asset('trader-assets/assets/img/pamm/user_image.png') }}">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="col-md-12">
                                    <span class="widget-connection-request-info-name">{{ucwords(auth()->user()->name)}}</span>
                                </div>
                                <div class="col-md-12">
                                    <span class="widget-connection-request-info-count">{{auth()->user()->email}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin: 20px 0;">
                                <div class="widget-connection-request-actions d-flex">
                                    <button class="btn  flex-grow-1 m-r-xxs balance custom-btn">Balance:
                                        <span id="balance">$0.00</span></button>

                                    <button class="btn btn-info ml-2 flex-grow-1 m-l-xxs equity">Equity:
                                        <span id="equity">$0.00</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div style="margin:0 auto ; width: 100%;">
                                <form id="pamm_form" method="post" action="/user/user-pamm/user-pamm-registration-process">
                                    @csrf
                                    <div class="input_field select_option"><span><i aria-hidden="true" class="fa fa-users"></i></span>
                                        <select class="pl-3 trade_ac" name="trading_account" id="trading_account">
                                            <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select One</option>
                                            @foreach ($trading_account as $account)
                                            <option value="{{ $account->account_number }}">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $account->account_number }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="select_arrow"></div>
                                    </div>
                                    <div class="input_field"> <span><i aria-hidden="true" class="fa fa-user"></i></span>
                                        <input type="text" name="username" id="username" placeholder="Set Username" required />
                                    </div>
                                    <div class="input_field"> <span><i aria-hidden="true" class="fas fa-dollar-sign"></i></span>
                                        <input type="text" name="min_deposit" id="min_deposit" placeholder="Minimum Deposit" required />
                                    </div>
                                    <div class="input_field"> <span><i aria-hidden="true" class="fas fa-money-bill-alt"></i></span>
                                        <input type="text" name="max_deposit" id="mix_deposit" placeholder="Maximum Deposit" required />
                                    </div>
                                    <div class="input_field"> <span><i aria-hidden="true" class="fa fa-external-link-alt"></i></span>
                                        <input type="text" name="share_profit" id="share_profit" placeholder="Profit Share" required />
                                    </div>
                                    <div class="input_field select_option"><span><i aria-hidden="true" class="fa fa-send"></i></span>
                                        <select style="padding-left: 35px !important;" name="profit_share_time" id="profit_share_time">
                                            <!--<option value="daily">Daily Based Profit Share</option>-->
                                            <!--<option value="weekly">Weekly Based Profit Share</option>-->
                                            <option value="biweekly">Bi-Weekly Based Profit Share</option>
                                            <option value="monthly">Monthly Based Profit Share</option>
                                        </select>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn custom-btn col-md-6 border-1" type="button" role="button" id="submitReg" onclick="_run(this)" data-form="pamm_form" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" data-callback="regCallBack" data-btnid="submitReg">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-js')


<script>
    $(document).ready(function() {
        var table = $('#data_table').DataTable({
            select: false,
            "columnDefs": [{
                className: "Name",
                "targets": [0],
                "visible": false,
                "searching": false,
            }]
        }); //End of create main table


        $('#data_table tbody').on('click', 'tr', function() {

            alert(table.row(this).data()[0]);

        });
    });

    //callbackfunction for pamm registration
    function regCallBack(data) {
        $('#submitReg').prop('disabled', false);
        if (data.success) {
            notify('success', data.message, "Success");
            $("#pamm_form")[0].reset();
        } else {
            console.log(data.message);
            notify('error', data.message, "Error");
            $.validator("pamm_form", data.errors);

        }
    }

    // balance and equity script
    $(document).on('change', '.trade_ac', function() {
        var master_login = $(this).val();
        if (master_login != "") {
            $("#balance").html('Loading....');
            $("#equity").html('Loading....');

            $.ajax({
                url: '/user/trading-account-balance-equity',
                type: 'GET',
                dataType: 'json',
                data: {
                    login: master_login
                },
                success: function(data) {
                    if (data.success) {
                        $("#balance").html(data.balance);
                        $("#equity").html(data.equity);

                        $("#balance").html((!data.balance != 'faild') ? data.balance : 'Not Found');
                        $("#equity").html((!data.equity != 'faild') ? data.equity : 'Not Found');
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
    });
</script>
@stop