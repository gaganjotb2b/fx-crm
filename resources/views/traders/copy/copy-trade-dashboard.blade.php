@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title','Copy Trade Dashboard')
@section('page-css')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/copy/app.css') }}" /> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/copy/user-pumm-profile.css') }}" />
<style>
    .filter-view-button {
        margin: 0 10px;
    }

    .filter-view-button.cards.active.fx-filter-vm {
        margin: 0;
    }
</style>
@stop
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pamm</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Pamm</li>
    </ol>
    <h6 class="font-weight-bolder mb-0"></h6>
</nav>
@stop
@section('content')
<div class="slim-mainpanel">
    <div class="row row-xs mg-t-10">
        <div class="fx-upumm-con">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="fx-section-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="rating__head">{{ __('page.master_rating') }}</h3>
                                <br />
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-12 col-lg-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="filter-gainer">{{ __('page.Whom to show first') }}</label>
                                            <select class="form-control multisteps-form__input choice-material" id="filter-gainer">
                                                <option value="Top gainers">{{ __('page.Top gainers') }}</option>
                                                <option value="Most popular">{{ __('page.Most popular') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group ">
                                            <label for="filter-duration">{{ __('page.Filter by duration') }}</label>
                                            <select class="form-control multisteps-form__input choice-colors" id="filter-duration">
                                                <option value="All time">{{ __('page.All time') }}</option>
                                                <option value="2 weeks">2 weeks</option>
                                                <option value="1 month">1 month</option>
                                                <option value="3 months">3 months</option>
                                                <option value="6 months">6 months</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 filter-margin ">
                                <div class="row">
                                    <div class="col-6 filter-margin">
                                        <div class="form-group">
                                            <div class="filter-more active fx-filter-d">
                                                <div class="filter-d__more-icon">
                                                    <img src="{{asset('/trader-assets/assets/img/copy/load.png')}}" />
                                                </div>
                                                <div class="filter-more-text">{{ __('page.Filters') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end">
                                        <div class="filter-change-view fx-view-btns">
                                            <div class="search-box-main">
                                                <div class="rating__search">
                                                    <div class="rating__search-button target">
                                                        <img src="{{asset('/trader-assets/assets/img/copy/search.png')}}" />
                                                    </div>
                                                    <div class="rating__search-input-area">
                                                        <input type="text" placeholder="Nickname" class="rating__search-input" />
                                                        <!---->
                                                    </div>
                                                </div>
                                                <div class="rating__search-input-area">
                                                    <input type="text" placeholder="Nickname" id="inputfild" class="rating__search-input" />
                                                </div>
                                            </div>
                                            <button type="button" onclick="list_on()" class="filter-view-button table" id="">
                                                <div class="filter-view-button-pin"></div>
                                                <div class="filter-view-button-pin"></div>
                                                <div class="filter-view-button-pin"></div>
                                            </button>
                                            <button type="button" onclick="grid_on()" id="" class="filter-view-button cards active fx-filter-vm">
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
                    <div class="rating_top"></div>
                </div>
                <div class="col-sm-12 col-lg-12 fade-section">
                    <div class="fx-section-body">
                        <div class="row">
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('page.Minimum investment') }}</label>
                                    <input type="text" class="form-control" placeholder="$ 25 or more" />
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group  ">
                                    <label>{{ __('page.Minimum expertise') }}</label>
                                    <select class="form-control multisteps-form__input btExport" data-plugin-options='{ "placeholder": "Select a State", "allowClear": true }'>
                                        <option value="Legend" style="background: url('{{asset('/trader-assets/assets/img/copy/user_image.png')}}')">
                                            {{ __('page.Legends') }}
                                        </option>
                                        <option value="2 weeks">2 weeks</option>
                                        <option value="1 month">1 month</option>
                                        <option value="3 months">3 months</option>
                                        <option value="6 months">6 months</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4" style="margin-top: 33px">
                                <div class="form-group trial-form-group" style="margin-left: 20px">
                                    <input class="form-check-input" type="checkbox" id="autoSizingCheck" />
                                    <label class="form-check-label" for="autoSizingCheck">
                                        {{ __('page.Free 7-day trial') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-12 fade-section" style="margin-top: 3px">
                    <div class="fx-section-body">
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6 no-gutters">
                                <div class="row">
                                    <div class="col-6 col-lg-9 master-trader-text" style="text-align: right">
                                        <span>(<span id="total-master-shown">2</span>) MASTER
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
                    <div class="rating_top"></div>
                </div>
                <div class="col-sm-12">
                    <br />
                    <div id="tab2" class="tab-pane active card">
                        <div class="row card-body">
                            {{-- single copy item  --}}
                            <div class="col-lg-12 d-block sk-custom-lebel">
                                <div class="rating__list _cards">
                                    <div class="r-cards sk-r-cards">
                                        <a href="{{url('/user/copy-trade/copy-trade-overview')}}" class="r-cards__card">
                                            <div class="r-card sk-r-card">
                                                <div class="r-card__head sk-r-card__head ps-0" style="opacity: 0; visibility:hidden">
                                                    <div class="r-card__view">
                                                        <div class="r-card__avatar" style="
                                        background-image: url({{asset('/trader-assets/assets/img/copy/user_image.png')}});
                                      "></div>
                                                        <div class="r-card__country">
                                                            <img src="{{asset('/trader-assets/assets/img/copy/bd.svg')}}" class="r-table__about-image-country-view" />
                                                        </div>
                                                    </div>
                                                    <div class="r-card__about">
                                                        <div class="r-card__name">Arif Ahmed</div>
                                                        <div class="r-card__master-exp">
                                                            <div class="master-exp master-exp _r-cards">
                                                                <div class="master-exp__nav">
                                                                    <div class="master-exp__text">
                                                                        <i class="fas fa-star-of-life star-achive"></i> &nbsp;High achiever
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="r-card__inner sk-r_card_inner">
                                                    <div class="r-table__section _risk">
                                                        <div class="r-table__section-name">Risk score</div>
                                                        <div class="r-table__section-desc">All time</div>
                                                    </div>
                                                    <div class="r-card__body sk-r-card__body">
                                                        <div class="r-table__section-name">Gain</div>
                                                        <div class="r-table__section-desc">All time</div>
                                                    </div>

                                                    <div class="r-card-copi sk-custom-copi sk-r-card-copi">
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
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- single copy item  --}}
                            <div class="col-lg-12 sk-custom-col">
                                <div class="rating__list _cards">
                                    <div class="r-cards sk-r-cards">
                                        <a href="{{url('/user/copy-trade/copy-trade-overview')}}" class="r-cards__card">
                                            <div class="r-card sk-r-card">
                                                <div class="r-card__head sk-r-card__head ps-0">
                                                    <div class="r-card__view">
                                                        <div class="r-card__avatar" style="
                                        background-image: url({{asset('/trader-assets/assets/img/copy/user_image.png')}});
                                      "></div>
                                                        <div class="r-card__country">
                                                            <img src="{{asset('/trader-assets/assets/img/copy/bd.svg')}}" class="r-table__about-image-country-view" />
                                                        </div>
                                                    </div>
                                                    <div class="r-card__about">
                                                        <div class="r-card__name">Arif Ahmed</div>
                                                        <div class="r-card__master-exp">
                                                            <div class="master-exp master-exp _r-cards">
                                                                <div class="master-exp__nav">
                                                                    <div class="master-exp__text">
                                                                        <i class="fas fa-star-of-life star-achive"></i> &nbsp;High achiever
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="r-card__inner sk-r_card_inner">
                                                    <div class="r-card__header">
                                                        <div class="r-card__param _no-margin sk-d-block sk-d-none">
                                                            Risk score
                                                        </div>
                                                        <div class="risk-score risk-score--size-lg risk-score--value-1">
                                                            <div class="risk-score__value">1</div>
                                                        </div>
                                                    </div>
                                                    <div class="r-card__body sk-r-card__body">
                                                        <div class="r-card__info sk-r-card__info">
                                                            <div class="r-card__gain">
                                                                <div class="r-card__param sk-d-block sk-d-none">Gain</div>
                                                                <div class="r-card__gain-value sk-r-card__gain-value _positive">
                                                                    0
                                                                </div>
                                                            </div>
                                                            <div class="r-card__profit-wrapper">
                                                                <div class="r-card__param sk-d-block sk-d-none">
                                                                    profit and loss
                                                                </div>
                                                                <div class="r-card__profit">
                                                                    <div class="r-card__profit-head">
                                                                        <div class="r-card__profit-title"></div>
                                                                        <div class="r-card__profit-title">
                                                                            0
                                                                        </div>
                                                                    </div>
                                                                    <div class="r-card__profit-body">
                                                                        <div class="r-card__profit-body-line _positive" style="width: 0%"></div>
                                                                        <div class="r-card__profit-body-line _negative" style="width: 0%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="r-card-copi sk-custom-copi sk-r-card-copi">
                                                        <div class="r-card__copiers">
                                                            <div class="r-card__param sk-d-block sk-d-none">copiers</div>
                                                            <div class="r-card__copiers-wrap">
                                                                <div class="r-card__copiers-count">
                                                                    0
                                                                </div>
                                                                <div class="r-card__copiers-delta-image _positive">
                                                                    <img src="{{asset('/trader-assets/assets/img/copy/up.png')}}" class="r-card__copiers-delta-image-file" />
                                                                </div>
                                                                <div class="r-card__copiers-delta _positive">
                                                                    5
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="r-card__footer sk-r-card-footer">
                                                        <div class="r-card__param _no-margin sk-d-block sk-d-none">
                                                            Commission
                                                        </div>
                                                        <div class="r-card__footer-value">10%</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {{-- single copy item  --}}
                            <div class="col-lg-12 sk-custom-col">
                                <div class="rating__list _cards">
                                    <div class="r-cards sk-r-cards">
                                        <a href="{{url('/user/copy-trade/copy-trade-overview')}}" target="_blank" class="r-cards__card">
                                            <div class="r-card sk-r-card">
                                                <div class="r-card__head sk-r-card__head ps-0">
                                                    <div class="r-card__view">
                                                        <div class="r-card__avatar" style="
                                        background-image: url({{asset('/trader-assets/assets/img/copy/user_image.png')}});
                                      "></div>
                                                        <div class="r-card__country">
                                                            <img src="{{asset('/trader-assets/assets/img/copy/bd.svg')}}" class="r-table__about-image-country-view" />
                                                        </div>
                                                    </div>
                                                    <div class="r-card__about">
                                                        <div class="r-card__name">Arif Ahmed</div>
                                                        <div class="r-card__master-exp">
                                                            <div class="master-exp master-exp _r-cards">
                                                                <div class="master-exp__nav">
                                                                    <div class="master-exp__text">
                                                                        <i class="fas fa-star-of-life star-achive"></i> &nbsp;High achiever
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="r-card__inner sk-r_card_inner">
                                                    <div class="r-card__header">
                                                        <div class="r-card__param _no-margin sk-d-block sk-d-none">
                                                            Risk score
                                                        </div>
                                                        <div class="risk-score risk-score--size-lg risk-score--value-1">
                                                            <div class="risk-score__value">1</div>
                                                        </div>
                                                    </div>
                                                    <div class="r-card__body sk-r-card__body">
                                                        <div class="r-card__info sk-r-card__info">
                                                            <div class="r-card__gain">
                                                                <div class="r-card__param sk-d-block sk-d-none">Gain</div>
                                                                <div class="r-card__gain-value sk-r-card__gain-value _positive">
                                                                    0
                                                                </div>
                                                            </div>
                                                            <div class="r-card__profit-wrapper">
                                                                <div class="r-card__param sk-d-block sk-d-none">
                                                                    profit and loss
                                                                </div>
                                                                <div class="r-card__profit">
                                                                    <div class="r-card__profit-head">
                                                                        <div class="r-card__profit-title"></div>
                                                                        <div class="r-card__profit-title">
                                                                            0
                                                                        </div>
                                                                    </div>
                                                                    <div class="r-card__profit-body">
                                                                        <div class="r-card__profit-body-line _positive" style="width: 0%"></div>
                                                                        <div class="r-card__profit-body-line _negative" style="width: 0%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="r-card-copi sk-custom-copi sk-r-card-copi">
                                                        <div class="r-card__copiers">
                                                            <div class="r-card__param sk-d-block sk-d-none">copiers</div>
                                                            <div class="r-card__copiers-wrap">
                                                                <div class="r-card__copiers-count">
                                                                    0
                                                                </div>
                                                                <div class="r-card__copiers-delta-image _positive">
                                                                    <img src="{{asset('/trader-assets/assets/img/copy/up.png')}}" class="r-card__copiers-delta-image-file" />
                                                                </div>
                                                                <div class="r-card__copiers-delta _positive">
                                                                    5
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="r-card__footer sk-r-card-footer">
                                                        <div class="r-card__param _no-margin sk-d-block sk-d-none">
                                                            Commission
                                                        </div>
                                                        <div class="r-card__footer-value">10%</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

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
                                <a class="fx-master-page fx-angle-disabled" href="javascript:void(0);" data-pageid="1" data-max="1"><i class="fa fa-chevron-left"></i></a><a href="javascript:void(0);" data-pageid="1" data-max="0" class="active fx-master-page">1</a><a href="javascript:void(0);" class="fx-angle-disabled fx-master-page" data-pageid="1" data-max="1"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>
@stop
@section('page-js')
<script src="{{asset('trader-assets/assets/js/copy/pamm_custom.js')}}"></script>
<script>
    function list_on() {
        $(".sk-d-block").addClass("sk-d-none");
        $(".r-cards").addClass("sk-r-cards");
        $(".r-card").addClass("sk-r-card");
        $(".r-card__head").addClass("sk-r-card__head");
        $(".r-card__head").addClass("ps-0");
        $(".r-card__inner").addClass("sk-r_card_inner");
        $(".r-card__body").addClass("sk-r-card__body");
        $(".r-card__info").addClass("sk-r-card__info");
        $(".r-card__gain-value").addClass("sk-r-card__gain-value");
        $(".r-card__footer").addClass("sk-r-card-footer");
        $(".sk-custom-col").addClass("col-lg-12");
        $(".tab-pane").addClass("card");
        $(".tab-pane .row").addClass("card-body");
        $(".rating__list").addClass("px-3");
        $(".sk-custom-col").removeClass("col-sm-12 col-md-6 col-lg-4");
        $(".sk-custom-copi").removeClass("r-card-copi");
        $(".sk-custom-lebel").show();
    }

    function grid_on() {
        $(".r-card__head").removeClass("sk-r_card_inner");
        $(".sk-d-block").removeClass("sk-d-none");
        $(".r-cards").removeClass("sk-r-cards");
        $(".r-card").removeClass("sk-r-card");
        $(".r-card__head").removeClass("sk-r-card__head");
        $(".r-card__head").removeClass("ps-0");
        $(".r-card__inner").removeClass("sk-r_card_inner");
        $(".r-card__body").removeClass("sk-r-card__body");
        $(".r-card__info").removeClass("sk-r-card__info");
        $(".r-card__gain-value").removeClass("sk-r-card__gain-value");
        $(".sk-custom-col").removeClass("col-lg-12");
        $(".sk-custom-col").addClass("col-sm-12 col-md-6 col-lg-4");
        $(".r-card__footer").removeClass("sk-r-card-footer");
        $(".sk-custom-copi").removeClass("sk-r-card-copi");
        $(".sk-custom-copi").addClass("r-card-copi");
        $(".tab-pane").removeClass("card");
        $(".tab-pane .row").removeClass("card-body");
        $(".r-card__head").removeClass("sk-r_card_inner");
        $(".sk-custom-lebel").removeClass("d-block");
        $(".sk-custom-lebel").hide();;
    }
</script>

@stop