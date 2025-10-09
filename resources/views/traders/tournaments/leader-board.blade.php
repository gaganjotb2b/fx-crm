@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Leader Board')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<style>
    .countdown-box {
        font-size: 1.8rem;
        padding: 1.5rem;
        text-align: center;
    }

    .countdown-time span {
        font-weight: bold;
        display: inline-block;
        min-width: 60px;
    }

    .page-item .page-link,
    .page-item span {
        border-radius: 0 !important;
    }
</style>
@stop
<!-- breadcrumb -->
@section('bread_crumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
            <a class="opacity-3 text-dark" href="javascript:;">
                <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>shop </title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(0.000000, 148.000000)">
                                    <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                                    <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
        </li>
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{__('Tournament')}}</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">LeaderBoard</li>
    </ol>
    <h6 class="font-weight-bolder mb-0">{{__('page.trader-area')}}</h6>
</nav>
@stop
<!-- main content -->
@section('content')
<div class="container-fluid page-profile-overview">
    <div class="page-header min-height-300 border-radius-xl mt-4 bg-gradient-info">
        <!--<div class="w-100">-->
        <!--    <h3 class="text-white h2 text-uppercase text-center w-100 z-index-1">{{$tournament?->tour_name??'---'}}</h3>-->
        <!--    <div class="countdown-box pt-0" id="countdown">-->
        <!--        <p class="text-border text-light">Time remaining to close</p>-->
        <!--        <div data-ending="{{$tournament?->start_date??now()}}" id="countdown-time" class="countdown-time text-center z-index-3 d-flex justify-content-center gap-1">-->
        <!--            <div class="day-container card px-4">-->
        <!--                <span id="tournament-days">0</span>-->
        <!--                <span class="h4">Days</span>-->
        <!--            </div>-->
        <!--            <div class="hour-container card px-4">-->
        <!--                <span id="tournament-hours">0</span>-->
        <!--                <span class="h4">Hrs</span>-->
        <!--            </div>-->
        <!--            <div class="minute-container card px-4">-->
        <!--                <span id="tournament-minutes">0</span>-->
        <!--                <span class="h4">Min</span>-->
        <!--            </div>-->
        <!--            <div class="second-container card px-4">-->
        <!--                <span id="tournament-seconds">0</span>-->
        <!--                <span class="h4">Sec</span>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- <span class="mask bg-gradient-primary opacity-6">

        </span> -->
    </div>
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
            <div class="col-2 border-end border-primary">
                <div class="card bg-transparent shadow-none rounded-0">
                    <div class="card-body text-center">
                        <h4 class="text-bolder">Grand Prize</h4>
                        <h4>Honda Civic 2025</h4>
                    </div>
                </div>
            </div>
            <div class="col-8 border-end border-primary">
                <div class="card bg-transparent shadow-none rounded-0">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center flex-wrap gap-4">
                            <div class="text-center">
                                <h5 class="text-bolder">Prize 1</h5>
                                <span class="badge-dark badge bg-gradient-info mt-2 px-3 py-2">
                                    <span class="h5 text-light">{{ $tournament->prize_1 }}</span>
                                </span>
                            </div>
                            <div class="text-center">
                                <h5 class="text-bolder">Prize 2</h5>
                                <span class="badge-dark badge bg-gradient-info mt-2 px-3 py-2">
                                    <span class="h5 text-light">{{ $tournament->prize_2 }}</span>
                                </span>
                            </div>
                            <div class="text-center">
                                <h5 class="text-bolder">Prize 3</h5>
                                <span class="badge-dark badge bg-gradient-info mt-2 px-3 py-2">
                                    <span class="h5 text-light">{{ $tournament->prize_3 }}</span>
                                </span>
                            </div>
                            <div class="text-center">
                                <h5 class="text-bolder">Prize 4</h5>
                                <span class="badge-dark badge bg-gradient-info mt-2 px-3 py-2">
                                    <span class="h5 text-light"> {{ $tournament->prize_4 }}</span>
                                </span>
                            </div>
                            <!-- Add more prize blocks here if needed -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-2">
                <div class="card bg-transparent shadow-none rounded-0">
                    <div class="card-body text-center">
                        <h4 class="text-bolder">Platform</h4>
                        <h4>MT5</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-4">
    <div class="row mt-3 custom-height-con">
        <div class="col-12 col-md-6 col-xl-4">
            <!-- trading account -->
            <div class="card h-100">
                <div class="card-body">
                    <div class="row  position-relative mb-3" id="trading-ac-data-list">
                        <h4 class="h4" style="font-weight: 700;">Who Wins</h4>
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md px-3">
                                <i class="fas fa-crown text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">The participant with the highest balance at the end of round wins the main prize</p>
                            </div>
                        </div>
                    </div>
                    <div class="row  position-relative mb-3" id="trading-ac-data-list">
                        <h4 class="h4" style="font-weight: 700;">How to join this tournament, follow this bellow instructions</h4>
                        <div class="timeline timeline-one-side">
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="text-success text-gradient">1</i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Open Account</h6>
                                    <p class="text-secondary font-weight-bold text-sm mt-1 mb-0">Open an account if not you not open yet, for specific group.</p>
                                </div>
                            </div>
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="text-danger text-gradient">2</i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Deposit</h6>
                                    <p class="text-secondary font-weight-bold text-sm mt-1 mb-0">Deposit minimum amount to your account, that required.</p>
                                </div>
                            </div>
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="text-info text-gradient">3</i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Terms</h6>
                                    <p class="text-secondary font-weight-bold text-sm mt-1 mb-0">Read terms and condition applied</p>
                                </div>
                            </div>
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="text-warning text-gradient">4</i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Rules</h6>
                                    <p class="text-secondary font-weight-bold text-sm mt-1 mb-0">Follow the applied rules</p>
                                </div>
                            </div>
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="text-primary text-gradient">5</i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Join</h6>
                                    <p class="text-secondary font-weight-bold text-sm mt-1 mb-0">Join Using the specified joining button.</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" id="btn-join-modal-show" class="btn btn-pill btn-outline-info w-lg-35">Join Now</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- profile information -->
        <div class="col-12 col-md-6 col-xl-8 mt-md-0 mt-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="h4" style="font-weight: 700;">Current round leaders</h4>
                    <!--<h6 class="h6" style="font-weight: 700;">-->
                    <!--    Start on: <span class="text-bold">{{ \Carbon\Carbon::parse($tournament?->start_date)->format('d M Y') }}</span>-->
                    <!--</h6>-->
                    <!--<h6 class="h6" style="font-weight: 700;">-->
                    <!--    Ends on: <span class="text-bold">{{ \Carbon\Carbon::parse($tournament?->end_date)->format('d M Y') }}</span>-->
                    <!--</h6>-->

                    <table class="table" id="dt-leaders">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Participant's Name</th>
                                <th>Account Number</th>
                                <th>Profit</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- modal for join tournament -->
    <div class="modal fade" id="modal-join-tournament" tabindex="-1" role="dialog" aria-labelledby="join-tournament" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popup-header">Join Tournament</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.tournament.join') }}" method="post" id="form-join-tournament">
                        @csrf
                        <input type="hidden" id="hidden_input_tournament_id" name="tournament" value="{{$tournament?->id}}">
                        <p>Please choose an account join the tournament, this is account base tournament so for join you need a trading account.</p>
                        <div class="form-group">
                            <label for="recipient-name" id="social_link" class="col-form-label">{{ __('Account') }}:</label>
                            <select name="account" id="tournament-account" class="form select form-control">
                                <option value="">Choose an account</option>
                                @foreach ($accounts as $account)
                                <option value="{{$account->id}}">#{{$account?->account_number}}({{$account?->group_id}})</option>
                                @endforeach
                            </select>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Account Balance: </th>
                                    <td id="join-account-balance">---</td>
                                </tr>
                                <tr>
                                    <th>Account Equity: </th>
                                    <td id="join-account-equity">---</td>
                                </tr>
                                <tr>
                                    <th>Account Type: </th>
                                    <td id="join-account-type">---</td>
                                </tr>
                            </thead>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data-label="Update" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="tournamentJoinCallback" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="form-join-tournament" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary">{{ __('page.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>
