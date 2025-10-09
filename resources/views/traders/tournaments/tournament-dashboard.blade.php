@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Tournament Dashboard')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('common-css/data-list-style.css') }}">
    <style>
        .list-row:last-child {
            border-bottom: none !important;
        }

        .card.trade_account_card_dash {
            min-height: 355px !important;
            display: flex;
        }

        .modal-header {
            justify-content: flex-start;
        }

        .data-list-total {
            display: flex;
            font-size: 13px;
            float: left;
            margin-top: 5px;
        }

        .lgrp-paginate {
            float: right;
            display: flex;
        }

        .btn-close.text-dark.btn-popup-close {
            position: absolute;
            right: 19px;
            top: 68px;
            z-index: 2;
        }
    </style>
    <style>
    /* .bracket-wrapper {
      max-width: 900px;
      margin: 2rem auto;
    } */

    .round-row {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
        /* border: 1px solid; */
        padding: 1rem;
        border-radius: 5px;
    }

    .round-label {
      font-weight: bold;
      font-size: 1.2rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    .match {
      /* border: 1px solid #333; */
      box-shadow: -5px 5px 5px rgb(92 255 234 / 24%);
      border-radius: 0.25rem;
      padding: 0.5rem;
      text-align: center;
      background: #f8f9fa;
      min-width: 200px;
    }

    .connector-down {
      height: 30px;
      width: 2px;
      background: #333;
      margin: 0 auto;
    }

    .merge-line {
      height: 2px;
      background: #333;
      width: 30px;
    }

    .merge-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.5rem;
      margin: -1.5rem 0 1.5rem;
    }
  </style>
