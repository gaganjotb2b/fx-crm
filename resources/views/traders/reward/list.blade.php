@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'User Deposit Report')
@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

    <style>
        #deposit_report_datatable tr,
        #deposit_report_datatable td:first-child {
            border-left: 3px solid #4fd1c5;
        }

        #deposit_report_datatable tr,
        #deposit_report_datatable th:first-child {
            border-left: 3px solid;
        }

        #deposit_report_datatable tr,
        #deposit_report_datatable td {
            background-color: #f7fafc;
            vertical-align: middle;
            text-align: left;
        }

        #deposit_report_datatable {
            border-collapse: separate !important;
            border-spacing: 2px 8px;
        }

        .dataTables_length .form-select {
            background-position: right 3px center;
            background-size: 12px 12px;
            padding-right: 1.25rem;
            margin-top: 3px;
        }

        #datatable-search_filter .form-control {
            margin: 3px 3px 0;
        }

        input:focus {
            outline: none !important;
            border: 1px solid #d8d6de;
        }

        .form-select.form-select-sm {
            display: block;
            width: 100%;
            padding-right: 0.5rem 2rem 0.5rem 0.75rem !important;
            -moz-padding-start: calc(0.75rem - 3px);
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.4rem;
            color: #495057;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            border: 1px solid #d2d6da;
            border-radius: 0.5rem;
            transition: box-shadow 0.15s ease, border-color 0.15s ease;
            appearance: none;
        }

        .ps__rail-x {
            display: none !important;
        }

        .btn {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 14px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-transition: all .2s ease-in-out !important;
            -moz-transition: all .2s ease-in-out !important;
            -o-transition: all .2s ease-in-out !important;
            transition: all .2s ease-in-out !important;
        }

        .input-rang-group-text {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.45;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #fff;
            border: 1px solid #d8d6de;
        }

        .min {
            padding: 0 !important;
            margin: 0 !important;
            border-top: 1px solid #d8d6de;
            border-right: none;
            border-bottom: 1px solid #d8d6de;
            border-left: 1px solid #d8d6de;
            text-align: center;
        }

        .dark-version .col-1.input-rang-group-date-logo.rang-max.input-range-gpr-right {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.6rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.45;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #151a2c;
            border-top-left-radius: 0rem !important;
            border-bottom-left-radius: 0rem !important;
            border-right: none !important;
            border: 1px solid #2d3357 !important;
        }

    .progress-item {
        width: 100px;
        text-align: center;
    }

    .progress-container {
        text-align: center;
        height: 100%;
        min-height: 200px;
    }

    .circular-chart {
        width: 100px;
        height: 100px;
        transform: rotate(-90deg);
    }

    .circle-bg {
        fill: none;
        stroke: #eee;
        stroke-width: 3.8;
    }

    .circle {
        fill: none;
        stroke-width: 3.8;
        stroke-linecap: round;
        stroke: #ff8e5c;
        transition: stroke-dasharray 0.6s ease-in-out;
    } 

    </style>
    @if (App\Services\systems\VersionControllService::check_version() === 'lite')
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
    {!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
<!-- main content -->
@section('content')
    <div class="container-fluid py-4">
        <div class="custom-height-con">
            <div class="card">
                <div class="card-body">
                    <!-- Card header -->
                    <div class="d-flex justify-content-between">
                       
                        <h4>Join Reward Details</h4>
                        {{-- <div class="">
                            <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                        </div>

                        <div class="border-bottom border-0">
                            <div class="btn-exports" style="width:200px">
                                <select data-placeholder="Select a state..." class="form-select btExport" id="fx-export">
                                    <option value="download" selected>{{ __('page.export_to') }}</option>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>

                    <div id="reward_details" class="d-none">
                                
                        <div class="d-flex flex-wrap gap-3 row">

                            <div class="col-md-3 d-flex flex-column justify-content-center">
                                <h3 id="reward_name" class="text-start">-</h3>
                                <p class="text-start text-muted">Countdown: <span id="countdown_timer">Loading...</span></p>
                                <div class="mt-3 text-start">
                                    <button id="cancel_reward" class="btn btn-danger">Cancel</button>
                                    <button id="claim_reward" class="btn btn-success">Claim</button>
                                </div>
                            </div>

                            <!-- Deposit Progress -->
                            <div class="progress-container col-md-2 shadow-lg rounded">
                                <br>
                                <div id="deposit_progress">

                                </div>
                                
                                <p>Deposit: <span id="deposit_sum">0</span> / <span id="total_deposit_amount">0</span></p>
                            </div>
                    
                            <!-- Referral Progress -->
                            <div class="progress-container col-md-2 shadow-lg rounded">
                                <br>
                                <div id="referral_progress">

                                </div>

                                <p>Referred: <span id="user_count">0</span> / <span id="total_user_count">0</span></p>
                            </div>
                    
                            <!-- Lot Progress -->
                            <div class="progress-container shadow-lg rounded col-md-2">
                                <br>
                                <div id="lot_progress">

                                </div>

                                <p>Lots: <span id="lot_count">0</span> / <span id="total_lot_count">0</span></p>
                            </div>
                        </div>
                    
                    </div>
                    
                    
                    <div id="no_reward" class="text-center text-muted d-none">
                        <h5>No Record Found</h5>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5>Reward Details</h5>
                    <div class="table-responsive">
                        <table class="table table-flush datatables-ajax w-100 text-center" id="deposit_report_datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Reward Neme</th>
                                    <th>Terms & Conditions</th>
                                    <th>Reward Amount</th>
                                    <th>Date Range</th>
                                    <th>Create Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            {{-- <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align: right;" class="details-control"
                                        rowspan="1">{{ __('ad-reports.total-amount') }} : </th>
                                    <th class="text-left" id="total_1" rowspan="1" colspan="1">$0</th>
                                </tr>
                            </tfoot> --}}
                        </table>
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
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>



    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>


    <script>
        var deposit_report = $("#deposit_report_datatable").fetch_data({
            url: '/user/rewards',
            // csv_export: true,
            // export_col:[0, 1, 2, 3, 4],
            // total_sum: true,
            // length_change: true,
            icon_feather: false,
            customorder: 3,
            // o_Language: true,
            columns: [
                { data: "reward_name" },
                { data: "terms_conditions" },
                { data: "reward_amount" },
                { data: "date_range" },
                { data: "create_date" },
                { data: "action" },
            ],
        })


        $(document).ready(function() {
            fetchAssignedReward();
        
        
            function fetchAssignedReward() {
                $.ajax({
                    url: "{{ route('users.assigned.reward') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.success && response.data.reward) {
                            let data = response.data;

                            $("#reward_name").text(data.reward.name);
                            $("#total_deposit_amount").text(data.total_deposit_amount);
                            $("#deposit_sum").text(data.deposit_sum);
                            $("#total_user_count").text(data.total_user_count);
                            $("#user_count").text(data.user_count);
                            $("#total_lot_count").text(data.total_lot);
                            $("#lot_count").text(data.acheive_lot);

                            $("#reward_details").removeClass("d-none");

                            if (!data.is_complete) {
                                $("#claim_reward").remove();
                            }

                            updateProgressBar("deposit_progress", data.deposit_sum, data.total_deposit_amount);
                            updateProgressBar("referral_progress", data.user_count, data.total_user_count);
                            updateProgressBar("lot_progress", data.acheive_lot, data.total_lot);

                            // updateProgressBar("deposit_progress", data.total_deposit_amount * 0.5, data.total_deposit_amount);
                            // updateProgressBar("referral_progress", data.total_user_count * 0.5, data.total_user_count);
                            // updateProgressBar("lot_progress", data.total_lot * 0.5, data.total_lot);

                            startCountdown(data.reward.end_date);

                        } else {
                            $("#reward_details").addClass("d-none");
                            $("#no_reward").removeClass("d-none");
                            $("#claim_reward").remove();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function startCountdown(endDateStr) {
                let endDate = new Date(endDateStr).getTime();

                let timer = setInterval(function () {
                    let now = new Date().getTime();
                    let timeLeft = endDate - now;

                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        $("#countdown_timer").html("<span class='text-danger'>Time's up!</span>");
                        return;
                    }

                    let days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    let hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    $("#countdown_timer").html(
                        `<span class='text-success'>${days}d ${hours}h ${minutes}m ${seconds}s</span>`
                    );
                }, 1000);
            }
        

            function updateProgressBar(elementId, achieved, required) {
                let progress = required > 0 ? (achieved / required) * 100 : 0;
                progress = Math.min(progress, 100); // Ensure max is 100%

                let circumference = 100; // This is the full stroke-dasharray value

                let dashOffset = circumference - (progress / 100) * circumference;

                let element = document.getElementById(elementId);
                renderApexChart(element, progress)
                // if (element) {
                //     element.setAttribute("stroke-dasharray", circumference);
                //     element.setAttribute("stroke-dashoffset", dashOffset);
                // }
        
            }

            function renderApexChart(element, progress) {
                if (!element) return;

                // Ensure the parent container exists and has dimensions
                const parent = element.parentElement;
                if (!parent) return;
                
                element.innerHTML = ""; // Clear previous chart

                var options = {
                    series: [progress],
                    chart: {
                        type: 'radialBar',
                        height: 300,  // Allow it to be flexible
                        width: '100%',
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 225,
                            hollow: {
                                size: '70%',
                                background: '#fff',
                                dropShadow: {
                                    enabled: true,
                                    top: 3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.5
                                }
                            },
                            track: {
                                background: '#fff',
                                strokeWidth: '67%',
                                margin: 0,
                                dropShadow: {
                                    enabled: true,
                                    top: -3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.7
                                }
                            },
                            dataLabels: {
                                show: true,
                                name: {
                                    offsetY: -10,
                                    show: true,
                                    color: '#888',
                                    fontSize: '17px'
                                },
                                value: {
                                    formatter: function(val) { return parseInt(val); },
                                    color: '#111',
                                    fontSize: '36px',
                                    show: true
                                }
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#ABE5A1'],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    stroke: { lineCap: 'round' },
                    labels: ['Percent'],
                    responsive: [{
                        breakpoint: 768, // Mobile view adjustments
                        options: {
                            chart: { height: 200 },
                            plotOptions: {
                                radialBar: {
                                    dataLabels: {
                                        value: { fontSize: '24px' }
                                    }
                                }
                            }
                        }
                    }]
                };

                var chart = new ApexCharts(element, options);
                chart.render();
            }
        

            function handleRewardAction(action) {
                let route = action === "cancel" ? "{{ route('users.cancel.reward') }}" : "{{ route('users.claim.reward') }}";

                Swal.fire({
                    title: `Are you sure you want to ${action} this reward?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: `Yes, ${action}`,
                    cancelButtonText: "No, cancel",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route,
                            type: "GET",
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    if (action == "cancel"){
                                        Swal.fire("Success", `Reward ${action}ed successfully!`, "success");
                                    }else{
                                        Swal.fire("Success", `Reward approval request created successfully!`, "success");
                                    }
                                    
                                    fetchAssignedReward();
                                } else {
                                    Swal.fire("Error", response.message || "Something went wrong!", "error");
                                }
                            },
                            error: function(xhr) {
                                Swal.fire("Error", "Failed to process request.", "error");
                            }
                        });
                    }
                });
            }

            $("#cancel_reward").click(function() {
                handleRewardAction("cancel");
            });

            $("#claim_reward").click(function() {
                handleRewardAction("claim");
            });


            $(document).on('click', '.btn-assign-reward', function() {
                let reward_id = $(this).data('id'); // Get reward ID from data-id attribute
                let assignUrl = "{{ route('users.assign.rewards', ':id') }}".replace(':id', reward_id);

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to join this reward ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Join!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: assignUrl,
                            type: "GET",  // Changed from POST to GET
                            success: function(response) {
                                $('.btn-assign-reward').remove()
                                fetchAssignedReward();
                                Swal.fire("Joined!", "The reward has been joined successfully.", "success");
                            },
                            error: function(xhr) {
                                Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                            }
                        });
                    }
                });
            });
            
        })


    </script>
@endsection