@stop
@section('corejs')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
<!-- Start: date picker -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/form-pickers.js') }}"></script>
<!-- End: date picker -->
@stop
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('/common-js/finance.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script>
    $(document).ready(function() {
        // const targetDate = new Date("2025-06-01T00:00:00").getTime();
        const targetDate = new Date($("#countdown-time").data('ending')).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance <= 0) {
                clearInterval(timer);
                $('#countdown').html("Time's up!");
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $('#tournament-days').text(days);
            $('#tournament-hours').text(hours);
            $('#tournament-minutes').text(minutes);
            $('#tournament-seconds').text(seconds);
        }

        const timer = setInterval(updateCountdown, 1000);
        updateCountdown(); // initial call
        // ending the countdown time
        $(document).on('click', "#btn-join-modal-show", function() {
            $("#modal-join-tournament").modal('show');
        });
        // change account / show balance
        $(document).on('change', "#tournament-account", function() {
            let account_id = $(this).val();
            $.ajax({
                url: "/user/tournament/leaderboard/balance-equity",
                method: 'GET',
                data: {
                    account_id: account_id
                },
                success: function(response) {
                    $("#join-account-balance").html(response.balance);
                    $("#join-account-equity").html(response.equity);
                    $("#join-account-type").html(response.type);
                }
            });
        });
        // datatable
        const ranking_report = $("#dt-leaders").DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            responsive: false,
            info: false,
            dom: 'B<"clear">lfrtip',
            pageLength: 10,
            ajax: {
                url: "/user/tournament/leaderboard/leaders",
                data: function(d) {
                    return $.extend({}, d, {
                        'tournament': "{{$tournament?->id}}"
                    });
                },
            },
            columns: [{
                    data: "rank",
                    responsivePriority: 1,
                    className: 'align-middle',
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md px-3">
                                            <i class="text-lg opacity-10" aria-hidden="true">${data}</i>
                                        </div>
                                    </div>`;
                    }
                }, // method
                {
                    data: "tournament",
                    responsivePriority: 5,
                    className: 'align-middle',
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md px-3">
                                        <i class="fas fa-user text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0 h5">${data?.name || '---'}</h4>
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">${data?.country || '---'}</p>
                                    </div>
                                </div>`;
                    }
                },

                {
                    data: "account_num",
                    responsivePriority: 6,
                    className: 'align-middle',
                },
                {
                    data: "profit",
                    responsivePriority: 2,
                    className: 'align-middle',
                },
                {
                    data: "volume",
                    responsivePriority: 1,
                    className: 'align-middle',
                }
            ],
            oLanguage: {
                "sLengthMenu": "\_MENU_",
                "sSearch": ""
            },
            language: {
                paginate: {
                    previous: '<i class="fa-solid fa-chevron-left"></i>',
                    next: '<i class="fa-solid fa-chevron-right"></i>'
                }
            },
        });
    });
    $(document).on('click', "#btn-submit-request", function() {
        $(this).prop('disabled', true);
    })

    function tournamentJoinCallback(response) {
        if (response.status === true) {
            notify('success', response.message, 'Join Tournament');
            setTimeout(function() {
                window.location.href = '/user/tournament/dashboard';
            }, 3000);
        } else {
            notify('error', response.message, 'Join Tournament');
        }
        $.validator("form-join-tournament", response.errors);
        $("#btn-submit-request").prop('disabled', false);
    }
</script>
@stop