@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Participate contest')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<style>
    .contest-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 30px;
        background: #fff;
    }
    
    .contest-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .contest-header {
        position: relative;
        background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
        color: white;
        padding: 30px 25px 20px;
        min-height: 220px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }
    
    .contest-header-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        opacity: 0.6;
        z-index: 1;
        filter: brightness(0.8) contrast(1.2);
    }
    
    .contest-header-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 142, 92, 0.85) 0%, rgba(255, 107, 53, 0.9) 100%);
        z-index: 2;
    }
    
    .contest-header-content {
        position: relative;
        z-index: 3;
    }
    
    .contest-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        letter-spacing: 0.5px;
        background: rgba(0,0,0,0.3);
        padding: 10px 15px;
        border-radius: 8px;
        backdrop-filter: blur(5px);
        display: inline-block;
    }
    
    .contest-subtitle {
        font-size: 12px;
        opacity: 0.9;
        margin-bottom: 25px;
        font-weight: 500;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        background: rgba(0,0,0,0.3);
        padding: 6px 10px;
        border-radius: 4px;
        backdrop-filter: blur(5px);
        display: inline-block;
        position: absolute;
        top: 15px;
        left: 15px;
        color: #fff;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    
    .contest-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        background: rgba(0,0,0,0.4);
        padding: 20px;
        border-radius: 15px;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    
    .stat-item {
        text-align: center;
        flex: 1;
        padding: 0 15px;
    }
    
    .stat-item:first-child {
        border-right: 2px solid rgba(255,255,255,0.4);
    }
    
    .stat-label {
        font-size: 13px;
        opacity: 0.95;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        color: #fff;
    }
    
    .contest-body {
        padding: 25px;
    }
    
    .contest-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #ff8e5c;
    }
    
    .info-icon {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        color: #ff8e5c;
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-label {
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 2px;
    }
    
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    
    .prize-section {
        background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .prize-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .prize-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .prize-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    
    .prize-item:last-child {
        border-bottom: none;
    }
    
    .prize-rank {
        font-weight: 600;
    }
    
    .prize-amount {
        font-weight: 700;
        color: #ffd700;
    }
    
    .join-button {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
        color: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .join-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 142, 92, 0.4);
    }
    
    /* Countdown Timer Styles */
    .countdown-container {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
        color: white;
        transition: all 0.3s ease;
        cursor: not-allowed;
        text-align: center;
    }
    
    .countdown-timer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }
    
    .countdown-item {
        background: rgba(255,255,255,0.2);
        padding: 8px 12px;
        border-radius: 6px;
        min-width: 50px;
        text-align: center;
    }
    
    .countdown-number {
        font-size: 18px;
        font-weight: 700;
        display: block;
    }
    
    .countdown-label {
        font-size: 10px;
        opacity: 0.8;
        text-transform: uppercase;
    }
    
    .contest-started {
        background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
        cursor: pointer;
    }
    
    .contest-started:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 142, 92, 0.4);
    }
    
    .contest-type-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0,0,0,0.6);
        padding: 10px 18px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 700;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255,255,255,0.4);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
