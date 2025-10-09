@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Contest List')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<style>
    span.input-group-text {
        height: 38px;
    }

    .btn-close.text-dark.btn-popup-close {
        position: absolute;
        right: 19px;
        top: 68px;
        z-index: 2;
    }
    
    /* Contest List Cards */
    .contest-list-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 30px;
        background: #fff;
        border: 1px solid rgba(255,142,92,0.2);
    }
    
    .contest-list-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .contest-list-header {
        position: relative;
        background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
        color: white;
        padding: 25px 20px 15px;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }
    
    .contest-list-header-bg {
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
    
    .contest-list-header-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 142, 92, 0.85) 0%, rgba(255, 107, 53, 0.9) 100%);
        z-index: 2;
    }
    
    .contest-list-header-content {
        position: relative;
        z-index: 3;
    }
    
    .contest-list-title {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        letter-spacing: 0.5px;
        background: rgba(0,0,0,0.3);
        padding: 8px 12px;
        border-radius: 8px;
        backdrop-filter: blur(5px);
        display: inline-block;
    }
    
    .contest-list-subtitle {
        font-size: 11px;
        opacity: 0.9;
        margin-bottom: 20px;
        font-weight: 500;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        background: rgba(0,0,0,0.3);
        padding: 5px 8px;
        border-radius: 4px;
        backdrop-filter: blur(5px);
        display: inline-block;
        position: absolute;
        top: 12px;
        left: 12px;
        color: #fff;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    
    .contest-list-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        background: rgba(0,0,0,0.4);
        padding: 15px;
        border-radius: 12px;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255,255,255,0.3);
        box-shadow: 0 6px 25px rgba(0,0,0,0.3);
    }
    
    .contest-list-stat-item {
        text-align: center;
        flex: 1;
        padding: 0 10px;
    }
    
    .contest-list-stat-item:first-child {
        border-right: 2px solid rgba(255,255,255,0.4);
    }
    
    .contest-list-stat-label {
        font-size: 11px;
        opacity: 0.95;
        margin-bottom: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .contest-list-stat-value {
        font-size: 18px;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        color: #fff;
    }
    
    .contest-list-body {
        padding: 20px;
    }
    
    .contest-list-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 15px;
    }
    
    .contest-list-info-item {
        display: flex;
        align-items: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #ff8e5c;
    }
    
    .contest-list-info-icon {
        width: 18px;
        height: 18px;
        margin-right: 8px;
        color: #ff8e5c;
    }
    
    .contest-list-info-content {
        flex: 1;
    }
    
    .contest-list-info-label {
        font-size: 10px;
        color: #6c757d;
        margin-bottom: 2px;
    }
    
    .contest-list-info-value {
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }
    
    .contest-list-status-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0,0,0,0.6);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255,255,255,0.4);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.3);
    }
    
    .contest-list-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .contest-list-btn {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    
    .contest-list-btn-primary {
        background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
        color: white;
    }
    
    .contest-list-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 142, 92, 0.4);
        color: white;
    }
    
    .contest-list-btn-secondary {
        background: #f8f9fa;
        color: #333;
        border: 1px solid #dee2e6;
    }
    
    .contest-list-btn-secondary:hover {
        background: #e9ecef;
        color: #333;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .contest-list-card {
            margin-bottom: 20px;
        }
        
        .contest-list-header {
            min-height: 150px;
            padding: 20px 15px 12px;
        }
        
        .contest-list-title {
            font-size: 20px;
            padding: 6px 10px;
        }
        
        .contest-list-subtitle {
            font-size: 10px;
            padding: 4px 6px;
            top: 10px;
            left: 10px;
        }
        
        .contest-list-stats {
            padding: 12px;
        }
        
        .contest-list-stat-value {
            font-size: 16px;
        }
        
        .contest-list-stat-label {
            font-size: 10px;
        }
        
        .contest-list-body {
            padding: 15px;
        }
        
        .contest-list-info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .contest-list-info-item {
            padding: 8px;
        }
        
        .contest-list-info-value {
            font-size: 12px;
        }
        
        .contest-list-actions {
            flex-direction: column;
            gap: 8px;
        }
        
        .contest-list-btn {
            padding: 12px;
            font-size: 14px;
        }
    }
    
    @media (max-width: 480px) {
        .contest-list-title {
            font-size: 18px;
        }
        
        .contest-list-stats {
            flex-direction: column;
            gap: 10px;
        }
        
        .contest-list-stat-item:first-child {
            border-right: none;
            border-bottom: 2px solid rgba(255,255,255,0.4);
            padding-bottom: 10px;
        }
        
        .contest-list-stat-value {
            font-size: 14px;
        }
    }