@stop
@section('bread_crumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
                <a class="opacity-3 text-dark" href="javascript:;">
                    <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>shop </title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g transform="translate(0.000000, 148.000000)">
                                        <path
                                            d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                        </path>
                                        <path
                                            d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                    href="javascript:;">Tournament Dashboard</a></li>
        </ol>
        <h6 class="font-weight-bolder mb-0">{{ __('page.trader-area') }}</h6>
    </nav>
@stop
@section('content')
    @php
        use App\Services\AllFunctionService;
        $all_fun = new AllFunctionService();
    @endphp
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 position-relative z-index-2">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 mt-sm-0 mt-4">
                        <div class="card mb-4" style="background-color: #8392AB !important;">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-sm text-capitalize font-weight-bold text-center mb-3">
                                            <span style="background:white; border-radius:5px; padding:5px 10px;">Round 1</span>
                                        </p>
                                        <div class="round-row">
                                            @foreach($round1_groups as $group)
                                                @php
                                                    $startTime = (!empty($group->start_trading) && $group->start_trading !== '0000-00-00 00:00:00')
                                                        ? \Carbon\Carbon::parse($group->start_trading)
                                                        : null;
                                                    $endTime = ($startTime && $tourSetting->group_trading_duration > 0)
                                                        ? $startTime->copy()->addDays($tourSetting->group_trading_duration)
                                                        : null;
                                                @endphp

                                                <div class="card match" 
                                                    @if($endTime)
                                                        data-end="{{ $endTime->toIso8601String() }}" 
                                                        id="group-timer-{{ $group->id }}"
                                                    @endif
                                                >
                                                    <h5 class="p-2 text-center bg-primary mb-0">{{ $group->group_name }}</h5>
                                                    @if($endTime)
                                                        <div class="text-center bg-dark py-1">
                                                            <small class="countdown text-light" data-group-id="{{ $group->id }}"></small>
                                                        </div>
                                                    @endif
                                                    <div class="bg-white p-0">
                                                        <div class="d-flex flex-column text-white">

                                                            @forelse($group->participants as $participant)
                                                                <div class="d-flex justify-content-between bg-secondary p-2 mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$participant->user->email}}">
                                                                    <span>
                                                                    @php
                                                                        $avatar = (($participant?->user?->description?->profile_avater == "https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:crmassets/images/avater-men.png") || $participant?->user?->description?->profile_avater == null) ? null : $participant->user->description->profile_avater;
                                                                    @endphp
                                                                    
                                                                    <img src="{{ $avatar ? asset('Uploads/profile/' . $avatar) : asset('admin-assets/app-assets/images/avatars/avater-men.png') }}"
                                                                         height="20" alt="profile_image" class="w-100 border-radius-lg shadow-sm">

                                                                    </span>
                                                                    <span><a class="{{(($participant?->group1_status =='gained') ? 'text-success':'')}} {{(($participant?->group1_status =='failed') ? 'text-danger':'')}}" href="{{url('user/tournament/trading-account-history/'.$participant?->account_num.'/'.$group->id)}}">{{ $participant?->account_num ?? 'Unknown' }}</a></span>
                                                                    <!--<span class="account_delete {{($participant?->user_id == auth()->id())?'':'d-none'}}" data-account_num="{{ $participant?->account_num ?? '' }}"><i class="fas fa-trash"></i></span>-->
                                                                </div>
                                                            @empty
                                                                <div class="d-flex justify-content-center bg-light text-dark p-2">
                                                                    --- No participants ---
                                                                </div>
                                                            @endforelse

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 position-relative z-index-2">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 mt-sm-0 mt-4">
                        <div class="card mb-4" style="background-color:rgb(152, 159, 171) !important;">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-sm mb-3 text-capitalize font-weight-bold text-center">
                                            <span style="background:white; border-radius:5px; padding:5px 10px;">Round 2</span>
                                        </p>
                                        <div class="round-row">
                                            @foreach($round2_groups as $group)
                                                <div class="card match">
                                                    <h5 class="p-2 text-center bg-primary">{{ $group->group_name }}</h5>
                                                    <div class="bg-white p-0">
                                                        <div class="d-flex flex-column text-white">

                                                            @forelse($group->participants as $participant)
                                                                <div class="d-flex justify-content-between bg-secondary p-2 mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$participant?->user?->email}}">
                                                                    <span>
                                                                    @php
                                                                        $avatar = ($participant?->user?->description?->profile_avater == "https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:crmassets/images/avater-men.png") ? null : $participant?->user?->description?->profile_avater;
                                                                    @endphp
                                                                    
                                                                    <img src="{{ $avatar ? asset('Uploads/profile/' . $avatar) : asset('admin-assets/app-assets/images/avatars/avater-men.png') }}"
                                                                         height="20" alt="profile_image" class="w-100 border-radius-lg shadow-sm">

                                                                    </span>
                                                                    <span>{{ $participant?->account_num ?? 'Unknown' }}</span>
                                                                    <!--<span class="account_delete" data-account_num="{{ $participant?->account_num ?? '' }}"><i class="fas fa-trash"></i></span>-->
                                                                </div>
                                                            @empty
                                                                <div class="d-flex justify-content-center bg-light text-dark p-2">
                                                                    --- No participants ---
                                                                </div>
                                                            @endforelse

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 position-relative z-index-2">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 mt-sm-0 mt-4">
                        <div class="card mb-4" style="background-color:rgb(172, 175, 180) !important;">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-sm mb-3 text-capitalize font-weight-bold text-center">
                                            <span style="background:white; border-radius:5px; padding:5px 10px;">Round 3</span>
                                        </p>
                                        <div class="round-row">
                                            @foreach($round3_groups as $group)
                                                <div class="card match">
                                                    <h5 class="p-2 text-center bg-primary">{{ $group->group_name }}</h5>
                                                    <div class="bg-white p-0">
                                                        <div class="d-flex flex-column text-white">

                                                            @forelse($group->participants as $participant)
                                                                <div class="d-flex justify-content-between bg-secondary p-2 mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$participant?->user?->email}}">
                                                                    <span>
                                                                    @php
                                                                        $avatar = ($participant?->user?->description?->profile_avater == "https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:crmassets/images/avater-men.png") ? null : $participant?->user?->description?->profile_avater;
                                                                    @endphp
                                                                    
                                                                    <img src="{{ $avatar ? asset('Uploads/profile/' . $avatar) : asset('admin-assets/app-assets/images/avatars/avater-men.png') }}"
                                                                         height="20" alt="profile_image" class="w-100 border-radius-lg shadow-sm">

                                                                    </span>
                                                                    <span>{{ $participant?->account_num ?? 'Unknown' }}</span>
                                                                    <!--<span class="account_delete" data-account_num="{{ $participant?->account_num ?? '' }}"><i class="fas fa-trash"></i></span>-->
                                                                </div>
                                                            @empty
                                                                <div class="d-flex justify-content-center bg-light text-dark p-2">
                                                                    --- No participants ---
                                                                </div>
                                                            @endforelse

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 position-relative z-index-2">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 mt-sm-0 mt-4">
                        <div class="card mb-4" style="background-color:rgb(164, 197, 255) !important;">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-sm mb-3 text-capitalize font-weight-bold text-center">
                                            <span style="background:white; border-radius:5px; padding:5px 10px;">Round 4</span>
                                        </p>
                                        <div class="round-row">
                                            @foreach($round4_groups as $group)
                                                <div class="card match w-50"">
                                                    <h5 class="p-2 text-center bg-warning">{{ $group->group_name }}</h5>
                                                    <div class="bg-white p-0">
                                                        <div class="d-flex flex-column text-white">

                                                            @forelse($group->participants as $participant)
                                                                <div class="d-flex justify-content-between bg-secondary p-2 mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$participant?->user?->email}}">
                                                                    <span>
                                                                    @php
                                                                        $avatar = ($participant?->user?->description?->profile_avater == "https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:crmassets/images/avater-men.png") ? null : $participant?->user?->description?->profile_avater;
                                                                    @endphp
                                                                    
                                                                    <img src="{{ $avatar ? asset('Uploads/profile/' . $avatar) : asset('admin-assets/app-assets/images/avatars/avater-men.png') }}"
                                                                         height="20" alt="profile_image" class="w-100 border-radius-lg shadow-sm">

                                                                    </span>
                                                                    <span>{{ $participant?->account_num ?? 'Unknown' }}</span>
                                                                    <!--<span class="account_delete" data-account_num="{{ $participant?->account_num ?? '' }}"><i class="fas fa-trash"></i></span>-->
                                                                </div>
                                                            @empty
                                                                <div class="d-flex justify-content-center bg-light text-dark p-2">
                                                                    --- No participants ---
                                                                </div>
                                                            @endforelse

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 position-relative z-index-2">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 mt-sm-0 mt-4">
                        <div class="card mb-4" style="background-color:rgb(45 110 110) !important;">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-sm mb-3 text-capitalize font-weight-bold text-center">
                                            <span style="background:white; border-radius:5px; padding:5px 10px;">Grand Finale</span>
                                        </p>
                                        <div class="round-row">
                                            @foreach($round4_groups as $group)
                                                <div class="card match w-50">
                                                    <h5 class="p-2 text-center bg-success">{{ $group->group_name }}</h5>
                                                    <div class="bg-white p-0">
                                                        <div class="d-flex flex-column text-white">

                                                            @forelse($group->participants as $participant)
                                                                <div class="d-flex justify-content-between bg-secondary p-2 mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$participant?->user?->email}}">
                                                                    <span>
                                                                    @php
                                                                        $avatar = ($participant?->user?->description?->profile_avater == "https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:crmassets/images/avater-men.png") ? null : $participant?->user?->description?->profile_avater;
                                                                    @endphp
                                                                    
                                                                    <img src="{{ $avatar ? asset('Uploads/profile/' . $avatar) : asset('admin-assets/app-assets/images/avatars/avater-men.png') }}"
                                                                         height="20" alt="profile_image" class="w-100 border-radius-lg shadow-sm">

                                                                    </span>
                                                                    <span>{{ $participant?->account_num ?? 'Unknown' }}</span>
                                                                    <!--<span class="account_delete" data-account_num="{{ $participant?->account_num ?? '' }}"><i class="fas fa-trash"></i></span>-->
                                                                </div>
                                                            @empty
                                                                <div class="d-flex justify-content-center bg-light text-dark p-2">
                                                                    --- No participants ---
                                                                </div>
                                                            @endforelse

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
@stop

@section('page-js')
    <script src="{{ asset('/common-js/finance.js') }}"></script>
    <script src="{{ asset('/common-js/data-list.js') }}"></script>
    <script>

        // view popup modal
        $(document).on("click", ".btn-permanently-close", function() {
            var popup_id = $('#popup-id').val();
            $.ajax({
                method: 'GET',
                url: '/user/dashboard/popup-permanently-close/' + popup_id,
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == false) {
                        notify('error', data.message, 'Popup');
                    }
                    if (data.status == true) {
                        notify('success', data.message, 'Popup');
                    }
                }
            });
        });
        
        // view contest modal
        $(document).on("click", ".btn-view-contest", function() {
            $("#modal_contest").modal('show');
        });
        // account_delete action
        $(document).on("click", ".account_delete", function() {
            let account_num = $(this).data('account_num');
            console.log(account_num);
            $(this).find('.account_delete').html("<i class='fa-spin fas fa-circle-notch'></i>");
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure? to delete the participant!',
                html: 'If you want to delete the participant please click OK, otherwise simply click cancel',

                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-warning',
                    cancelButton: 'btn btn-danger'
                },
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/user/tournament/delete-participant',
                        method: 'POST',
                        dataType: 'JSON',
                        data : {
                            account_num : account_num
                        },
                        success: function(data) {
                            if (data.status == true) {
                                notify('success', data.message, 'Delete Participant')
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                notify('error', data.message, 'Delete Participant')
                            }
                            $($this).find('.account_delete').html(label);
                        }
                    })
                } else {
                    $($this).find('.account_delete').html(label);
                }
            });
        });
    </script>
    <script>
        function startCountdown() {
            document.querySelectorAll('.card.match[data-end]').forEach(card => {
                const endTime = new Date(card.dataset.end);
                const countdownEl = card.querySelector('.countdown');
    
                const interval = setInterval(() => {
                    const now = new Date();
                    const diff = endTime - now;
    
                    if (diff <= 0) {
                        clearInterval(interval);
                        countdownEl.innerHTML = '<span class="badge bg-danger">Time Up</span>';
                        return;
                    }
    
                    const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                    const minutes = Math.floor((diff / (1000 * 60)) % 60);
                    const seconds = Math.floor((diff / 1000) % 60);
    
                    countdownEl.innerHTML = `<span class="badge bg-success">${hours}h ${minutes}m ${seconds}s left</span>`;
                }, 1000);
            });
        }
    
        document.addEventListener("DOMContentLoaded", startCountdown);
    </script>


@stop