</style>
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="custom-height-con">
        <div class="row">
            @foreach($contest as $value)
            <div class="col-lg-4 col-md-6">
                <div class="contest-card">
                    <!-- Contest Header -->
                    <div class="contest-header">
                        <div class="contest-header-bg" style="background-image: url('{{\App\Services\contest\ContestService::contest_popup_file($value->id)}}');"></div>
                        <div class="contest-header-overlay"></div>
                        <div class="contest-header-content">
                            <div class="contest-type-badge">
                                {{ucwords(str_replace('_',' ',$value->contest_type))}}
                            </div>
                            
                            <div class="contest-title">
                                {{ucfirst($value->contest_name)}}
                            </div>
                            
                            <div class="contest-stats">
                                <div class="stat-item">
                                    <div class="stat-label">Participants</div>
                                    <div class="stat-value">{{\App\Services\contest\ContestService::count_total_participant($value->id)}}/{{$value->max_contest}}</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Prize Pool</div>
                                    <div class="stat-value">
                                        @php
                                            $prices = json_decode($value->contest_prices);
                                            $totalPrize = 0;
                                            foreach($prices as $price) {
                                                foreach($price as $pr) {
                                                    $totalPrize += $pr;
                                                }
                                            }
                                        @endphp
                                        ${{number_format($totalPrize)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contest Body -->
                    <div class="contest-body">
                        <!-- Contest Info Grid -->
                        <div class="contest-info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Start Date</div>
                                    <div class="info-value">{{date('M d, Y',strtotime($value->start_date))}}</div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">End Date</div>
                                    <div class="info-value">{{date('M d, Y',strtotime($value->end_date))}}</div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Min Participants</div>
                                    <div class="info-value">{{$value->min_join}}</div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Contest Type</div>
                                    <div class="info-value">{{ucwords(str_replace('_',' ',$value->contest_type))}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Prize Section -->
                        <div class="prize-section">
                            <div class="prize-title">
                                <i class="fas fa-medal me-2"></i>Prize Distribution
                            </div>
                            <div class="prize-list">
                                @php
                                    $prices = json_decode($value->contest_prices);
                                    $rank = 1;
                                @endphp
                                @foreach($prices as $price)
                                @foreach($price as $key=>$pr)
                                <div class="prize-item">
                                    <div class="prize-rank">{{$key}}</div>
                                    <div class="prize-amount">${{number_format($pr)}}</div>
                                </div>
                                @endforeach
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Join Button -->
                        <div class="join-button-container" data-contest-id="{{$value->id}}" data-start-date="{{$value->start_date}}">
                            <!-- Countdown Timer (shown when contest hasn't started) -->
                            <div class="countdown-container" id="countdown-{{$value->id}}" style="display: none;">
                                <div>Contest starts in:</div>
                                <div class="countdown-timer">
                                    <div class="countdown-item">
                                        <span class="countdown-number" id="days-{{$value->id}}">00</span>
                                        <span class="countdown-label">Days</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-number" id="hours-{{$value->id}}">00</span>
                                        <span class="countdown-label">Hours</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-number" id="minutes-{{$value->id}}">00</span>
                                        <span class="countdown-label">Minutes</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-number" id="seconds-{{$value->id}}">00</span>
                                        <span class="countdown-label">Seconds</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Join Button (shown when contest has started) -->
                            <button type="button" class="join-button btn-join-contest contest-started" data-id="{{$value->id}}" id="join-btn-{{$value->id}}" style="display: none;">
                                <i class="fas fa-sign-in-alt me-2"></i>Join Now
                            </button>
                            
                            <!-- Contest Full Message (shown when contest is full) -->
                            <div class="contest-full-message" id="contest-full-{{$value->id}}" style="display: none;">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Contest Full!</strong> Maximum participants reached.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- confirm modal -->
<div class="modal fade" id="modal_contest" tabindex="-1" role="dialog" aria-labelledby="contest-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="contest-join-form" action="{{route('users.join.contest')}}" method="post">
            @csrf
            <input type="hidden" name="contest_id" value="" id="contest-id">
            <div class="modal-header">
                <h5 class="modal-title" id="contest-modal-label">Joining to contest</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 id="contest-title"></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="account">Account</label>
                        <select name="account" id="account" class="form-control form-select form-input">
                            <option value="">Choose an account</option>
                            @foreach($accounts as $value)
                            <option value="{{$value->account_number}}">{{$value->account_number}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="form-join-contest">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-primary" id="btn-request-join" data-btnid="btn-request-join" data-form="contest-join-form" data-callback="join_contest_callback" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" onclick="_run(this)">Join Now</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Account Popup Modal -->
<div class="modal fade" id="create-account-popup" tabindex="-1" role="dialog" aria-labelledby="create-account-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-account-modal-label">Demo Contest Account for joining</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Account Validation Error!</strong>
                        </div>
                        <p id="account-error-message">The selected account already has trading history in our system. For contest participation, you need a fresh demo account with no trading history.</p>
                        <p>Please create a new "Demo Contest Account" to join the contest.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('user.trading-account.open-demo-account') }}" class="btn bg-gradient-primary">
                    <i class="fas fa-plus"></i> Create Now
                </a>
            </div>
        </div>
    </div>
</div>

<!-- include footer -->
@include('layouts.footer')
</div>
@stop
@section('page-js')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>



<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize countdown timers for all contests
        initializeCountdowns();
        
        // Update countdowns every second
        setInterval(function() {
            updateCountdowns();
        }, 1000);
    });
    
    function initializeCountdowns() {
        $('.join-button-container').each(function() {
            const contestId = $(this).data('contest-id');
            const startDate = $(this).data('start-date');
            
            // Check if contest has started
            const now = new Date();
            const contestStart = new Date(startDate);
            
            if (contestStart > now) {
                // Contest hasn't started - show countdown
                $(`#countdown-${contestId}`).show();
                $(`#join-btn-${contestId}`).hide();
            } else {
                // Contest has started - show join button
                $(`#countdown-${contestId}`).hide();
                $(`#join-btn-${contestId}`).show();
            }
        });
    }
    
    function updateCountdowns() {
        $('.join-button-container').each(function() {
            const contestId = $(this).data('contest-id');
            const startDate = $(this).data('start-date');
            
            const now = new Date();
            const contestStart = new Date(startDate);
            const timeLeft = contestStart - now;
            
            if (timeLeft > 0) {
                // Contest hasn't started - update countdown
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                
                $(`#days-${contestId}`).text(days.toString().padStart(2, '0'));
                $(`#hours-${contestId}`).text(hours.toString().padStart(2, '0'));
                $(`#minutes-${contestId}`).text(minutes.toString().padStart(2, '0'));
                $(`#seconds-${contestId}`).text(seconds.toString().padStart(2, '0'));
            } else {
                // Contest has started - check if contest is full
                checkContestCapacity(contestId);
            }
        });
    }
    
    function checkContestCapacity(contestId) {
        $.ajax({
            method: 'GET',
            url: '/user/contest/check-contest-capacity',
            dataType: 'JSON',
            data: {
                contest_id: contestId
            },
            success: function(data) {
                if (data.is_full) {
                    // Contest is full - show full message
                    $(`#countdown-${contestId}`).hide();
                    $(`#join-btn-${contestId}`).hide();
                    $(`#contest-full-${contestId}`).show();
                } else {
                    // Contest has space - show join button
                    $(`#countdown-${contestId}`).hide();
                    $(`#join-btn-${contestId}`).show();
                }
            },
            error: function() {
                // If check fails, show join button as fallback
                $(`#countdown-${contestId}`).hide();
                $(`#join-btn-${contestId}`).show();
            }
        });
    }

    $(document).on('click', '.btn-join-contest', function() {
        $("#modal_contest").modal('show');
        let contest_id = $(this).data('id');
        $.ajax({
            method: 'GET',
            url: '/user/dashboard/get-contest',
            dataType: 'JSON',
            data: {
                contest_id: contest_id
            },
            success: function(data) {
                $('#contest-title').text(data.title);
                $('#start-date').text(data.start_date);
                $("#end-date").text(data.end_date);
                $("#contest-id").val(data.id);
            }
        });
    });

    function join_contest_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Join Contest');
            $("#modal_contest").modal('hide');
        } else {
            if (data.show_create_account_popup) {
                // Show create account popup with dynamic message
                $("#modal_contest").modal('hide');
                
                // Update the error message if provided
                if (data.message) {
                    $('#account-error-message').text(data.message);
                }
                
                $("#create-account-popup").modal('show');
            } else {
                notify('error', data.message, 'Join Contest');
            }
        }
        $.validator('contest-join-form', data.errors);
    }
</script>
@endsection