</style>
@if(App\Services\systems\VersionControllService::check_version()==='lite')
<style>
    .dt-buttons .buttons-csv,
    .dt-buttons .buttons-excel,
    .dt-buttons .buttons-copy {
        display: none;
    }
</style>
@endif
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="custom-height-con">
        <div class="card">
            <div class="card-body">
                <!-- Card header -->
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                    </div>

                    <div class="border-bottom border-0 mb-2">
                        <div class="btn-exports" style="width:200px">
                            <select data-placeholder="Select a state..." class="form-select btExport" id="fx-export">
                                <option value="download" selected>{{ __('page.export_to') }}</option>
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                </div>

                <form id="filter-form" class="dt_adv_search" method="POST">
                    <div class="row g-1 mb-md-1">
                        <!-- filter by status -->
                        <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Status">
                            <select class="select2 form-select" name="status" id="filter-status">
                                <optgroup label="status">
                                    <option value="">{{__('ad-reports.all')}}</option>
                                    <option value="active">Active</option>
                                    <option value="closed">Closed</option>
                                </optgroup>
                            </select>
                        </div>
                        <!-- filter by client type -->
                        <div class="col-md-4  mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Client Type">
                            <select class="select2 form-select" name="client_type" id="approved_status">
                                <optgroup label="Search client type">
                                    <option value="">{{__('ad-reports.all')}}</option>
                                    <option value="trader">Trader</option>
                                    <option value="ib">IB</option>
                                </optgroup>
                            </select>
                        </div>
                        <!-- filter by contest name -->
                        <div class="col-md-4">
                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter contest name" class="form-control dt-input dt-full-name" data-column="1" name="contest_name" id="filter-name" placeholder="Contest name" data-column-index="0" />
                        </div>
                        <!-- filter by contest type -->
                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Contest Type">
                            <div class="form-group">
                                <select class="select2 form-select" name="contest_type" id="filter-contest-type">
                                    <optgroup label="Search contest type">
                                        <option value="">{{__('ad-reports.all')}}</option>
                                        <option value="on_profit">On Profit</option>
                                        <option value="on_profit_ratio">On profit ratio</option>
                                        <option value="on_lot">On Lot</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <!-- filter by date range -->
                        <div class="col-md-4 p-1">
                            <div class="col-12">
                                <div class="col-12 input-rang-group">
                                    <span class="col-1 input-rang-group-date-logo rang-min">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6">
                                            </line>
                                            <line x1="8" y1="2" x2="8" y2="6">
                                            </line>
                                            <line x1="3" y1="10" x2="21" y2="10">
                                            </line>
                                        </svg>
                                    </span>
                                    <input type="text" id="from" class="col-4 min flatpickr-basic" name="from" placeholder="YY-MM-DD">
                                    <span class="input-rang-group-text col-1">-</span>
                                    <input type="text" id="to" class="col-4 max flatpickr-basic" name="to" placeholder="YY-MM-DD">
                                    <span class="col-1 input-rang-group-date-logo rang-max" style="border-top-left-radius: 0rem !important;border-bottom-left-radius: 0rem !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6">
                                            </line>
                                            <line x1="8" y1="2" x2="8" y2="6">
                                            </line>
                                            <line x1="3" y1="10" x2="21" y2="10">
                                            </line>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- filter reset button -->
                        <div class="col-md-2">
                            <button id="btn-reset" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                            </button>
                        </div>
                        <!-- filter button -->
                        <div class="col-md-2">
                            <button id="btn-filter" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                <span class="align-middle">{{__('category.FILTER')}}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" id="contest-cards-container">
            <!-- Contest cards will be loaded here -->
        </div>
    </div>
</div>
<!-- Modal sending mail-->
<div class="modal fade text-start modal-success" id="send-mail-pass" tabindex="-1" aria-labelledby="mail-sending-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mail-sending-modal">Sending Mail.....</h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p class="text-warning">Please wait, While we sending mail to - user.</p>
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Modal Themes end -->

