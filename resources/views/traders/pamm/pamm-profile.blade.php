@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Pamm Profile')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/pamm.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/color.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/pamm_chart_cust.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/flaviusmatis-simplePagination/simplePagination2.css') }}" />
<link href=" https://cdn.jsdelivr.net/npm/apexcharts@3.42.0/dist/apexcharts.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}" />


<style>
    #fx-master-data {
        min-height: 120px;
        position: relative;
    }

    .fx-tbl-rows-loading {
        position: absolute;
        width: 100%;
        height: max-content;
        z-index: 999;
        color: inherit;
        background: aliceblue;
        margin-top: 0 !important;
    }

    .r-card__profit-body-line._negative {
        background: #4a4a75;
        border-top-right-radius: 100px;
        border-bottom-right-radius: 100px;
    }

    .sp-dark-theme .current {
        background: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%);
        color: #FFF;
        border-color: #f7fafc;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2), 0 0 1px 1px rgba(0, 0, 0, 0.1) inset;
        cursor: default;
        border-radius: 25px;
        padding: .3rem 1rem;
    }

    .sp-dark-theme a,
    .sp-dark-theme span {
        float: left;
        color: #CCC;
        font-size: 14px;
        line-height: 24px;
        font-weight: normal;
        text-align: center;
        border: 1px solid #49c1b6;
        min-width: 14px;
        padding: 0 7px;
        margin: 0 5px 0 0;
        border-radius: 3px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        background: #555;
        background: -moz-linear-gradient(top, #555 0%, #333 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #555), color-stop(100%, #333));
        background: #49c1b6;
        background: -o-linear-gradient(top, #555 0%, #333 100%);
        background: -ms-linear-gradient(top, #555 0%, #333 100%);
        background: linear-gradient(top, #555 0%, #333 100%);
    }

    input.form-control {
        background-color: inherit;
        color: var(--font-color);
        border: 1px solid var(--border-color);
    }

    .r-card {
        border: 1px solid #dbe0e3;
    }

    .r-card.sk-r-card {
        border: none;
        border-bottom: 1px solid #dbe0e3;
    }

    .r-card {
        background: #c5c7c80a;
    }

    .r-card.sk-r-card {
        background: #fff;
    }

    .dark-version .r-card.sk-r-card {
        background: #111322;
    }

    .dark-version .fx-tbl-rows-loading {
        color: #111322;
    }

    .custom-font {
        font-family: Verdana, Times, serif;
    }
</style>
@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="row row-xs mg-t-10">
        <div class="fx-upumm-con">
            <div class="row">
                <form id="filter_form" action="" method="get" style="">
                    @csrf
                    <input type="hidden" name="op" value="datatable" />
                    <input type="hidden" id="page_num" name="page" value="1" />
                    <input type="hidden" id="rpp" name="rpp" value="8" />
                    <div class="col-sm-12 col-lg-12 custom-card">
                        <div class="fx-section-body card">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="rating__head">Master Rattings</h3>
                                    <br />
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-12 col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="filter-gainer">Whom to show first</label>
                                                <select class="form-control multisteps-form__input choice-material filter_fields" id="filter-gainer" name="filter_gainer">
                                                    <option value="">Choose an option</option>
                                                    <option value="DESC">Top gainers</option>
                                                    <option value="ASC">Most popular</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group ">
                                                <label for="filter-duration">Filter by duration</label>
                                                <select class="form-control multisteps-form__input choice-colors filter_fields" id="filter-duration" name="filter_duration">
                                                    <option value="">All time</option>
                                                    <option value="14">2 weeks</option>
                                                    <option value="30">1 month</option>
                                                    <option value="90">3 months</option>
                                                    <option value="180">6 months</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 filter-margin ">
                                    <div class="row filter-row">
                                        <div class="col-6 filter-margin">
                                            <div class="form-group">
                                                <div class="filter-more active fx-filter-d">
                                                    <div class="filter-d__more-icon">
                                                        <!-- <img src="{{ asset('trader-assets/assets/img/pamm/copy/load.png') }}" /> -->
                                                        <i class="fas fa-bars"></i>
                                                    </div>
                                                    <div class="filter-more-text">Filters</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                            <div class="filter-change-view fx-view-btns">
                                                <div class="search-box-main">
                                                    <div class="rating__search">
                                                        <div class="rating__search-button target">
                                                            <i class="fab fa-searchengin"></i>
                                                        </div>
                                                        <div class="rating__search-input-area">
                                                            <input name="filter_text" type="text" placeholder="Search By Name/Email/Username/Account" class="rating__search-input text-filter" style="visibility: visible;">
                                                        </div>
                                                    </div>

                                                </div>

                                                <button type="button" class="filter-view-button active table list_on d-none" id="">
                                                    <div class="filter-view-button-pin"></div>
                                                    <div class="filter-view-button-pin"></div>
                                                    <div class="filter-view-button-pin"></div>
                                                </button>
                                                <button type="button" onclick="grid_on()" id="fx-master-grid-btn" class="filter-view-button cards fx-filter-vm grid_on active">
                                                    <div class="filter-view-button-pins-row">
                                                        <div class="filter-view-button-pin"></div>
                                                        <div class="filter-view-button-pin"></div>
                                                    </div>
                                                    <div class="filter-view-button-pins-row">
                                                        <div class="filter-view-button-pin"></div>
                                                        <div class="filter-view-button-pin"></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="rating_top"></div> -->
                    </div>
                    <div class="col-sm-12 col-lg-12 fade-section">
                        <div class="fx-section-body card">
                            <div class="row">
                                <div class="col-sm-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Minimum investment</label>
                                        <input id="fx-filter-mdepo" type="number" name="filter_deposit" class="form-control filter_fields" placeholder="$ 25 or more" />
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-4">
                                    <div class="form-group  ">
                                        <label>Minimum expertise</label>
                                        <select id="fx-filter-expert" name="filter_expert" class="form-control multisteps-form__input btExport  filter_fields text-filter" data-plugin-options='{ "placeholder": "Select a State", "allowClear": true }'>
                                            <option value="">Choose an option</option>
                                            <?php $asset_urlbg = asset('trader-assets/assets/img/pamm/user_image.png'); ?>
                                            <option value="365" style="background: url('{{$asset_urlbg}}')">
                                                Legends
                                            </option>
                                            <option value="14">2 weeks</option>
                                            <option value="30">1 month</option>
                                            <option value="90">3 months</option>
                                            <option value="180">6 months</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-4" style="margin-top: 33px">
                                    <div class="form-group trial-form-group" style="margin-left: 20px">
                                        <input class="form-check-input" type="checkbox" id="autoSizingCheck" />
                                        <label class="form-check-label" for="autoSizingCheck">
                                            Free 7-day trial
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-sm-12 col-lg-12 fade-section" style="margin-top: 3px">
                    <div class="fx-section-body card">
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6 no-gutters">
                                <div class="row align-items-center">
                                    <div class="col-6 col-lg-9 master-trader-text" style="text-align: right">
                                        <span>(<span id="total-master-shown">0</span>) MASTER
                                            TRADERS SHOWN</span>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group">
                                            <div class="filter-reset">Reset all</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="rating_top"></div> -->
                </div>
                <div class="col-sm-12">
                    <div id="tab2" class="tab-pane active">
                        <div class="row">
                            <!-- single copy item  -->
                            <div class="col-lg-12 sk-custom-lebel">
                                <div class="rating__list _cards">
                                    <div class="r-cards sk-r-cards">
                                        <a href="#" class="r-cards__card card me-0">
                                            <div class="r-card sk-r-card">
                                                <div class="r-card__head sk-r-card__head ps-0 c-card_none" style="opacity: 0; visibility:hidden">
                                                    <div class="r-card__view">
                                                        <?php $rcardavater = asset('trader-assets/assets/img/pamm/user_image.png'); ?>
                                                        <div class="r-card__avatar" style=" background-image: url({{$rcardavater}});">
                                                        </div>
                                                        <div class="r-card__country">
                                                            <img src="{{asset('trader-assets/assets/img/pamm/bd.svg')}}" class="r-table__about-image-country-view" />
                                                        </div>
                                                    </div>
                                                    <div class="r-card__about">
                                                        <div class="r-card__name">Arif Ahmed</div>
                                                        <div class="r-card__master-exp">
                                                            <div class="master-exp master-exp _r-cards">
                                                                <div class="master-exp__nav">
                                                                    <div class="master-exp__text">
                                                                        <i class="fas fa-star-of-life star-achive"></i>
                                                                        &nbsp;High achiever
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="r-card__inner sk-r_card_inner sk-card-inner-flex">
                                                    <div class="r-table__section _risk">
                                                        <div class="r-table__section-name">Risk score</div>
                                                        <div class="r-table__section-desc">All time</div>
                                                    </div>
                                                    <div class="r-card__body sk-r-card__body">
                                                        <div class="r-table__section-name">Gain</div>
                                                        <div class="r-table__section-desc">All time</div>
                                                    </div>
                                                    <div class="r-card-copi sk-custom-copi sk-r-card-copi w">
                                                        <div class="r-table__section-name">
                                                            profit and loss
                                                        </div>
                                                        <div class="r-table__section-desc">All time</div>
                                                    </div>
                                                    <div class="r-card-copi sk-custom-copi sk-r-card-copi">
                                                        <div class="r-table__section-name">copiers</div>
                                                        <div class="r-table__section-desc">All time</div>
                                                    </div>
                                                    <div class="r-card__footer sk-r-card-footer">
                                                        <div class="r-table__section-name">Commission</div>
                                                    </div>
                                                </div>
                                                <div class="r-table__body" id="fx-master-data" style="display: none">
                                                    <!-- load master data here for list style -->
                                                    <div class="fx-tbl-rows-loading">Data Loading......</div>
                                                    <!-- <div class="fx-tbl-rows" id="pamm_result" style="">
                                                        </div> -->
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="data_get"></div>
                        </div>
                        <div class="r-table__body " id="fx-master-data">
                            <!-- load master data here for list style -->
                            <div class="fx-tbl-rows-loading  ">
                                <button class="btn btn-primary btn-rounded btn-sm btn-outline-danger text-capitalize" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading........
                                </button>
                            </div>
                            <div class="row-container row" id="pamm_result">

                            </div>
                            <!-- datatable rows static-->
                            <div id="static-row" style="display: none;">
                                <div class="col-lg-3 sk-custom-col static-row-content mb-4">
                                    <!--start pamm profile -->
                                    <div id="card-border-chart-0" class="position-relative main-chart " style=" border-color: #696BC7">
                                        <div class="mt-2 ms-2">
                                            <div class="position-relative">
                                                <img class="rounded-circle border ms-1 me-1 float-left" src="https://social.fxcrm.net/public/admin-assets/app-assets/images/avatars/avater-lady.png" alt="avatar" height="40" width="40" />
                                                <div class="r-card__country" style=" 
                                                  left: 33px;
                                                  bottom: -31px; 
                                                  color: aliceblue;
                                                  font-size: 15px;
                                                  width: 19px;
                                                  height: 11px;
                                                  display: flex;
                                                    align-content: center;
                                                    justify-content: center;
                                                  ">
                                                    <img src="{{asset('trader-assets/assets/img/pamm/bd.svg')}}" class="r-table__about-image-country-view m-0" />
                                                </div>
                                            </div>
                                            <div class="user-name-label-con  float-none">
                                                <h6 class="r-card__name m-0 user-name text-dark ms-2 text-capitalize">Jon Abraham</h6>
                                                <p class="ms-6 mt-1 user-name-label text-secondary custom-font">
                                                    <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-asterisk" viewBox="0 0 16 16">
                                                        <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z" />
                                                    </svg>
                                                    Starter
                                                </p>
                                            </div>
                                            <div class="clear-both"></div>
                                            <p class="font-10 ms-3" style="
                                            	margin-top: -8px;
                                            ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-graph-down-arrow" viewBox="0 0 16 16" style=" background: #df820b47;
                                                  color:#ED502F;
                                                  border-radius: 50%; margin-top: -6px;">
                                                    <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 11.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-1 0v2.6l-3.613-4.417a.5.5 0 0 0-.74-.037L7.06 8.233 3.404 3.206a.5.5 0 0 0-.808.588l4 5.5a.5.5 0 0 0 .758.06l2.609-2.61L13.445 11H10.5a.5.5 0 0 0-.5.5Z" />
                                                </svg>
                                                <span class="risk-score__value font-12 text-danger">
                                                    1
                                                </span>
                                                <span class="text-secondary custom-font">Potential Risk Score</span>
                                            </p>
                                        </div>
                                        <div class="pamm-info-icon text-info"><i data-feather='alert-octagon' style="font-size:13px;"></i></div>
                                        <div class="show-chart" id="chart-0"></div>
                                        <!-- start bottom option  -->
                                        <div class="d-flex justify-content-between align-items-center card-row  ps-1 pe-1">
                                            <div>
                                                <div class="r-card__gain">
                                                    <div class="r-card__param sk-d-block sk-d-none text-capitalize text-dark custom-font">
                                                        Gain
                                                    </div>
                                                    <div class="r-card__gain-value sk-r-card__gain-value r-table__row-gain r-card__row-gain">
                                                        0
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-dark m-0 font-12 text-capitalize custom-font">Copier</p>
                                                <div class="r-card__copiers-wrap">
                                                    <div class="r-card__copiers-count">
                                                        0
                                                    </div>
                                                    <div class="r-card__copiers-delta-image _positive copier-up-down-img">
                                                        <img src="{{asset('trader-assets/assets/img/pamm/up.png')}}" class="r-card__copiers-delta-image-file" />
                                                    </div>
                                                    <div class="r-card__copiers-delta _positive">
                                                        0
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end bottom option  -->
                                        <!-- start button option -->
                                        <div class="d-flex justify-content-between align-items-center ps-1 pe-1">
                                            <div>
                                                <p class="m-0 font-12  text-capitalize text-dark custom-font">Commission</p>
                                                <p class="text-secondary m-0  font-20 r-card__comission-value">10.07%</p>
                                            </div>
                                            <div>
                                                <p class="text-dark m-0 font-12 text-capitalize text-dark custom-font">Days in System</p>
                                                <p class="text-secondary m-0  font-20">0.00%</p>
                                            </div>
                                        </div>
                                        <!-- end bottom option  -->
                                        <!-- start button option -->
                                        <div class="d-flex justify-content-center align-items-center" style=" height: 70px">
                                            <a href="#" class="profile-overview-link btn  w-75 text-dark" id="chart-0-btn" type="button" style="
                                                    padding: 10px !important;
                                                    border-radius: 0; 
                                                  ">
                                                STATISTICS
                                            </a>
                                        </div>
                                        <!-- end button  -->
                                    </div>
                                    <!--end pamm profile -->
                                </div>
                                {{-- </div> --}}
                            </div>
                            <!-- datatable rows end -->
                        </div>
                    </div>
                </div>
                <br />
                <br />
                <div class="col-sm-12 row mt-5">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <!-- load pagination buttons here -->

                        <div class="fx-page-con">
                            <div class="pagination">
                                <!-- <a class="fx-master-page fx-angle-disabled" href="javascript:void(0);" data-pageid="1" data-max="1"><i class="fa fa-chevron-left"></i></a><a href="javascript:void(0);" data-pageid="1" data-max="0" class="active fx-master-page">1</a><a href="javascript:void(0);" class="fx-angle-disabled fx-master-page" data-pageid="1" data-max="1"><i class="fa fa-chevron-right"></i></a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop
@section('page-js')
<script src="{{ asset('trader-assets/assets/vendor/js/vendor.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<!-- <script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script> -->

<!-- <script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script> -->

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/ui/ui-feather.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/ui/ui-feather.js')}}"></script>

<script src="{{ asset('trader-assets/assets/js/pamm/pamm_custom.js') }}" type="text/javascript"></script>
<script src="{{ asset('trader-assets/assets/flaviusmatis-simplePagination/jquery.simplePagination.js') }}" type="text/javascript"></script>


<script>
    $(document).ready(function() {
        function setCookie(key, value, expiry) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        }

        function getCookie(key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }

        function eraseCookie(key) {
            var keyValue = getCookie(key);
            setCookie(key, keyValue, '-1');
        }

        $('.grid_on').click(function() {
            setCookie('grid_list', 1, '365');
            $(this).addClass('active');
            $('.list_on').removeClass('active');
        });

        // $('.list_on').click(function() {
        //     setCookie('grid_list', 1, '365');
        //     $(this).addClass('active');
        //     $('.grid_on').removeClass('active');
        // });

        if (getCookie('grid_list') == 1) {
            if ($(window).width() < 991) {
                $('.grid_on').click();
                $('.grid_on').hide();
                $('.list_on').hide();
            } else {
                $('.grid_on').click();
            }
        } else {
            if ($(window).width() < 991) {
                $('.grid_on').click();
                $('.grid_on').hide();
                $('.list_on').hide();
            } else {
                $('.list_on').click();
            }
        }

    });


    //<-------------------pamm profile js start------------------------------->
    $(document).ready(function() {
        load_pamm_data();
    });
    var getChartIndex = 0;

    function load_pamm_data() {
        $(".fx-tbl-rows-loading").show();
        $(".fx-tbl-rows-loading").html('<button class="btn btn-primary btn-rounded btn-sm btn-outline-danger text-capitalize" type="button" disabled> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</button>');
        $(".fx-tbl-rows-loading").css('height', $("#pamm_result").height());

        var formData = $("#filter_form").serialize();

        $.ajax({
            url: "/user/user-pamm/user-pamm-profile-list-process",
            type: 'GET',
            data: formData,
            dataType: 'JSON',
            success: function(result) {
                $("#pamm_result").html("");

                $("#total-master-shown").text(result.data.length);
                if (result.data.length > 0) {
                    $("#static-row").find(".main-chart").attr("id", `card-border-chart-0`);
                    $("#static-row").find(".show-chart").attr("id", `chart-0`);
                    $("#static-row").find(".profile-overview-link").attr("id", `chart-0-btn`);
                    $.each(result.data, function(k, v) {

                        let row_el = $("#static-row").find(".static-row-content").clone();
                        $("#static-row").find(".main-chart").attr("id", `card-border-chart-${k+1}`);
                        $("#static-row").find(".show-chart").attr("id", `chart-${k+1}`);

                        $("#static-row").find(".profile-overview-link").attr("id", `chart-${k+1}-btn`);

                        row_el.find('.profile-overview-link').attr('href', "/user/user-pamm/user-pamm-copy-traders-details?ac=" + v.account);
                        row_el.find('.r-card__country').html('<img src="{{asset("comon-icon/flag/4_4")}}/' + v.flag + '.svg" class="r-table__about-image-country-view m-0" />');

                        row_el.find('.r-card__name').text(v.name);
                        row_el.find('.r-card__copiers-count').text(v.total_slaves);
                        // total copiers
                        let total_copiers = v.all_copiers;
                        row_el.find('.r-card__copiers-delta').text(total_copiers);
                        if (total_copiers > v.total_slaves) {
                            row_el.find('.r-card__copiers-delta').addClass('text-success');
                        } else {
                            row_el.find('.copier-up-down-img').html('<img src="{{asset("trader-assets/assets/img/pamm/arrow-down.png")}}" class="r-card__copiers-delta-image-file" />')
                            row_el.find('.r-card__copiers-delta').addClass('text-danger')
                        }

                        let total_pl = v.total_profit + v.total_lose;
                        if (total_pl > 0) {
                            row_el.find('.r-card__row-gain').addClass('_positive');
                            row_el.find('.r-card__row-gain').text('+' + total_pl.toFixed(2));

                        } else {
                            row_el.find('.r-card__row-gain').text(total_pl.toFixed(2));
                            row_el.find('.r-card__row-gain').addClass('text-danger');

                        }

                        if (total_pl > 100) {
                            row_el.find(".master-exp__text").text('High achiever');
                        }

                        //Range Slider
                        let profit_pie = (total_pl * Math.abs(v.total_profit)) / 100;
                        // let lose_pie = (total_pl * Math.abs(v.total_lose)) / 100;
                        let lose_pie = (100 - profit_pie);
                        row_el.find('.r-card__profit-head ._total_profit').text(v.total_profit.toFixed(2));
                        row_el.find('.r-card__profit-head ._total_lose').text(v.total_lose.toFixed(2));
                        row_el.find('.r-card__profit-body ._positive').css('width', profit_pie + "%");
                        row_el.find('.r-card__profit-body ._negative').css('width', lose_pie + "%");

                        row_el.find('.r-card__comission-value').text(v.share_profit + "%");
                        row_el.appendTo("#pamm_result");
                        var chartData = {
                            series: [{
                                name: 'Profit',
                                // data: v.chart_profit
                                data:[10,12,35,12]
                            }, {
                                name: 'Volume',
                                // data: v.chart_volume
                                data:[10.2,12.2,35,12.3]
                            }],
                            // 
                            categories: ['Jun','july','august']
                        };
                        var positiveVal = 0;
                        var negativeVal = 0;
                        for (var i = 0; i < v.chart_profit.length; i++) {
                            if (v.chart_profit[i] < 0)
                                negativeVal++;
                            else
                                positiveVal++;
                        }
                        // danger color: "#FE3649" ,"#D38123"
                        // warning color: "#D38123" ,"#B5A799" 
                        // normal color: "#3161EE","#8E9EC0"
                        // success color: "#01936C" ,"#9CAEB6"

                        // check copy or uncopy
                        var copy_uncopy_color = "#FE3649"; // uncopy color // danger
                        if (v.copy_uncopy > 0) {
                            copy_uncopy_color = "#01936C"; // copy color // success
                        }
                        if (positiveVal == 0 && negativeVal == 0) {
                            RenderPammChart(chartData, `chart-${k}`, "#FE3649", "#D38123", copy_uncopy_color); // danger
                        } else if (positiveVal == negativeVal) {
                            RenderPammChart(chartData, `chart-${k}`, "#D38123", "#B5A799", copy_uncopy_color); // normal
                        } else if (positiveVal > negativeVal) {
                            RenderPammChart(chartData, `chart-${k}`, "#01936C", "#9CAEB6", copy_uncopy_color); // success
                        } else {
                            RenderPammChart(chartData, `chart-${k}`, "#D38123", "#B5A799", copy_uncopy_color); //warning
                        }
                    });
                    $('.pagination').pagination({
                        items: result.total,
                        itemsOnPage: $("#rpp").val(),
                        currentPage: result.page,
                        cssStyle: 'sp-dark-theme',
                        onPageClick: function(pageNumber, event) {
                            $("#page_num").val(pageNumber);
                            load_pamm_data();
                        }
                    });

                    $(".fx-tbl-rows-loading").hide();
                } else {
                    $(".fx-tbl-rows-loading").text("No Data Found");
                }
            }

        })
    }

    $(".filter_fields").on('change', function() {
        load_pamm_data();
    });

    $(".text-filter").on('keyup', function() {
        load_pamm_data();
    });

    $('.rating__search-button').click(function() {
        if ($(this).data('active') == 0) {
            $(this).data('active', 1);
            $(".rating__search-input-area").show();
        } else {
            $(this).data('active', 0);
            $(".rating__search-input-area").hide();
        }
    });

    $(".filter-reset").click(function() {
        $("#filter_form").trigger('reset');
        load_pamm_data();
    });

    grid_on();

    function grid_on() {
        $(".sk-custom-col").addClass("col-sm-12 col-md-6 col-lg-3");
        // $(".r-card__head").removeClass("sk-r_card_inner");
        $(".sk-d-block").removeClass("sk-d-none");
        $(".r-cards").removeClass("sk-r-cards");
        $(".r-card").removeClass("sk-r-card");
        $(".r-card__head").removeClass("sk-r-card__head");
        $(".r-card__head").removeClass("ps-0");
        $(".r-card__inner").removeClass("sk-r_card_inner");
        $(".r-card__body").removeClass("sk-r-card__body");
        $(".r-card__info").removeClass("sk-r-card__info");
        $(".r-card__gain-value").removeClass("sk-r-card__gain-value");
        $(".sk-custom-col").removeClass("col-lg-3");
        $(".sk-custom-col").addClass("col-sm-12 col-md-6 col-lg-3");
        $(".r-card__footer").removeClass("sk-r-card-footer");
        $(".sk-custom-copi").removeClass("sk-r-card-copi");
        $(".sk-custom-copi").addClass("r-card-copi");
        // $(".tab-pane").removeClass("card");
        // $(".tab-pane .row").removeClass("card-body");
        $(".r-card__head").removeClass("sk-r_card_inner");
        $(".sk-custom-lebel").removeClass("d-block");
        $(".sk-custom-lebel").hide();;
    }
</script>

<!--pamm profile chart js and render js-->
<script src=" https://cdn.jsdelivr.net/npm/apexcharts@3.42.0/dist/apexcharts.min.js "></script>
<!-- END: Page JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    });
</script>


<script>
    /***
     ****************************************************************
     *
     * show the pamm chart 
     *
     ***************************************************************
     */

    function RenderPammChart(data, _chart, wavecolor1 = "#84D0FF", wavecolor2 = "#00E396", copy_uncopy_color) {
        var options = {

            series: data.series,
            chart: {
                height: "auto",
                type: "area",
                sparkline: {
                    enabled: true,
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: "smooth",
                width: 2,
                lineColor: {
                    // Set the stroke line color as a gradient
                    gradient: {
                        shade: "light",
                        type: "vertical",
                        shadeIntensity: 0.5,
                        gradientToColors: [wavecolor1],
                        inverseColors: true,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 80, 100],
                    },
                },
            },
            fill: {
                // Set the fill for the area under the line
                gradient: {
                    shade: "light",
                    type: "vertical",
                    shadeIntensity: 0.5,
                    gradientToColors: [wavecolor1, wavecolor2],
                    inverseColors: true,
                    opacityFrom: 0.5,
                    opacityTo: 0,
                    stops: [0, 80, 100],
                },
            },
            xaxis: {
                // type: 'datetime',
                categories: data.categories
            },
            yaxis: [

                {
                    title: {
                        text: 'Profit',
                    },

                    show: false,
                },
                {
                    title: {
                        text: 'Volume',
                    },
                    show: false,
                },
            ],
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
            colors: [wavecolor1, wavecolor2],
        };
        // change chart border and statistical border color from the copy and uncopy
        $(`#card-border-${_chart},#${_chart}-btn`).css({
            border: `1px solid ${copy_uncopy_color}`
        });
        var chart = new ApexCharts(document.querySelector(`#${_chart}`), options);
        chart.render();
    }
</script>
@stop