<!-- add new card modal  -->
<div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Reason For Decline</h1>

                <!-- form -->
                <form id="ib_decline_request" class="row gy-1 gx-2 mt-75" action="" method="POST">
                    <div class="col-12">
                        <label class="form-label" for="modalAddCardNumber">Reason:</label>
                        <div class="input-group input-group-merge">
                            <input id="reason" name="reason" class="form-control add-credit-card-mask" type="text" placeholder="type here....." aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-25" id="modalAddCard2">
                                <span class="add-card-type"></span>
                            </span>
                            <input type="hidden" name="decline_id" id="decline_id">
                            <input type="hidden" name="user_main_id" id="user_main_id">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-1 mt-1">Yes</button>
                        <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                            No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ add new card modal  -->
<!-- modal for popup -->
<div class="modal fade" id="contest-popup" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 bg-transparent">
                <button type="button" class="btn-close text-dark btn-popup-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-none">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <div class="row">
                        <div class="col-md-12">
                            <img class="img-fluid" id="popup-image" src="" alt="Display Popup image">
                        </div>
                    </div>
                </div>
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
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>
<script>
    $(document).ready(function() {
        loadContestCards();
        
        // Handle filter button click
        $('#btn-filter').on('click', function() {
            loadContestCards();
        });
        
        // Handle reset button click
        $('#btn-reset').on('click', function() {
            $('#filter-form')[0].reset();
            $('.select2').val('').trigger('change');
            loadContestCards();
        });
    });
    
    function loadContestCards() {
        const formData = new FormData($('#filter-form')[0]);
        formData.append('op', 'data_table');
        formData.append('draw', 1);
        formData.append('start', 0);
        formData.append('length', 1000);
        formData.append('order[0][column]', 0);
        formData.append('order[0][dir]', 'asc');
        
        $.ajax({
            url: '/user/contest/contest-list',
            method: 'GET',
            data: Object.fromEntries(formData),
            success: function(response) {
                if (response && response.data && response.data.length > 0) {
                    renderContestCards(response.data);
                } else {
                    $('#contest-cards-container').html('<div class="col-12 text-center py-5"><h4>No contests found</h4></div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading contests:', error);
                $('#contest-cards-container').html('<div class="col-12 text-center py-5"><h4>Error loading contests</h4><p class="text-muted">Please try again later</p></div>');
            }
        });
    }
    
    function renderContestCards(contests) {
        let cardsHtml = '';
        
        contests.forEach(function(contest) {
            // Extract status text from HTML
            let statusText = 'CLOSED';
            let statusHtml = contest.status;
            
            // Remove HTML tags and extract text
            let cleanStatus = statusHtml.replace(/<[^>]*>/g, '').trim();
            
            if (cleanStatus.toLowerCase().includes('active')) {
                statusText = 'ACTIVE';
            } else if (cleanStatus.toLowerCase().includes('disable')) {
                statusText = 'DISABLED';
            } else if (cleanStatus.toLowerCase().includes('closed')) {
                statusText = 'CLOSED';
            }
            
            // Clean up date range (remove HTML tags)
            let dateRange = contest.date_range.replace(/<br\s*\/?>/gi, ' ');
            dateRange = dateRange.replace(/<[^>]*>/g, '');
            
            cardsHtml += `
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contest-list-card">
                        <!-- Contest Header -->
                        <div class="contest-list-header">
                            <div class="contest-list-header-bg"></div>
                            <div class="contest-list-header-overlay"></div>
                            <div class="contest-list-header-content">
                                <div class="contest-list-status-badge">
                                    ${statusText}
                                </div>
                                
                                <div class="contest-list-title">
                                    ${contest.contest_name}
                                </div>
                                
                                <div class="contest-list-stats">
                                    <div class="contest-list-stat-item">
                                        <div class="contest-list-stat-label">Total Join</div>
                                        <div class="contest-list-stat-value">${contest.total_join}</div>
                                    </div>
                                    <div class="contest-list-stat-item">
                                        <div class="contest-list-stat-label">Date Range</div>
                                        <div class="contest-list-stat-value">${dateRange}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contest Body -->
                        <div class="contest-list-body">
                            <!-- Contest Info Grid -->
                            <div class="contest-list-info-grid">
                                <div class="contest-list-info-item">
                                    <div class="contest-list-info-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="contest-list-info-content">
                                        <div class="contest-list-info-label">Create Date</div>
                                        <div class="contest-list-info-value">${contest.create_date}</div>
                                    </div>
                                </div>
                                
                                <div class="contest-list-info-item">
                                    <div class="contest-list-info-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="contest-list-info-content">
                                        <div class="contest-list-info-label">Status</div>
                                        <div class="contest-list-info-value">${statusText}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="contest-list-actions">
                                <a href="/user/contest/contest-leaderboard/${contest.id}" class="contest-list-btn contest-list-btn-primary">
                                    <i class="fas fa-trophy"></i> View Leaderboard
                                </a>
                                <button onclick="showContestDetails('${contest.contest_name}')" class="contest-list-btn contest-list-btn-secondary">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#contest-cards-container').html(cardsHtml);
    }
    
    function viewContestStats(contestId) {
        // Implement contest stats view functionality
        console.log('Viewing stats for contest:', contestId);
    }
    
    // Show contest details modal
    function showContestDetails(contestName) {
        let modalHtml = `
            <div class="modal fade" id="contestDetailsModal" tabindex="-1" aria-labelledby="contestDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="contestDetailsModalLabel">
                                <i class="fas fa-info-circle me-2"></i>Contest Details - ${contestName}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="mb-3"><i class="fas fa-gavel me-2"></i>Terms and Conditions</h6>
                                        <p class="mb-0">Please read all terms and conditions carefully before participating in this contest.</p>
                                    </div>
                                    
                                    <div class="terms-content">
                                        <div class="term-section mb-4">
                                            <h6 class="term-title">
                                                <i class="fas fa-user-check text-success me-2"></i>1. KYC is mandatory
                                            </h6>
                                            <p class="term-description">
                                                All participants must complete the Know Your Customer (KYC) process before entering the contest.
                                            </p>
                                        </div>
                                        
                                        <div class="term-section mb-4">
                                            <h6 class="term-title">
                                                <i class="fas fa-ban text-danger me-2"></i>2. Hedge trading is not allowed
                                            </h6>
                                            <p class="term-description">
                                                Hedging (placing trades in opposite directions simultaneously) is strictly prohibited during the contest.
                                            </p>
                                        </div>
                                        
                                        <div class="term-section mb-4">
                                            <h6 class="term-title">
                                                <i class="fas fa-plus-circle text-primary me-2"></i>3. Fresh account required
                                            </h6>
                                            <p class="term-description">
                                                Participants must use a newly created trading account for both demo and live contests.
                                            </p>
                                        </div>
                                        
                                        <div class="term-section mb-4">
                                            <h6 class="term-title">
                                                <i class="fas fa-chart-line text-warning me-2"></i>4. Maximum Leverage
                                            </h6>
                                            <div class="term-description">
                                                <ul class="list-unstyled ms-3">
                                                    <li><strong>Demo Account:</strong> 1:500</li>
                                                    <li><strong>Live Account:</strong> 1:300</li>
                                                </ul>
                                                <p class="mb-0">Leverage must not exceed the specified limits.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="term-section mb-4">
                                            <h6 class="term-title">
                                                <i class="fas fa-clock text-info me-2"></i>5. Positions must be closed before the contest ends
                                            </h6>
                                            <p class="term-description">
                                                All open positions must be closed before the official contest deadline.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        $('#contestDetailsModal').remove();
        
        // Add modal to body
        $('body').append(modalHtml);
        
        // Show modal
        $('#contestDetailsModal').modal('show');
    }
</script>

<style>
/* Terms and Conditions Modal Styles */
.terms-content {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
}

.term-section {
    border-left: 4px solid #e9ecef;
    padding-left: 15px;
    transition: all 0.3s ease;
}

.term-section:hover {
    border-left-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
    padding-left: 20px;
}

.term-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.term-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0;
}

.term-description ul {
    margin-bottom: 10px;
}

.term-description li {
    margin-bottom: 5px;
    padding-left: 5px;
}

/* Modal scrollbar styling */
.terms-content::-webkit-scrollbar {
    width: 6px;
}

.terms-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.terms-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.terms-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection