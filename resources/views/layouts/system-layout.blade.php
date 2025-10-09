<!DOCTYPE html>
<html class="loading {{ get_admin_theme() }}" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="{{ get_company_name() }} is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords"
        content="{{ get_company_name() }} is operated by {{ get_company_name() }} and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="{{ get_company_name() }}">
    <!-- csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title id="minutes">{{ strtoupper(config('app.name')) }} - @yield('title') </title>
    <!-- style sheet -->
    <link rel="apple-touch-icon" href="{{ get_favicon_icon() }}">
    @php $themeColor = get_theme_colors_forAll('admin_theme') @endphp
    <style>
        :root {
            --custom-primary: <?=$themeColor->primary_color ?? '#7367f0' ?>;
            --custom-form-color: <?=$themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?=$themeColor->body_color ?? '#67748e' ?>;
        }
    </style>
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_favicon_icon() }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css') }}">
    @yield('vendor-css')
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/ui-feather.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/charts/chart-apex.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/shepherd.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/shepherd.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tour.css') }}">
    @yield('page-css')
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/style.css') }}">
    <!-- END: Custom CSS-->
    <style>
        .main-menu .navbar-header .navbar-brand {
            margin-top: 0.35rem !important;
        }
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="">
    <span id="envSEssionTime" data-session="{{ config('lifetime') }}"></span>
    <!-- BEGIN: Header-->
    <nav
        class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav bookmark-icons">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Email"><i class="ficon"
                                data-feather="mail"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat.html"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Chat"><i class="ficon"
                                data-feather="message-square"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Calendar"><i class="ficon"
                                data-feather="calendar"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Todo"><i class="ficon"
                                data-feather="check-square"></i></a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i
                                class="ficon text-warning" data-feather="star"></i></a>
                        <div class="bookmark-input search-input">
                            <div class="bookmark-input-icon"><i data-feather="search"></i></div>
                            <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0"
                                data-search="search">
                            <ul class="search-list search-list-bookmark"></ul>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item dropdown dropdown-language"><a class="nav-link dropdown-toggle"
                        id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"><i class="flag-icon flag-icon-us"></i><span
                            class="selected-language">English</span></a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag"><a
                            class="dropdown-item" href="#" data-language="en"><i
                                class="flag-icon flag-icon-us"></i> English</a><a class="dropdown-item"
                            href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i> French</a><a
                            class="dropdown-item" href="#" data-language="de"><i
                                class="flag-icon flag-icon-de"></i> German</a><a class="dropdown-item" href="#"
                            data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese</a></div>
                </li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                            data-feather="moon"></i></a></li>
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon"
                            data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Explore Vuexy..."
                            tabindex="-1" data-search="search">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-cart me-25"><a class="nav-link" href="#"
                        data-bs-toggle="dropdown"><i class="ficon" data-feather="shopping-cart"></i><span
                            class="badge rounded-pill bg-primary badge-up cart-item-count">6</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">My Cart</h4>
                                <div class="badge rounded-pill badge-light-primary">4 Items</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div class="list-item align-items-center"><img class="d-block rounded me-1"
                                    src="{{ asset('admin-assets/app-assets/images/pages/eCommerce/1.png') }}"
                                    alt="donuts" width="62">
                                <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove"
                                        data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> Apple watch 5</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$374.90</h5>
                                </div>
                            </div>
                            <div class="list-item align-items-center"><img class="d-block rounded me-1"
                                    src="{{ asset('admin-assets/app-assets/images/pages/eCommerce/7.png') }}"
                                    alt="donuts" width="62">
                                <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove"
                                        data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> Google Home Mini</a></h6><small
                                            class="cart-item-by">By Google</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="3">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$129.40</h5>
                                </div>
                            </div>
                            <div class="list-item align-items-center"><img class="d-block rounded me-1"
                                    src="{{ asset('admin-assets/app-assets/images/pages/eCommerce/2.png') }}"
                                    alt="donuts" width="62">
                                <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove"
                                        data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> iPhone 11 Pro</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="2">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$699.00</h5>
                                </div>
                            </div>
                            <div class="list-item align-items-center"><img class="d-block rounded me-1"
                                    src="{{ asset('admin-assets/app-assets/images/pages/eCommerce/3.png') }}"
                                    alt="donuts" width="62">
                                <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove"
                                        data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> iMac Pro</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$4,999.00</h5>
                                </div>
                            </div>
                            <div class="list-item align-items-center"><img class="d-block rounded me-1"
                                    src="{{ asset('admin-assets/app-assets/images/pages/eCommerce/5.png') }}"
                                    alt="donuts" width="62">
                                <div class="list-item-body flex-grow-1"><i class="ficon cart-item-remove"
                                        data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> MacBook Pro</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$2,999.00</h5>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-bolder mb-0">Total:</h6>
                                <h6 class="text-primary fw-bolder mb-0">$10,999.00</h6>
                            </div><a class="btn btn-primary w-100" href="app-ecommerce-checkout.html">Checkout</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#"
                        data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span
                            class="badge rounded-pill bg-danger badge-up">5</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                                <div class="badge rounded-pill badge-light-primary">6 New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list"><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar"><img src="{{ asset(avatar()) }}" alt="avatar"
                                                width="32" height="32"></div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Congratulation Sam
                                                ðŸŽ‰</span>winner!</p><small class="notification-text"> Won the monthly
                                            best seller badge.</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar"><img
                                                src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-3.jpg') }}"
                                                alt="avatar" width="32" height="32"></div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">New
                                                message</span>&nbsp;received</p><small class="notification-text"> You
                                            have 10 unread messages</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content">MD</div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Revised Order
                                                ðŸ‘‹</span>&nbsp;checkout</p><small class="notification-text"> MD Inc.
                                            order updated</small>
                                    </div>
                                </div>
                            </a>
                            <div class="list-item d-flex align-items-center">
                                <h6 class="fw-bolder me-auto mb-0">System Notifications</h6>
                                <div class="form-check form-check-primary form-switch">
                                    <input class="form-check-input" id="systemNotification" type="checkbox"
                                        checked="">
                                    <label class="form-check-label" for="systemNotification"></label>
                                </div>
                            </div><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Server
                                                down</span>&nbsp;registered</p><small class="notification-text"> USA
                                            Server is down due to high CPU usage</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-success">
                                            <div class="avatar-content"><i class="avatar-icon"
                                                    data-feather="check"></i></div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Sales
                                                report</span>&nbsp;generated</p><small class="notification-text"> Last
                                            month sales report generated</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon"
                                                    data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">High memory</span>&nbsp;usage
                                        </p><small class="notification-text"> BLR Server using high memory</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all
                                notifications</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">IT Corner</span><span
                                class="user-status">System</span></div><span class="avatar"><img class="round"
                                src="{{ asset(avatar()) }}" alt="avatar" height="40" width="40"><span
                                class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user"><a
                            class="dropdown-item" href="page-profile.html"><i class="me-50"
                                data-feather="user"></i> Profile</a><a class="dropdown-item" href="app-email.html"><i
                                class="me-50" data-feather="mail"></i> Inbox</a><a class="dropdown-item"
                            href="app-todo.html"><i class="me-50" data-feather="check-square"></i> Task</a><a
                            class="dropdown-item" href="app-chat.html"><i class="me-50"
                                data-feather="message-square"></i> Chats</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="page-account-settings-account.html"><i class="me-50"
                                data-feather="settings"></i> Settings</a>
                        <a class="dropdown-item" href="page-pricing.html"><i class="me-50"
                                data-feather="credit-card"></i> Pricing</a>
                        <a class="dropdown-item" href="page-faq.html"><i class="me-50"
                                data-feather="help-circle"></i> FAQ</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="me-50" data-feather="power"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist d-none">
        <li class="d-flex align-items-center"><a href="#">
                <h6 class="section-label mt-75 mb-0">Files</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/xls.png') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small
                            class="text-muted">Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/jpg.png') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/pdf.png') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                            Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/doc.png') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="#">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-8.jpg') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-1.jpg') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-14.jpg') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing
                            Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-6.jpg') }}"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a
                class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="me-75"
                        data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto" style="width:76%">
                    <a class="navbar-brand" href="{{ asset('html/ltr/vertical-menu-template/index.html') }}"><span
                            class="brand-logo">
                            <img class="img img-fluid" src="{{ get_admin_logo() }}" alt="{{ config('app.name') }}">
                            <!-- <h2 class="brand-text">{{ strtoupper(config('app.name')) }}</h2> -->
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0"
                        data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4"
                            data-feather="x"></i><i
                            class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary"
                            data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <!-- system dashboard -->
                <li class="{{ Request::is('system/dashboard') ? 'active' : '' }}" id="mainMenuLi">
                    <a class="d-flex align-items-center" href="{{ route('system.dashboard') }}">
                        <i data-feather="home"></i>
                        <span class="menu-item text-truncate" data-i18n="Configuration">Dashaboard</span>
                    </a>
                </li>
                <!-- Banks -->
                <li class=" nav-item {{ Request::is('/system/banks*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='box'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">Banks</span>
                    </a>
                    <!-- activities log reports -->
                    <ul class="menu-content">
                        <li class="{{ Request::is('/system/banks/online-bank-list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('systems.online-bank-list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">Online Bank List</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- crypto currency -->
                <li class=" nav-item {{ Request::is('/system/crypto/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='box'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">Crypto</span>
                    </a>
                    <!-- activities log reports -->
                    <ul class="menu-content">
                        <li class="{{ Request::is('/system/crypto/crypto-currency') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.crypto-currency') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">Crypto currency</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('/system/m2pay-config') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.m2pay-config') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">M2Pay Config</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- configuration -->
                <li class=" nav-item {{ Request::is('system/configurations/*') ? 'open' : '' }}"
                    id="left_setting_menu">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="settings"></i>
                        <span class="menu-title text-truncate" data-i18n="Configurations">Configurations</span>
                    </a>
                    <ul class="menu-content">
                        <!-- api configuration -->
                        <li class="{{ Request::is('system/configurations/api_configuration') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="api_configuration"
                                href="{{ route('system.configurations.api_configuration') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="API Configuration">API
                                    Configuration</span>
                            </a>
                        </li>
                        <li
                            class="{{ Request::is('/system/configurations/ib-commission-structure-replace') ? 'active' : '' }}">
                            <a class="d-flex align-items-center"
                                href="{{ route('system.ib-commission-structure-replace') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Commission Replace">IB Commision
                                    Structure Replace</span>
                            </a>
                        </li>
                        <!-- company setup -->
                        <li class="{{ Request::is('system/configurations/company_setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="company_setup"
                                href="{{ route('system.configurations.company_setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Company Setup">Company Setup</span>
                            </a>
                        </li>
                        <!-- finance -->
                        <li class="{{ Request::is('system/configurations/finance_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="finance_setting"
                                href="{{ route('system.configurations.finance_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Finance Setting">Finance
                                    Setting</span>
                            </a>
                        </li>
                        <!-- smtp setup -->
                        <li class="{{ Request::is('system/configurations/smtp_setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="smtp_setup"
                                href="{{ route('system.configurations.smtp_setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="SMTP Setup">SMTP Setup</span>
                            </a>
                        </li>
                        <!-- footer link setup -->
                        <li class="{{ Request::is('system/configurations/footer_link') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="footer_link"
                                href="{{ route('system.configurations.footer_link') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Footer Links">Footer Links</span>
                            </a>
                        </li>
                        <!-- software -->
                        <li class="{{ Request::is('system/configurations/software_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="software_setting"
                                href="{{ route('system.configurations.software_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Software Setting">Software
                                    Setting</span>
                            </a>
                        </li>
                        <!-- theme setup -->
                        <li class="{{ Request::is('system/configurations/theme_setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="theme_setup"
                                href="{{ route('system.configurations.theme_setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">Theme Setup</span>
                            </a>
                        </li>
                        <!-- pamm settings -->
                        <li class="{{ Request::is('system/pamm-setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="" href="{{ route('system.pamm') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">PAMM Settings</span>
                            </a>
                        </li>
                        <!-- company links -->
                        <li class="{{ Request::is('system/company-links') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id=""
                                href="{{ route('system.company_links') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">Company Links</span>
                            </a>
                        </li>
                        <!-- admin settings -->
                        <li class="{{ Request::is('system/admin-settings') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id=""
                                href="{{ route('system.admin-settings.by-system-admin') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">Admin rights</span>
                            </a>
                        </li>
                        <!-- trading account configurations-->
                        <li class="{{ Request::is('system/admin-account/configuration') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id=""
                                href="{{ route('system.admin-account.configuration') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">Account
                                    Configuration</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- migrtion -->
                <li class=" nav-item {{ Request::is('system/migration/user-migration/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='monitor'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">Migration</span>
                    </a>
                    <!-- user migration -->
                    <ul class="menu-content">
                        <li class="{{ Request::is('system/migration/user-migration/view') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.user-migration-view') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="User Migration">User Migration</span>
                            </a>
                        </li>
                        <!-- name email migration -->
                        <li class="{{ Request::is('system/migration/user-migration/name-email') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.name-email-migration') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="User Migration">Name and email
                                    migration</span>
                            </a>
                        </li>
                        <!-- manager asigne migration -->
                        <li
                            class="{{ Request::is('system/migration/user-migration/manager-asigne') ? 'active' : '' }}">
                            <a class="d-flex align-items-center"
                                href="{{ route('system.manager-asigne-migration') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="User Migration">Manager asigne
                                    migration</span>
                            </a>
                        </li>
                        <!-- deposit migration -->
                        <li class="{{ Request::is('system/migration/deposit-migration/view') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.deposit-migration-view') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Deposit Migration">Deposit
                                    Migration</span>
                            </a>
                        </li>
                        <!-- withdraw migration -->
                        <li class="{{ Request::is('system/migration/withdraw-migration/view') ? 'active' : '' }}">
                            <a class="d-flex align-items-center"
                                href="{{ route('system.withdraw-migration-view') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Withdraw Migration">Withdraw
                                    Migration</span>
                            </a>
                        </li>
                        <!-- ib to sub ib -->
                        <li class="{{ Request::is('system/migration/user-migration/ib-reference') ? 'active' : '' }}">
                            <a class="d-flex align-items-center"
                                href="{{ route('system.migration.ib-reference') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">IB to Sub IB</span>
                            </a>
                        </li>
                        <!-- ib to trader -->
                        <li
                            class="{{ Request::is('system/migration/user-migration/clients-reference') ? 'active' : '' }}">
                            <a class="d-flex align-items-center"
                                href="{{ route('system.migration.clients-reference') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">IB to Clients</span>
                            </a>
                        </li>
                        <!-- direct ib convert -->
                        <li class="{{ Request::is('system/migration/user-migration/convert-ib') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.migration.convert-ib') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">Trader to IB(Combined
                                    only)</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- choose mailer -->
                <li class=" nav-item {{ Request::is('/system/mailer/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='mail'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">Mailer</span>
                    </a>
                    <!-- activities log reports -->
                    <ul class="menu-content">
                        <li class="{{ Request::is('/system/mailer/choose-template') ? 'active' : '' }}">
                            <a class="d-flex align-items-center"
                                href="{{ route('system.mailer.choose-template') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">Choose Mail
                                    Template</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- retports -->
                <li class=" nav-item {{ Request::is('system/report/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='sliders'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">Reports</span>
                    </a>
                    <!-- activities log reports -->
                    <ul class="menu-content">
                        <li class="{{ Request::is('system/reports/activity_log') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.reports.activity_log') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">Activity Log</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- system module -->
                <li class=" nav-item {{ Request::is('system/report/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='user-check'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">System Modules</span>
                    </a>
                    <!-- activities log reports -->
                    <ul class="menu-content">
                        <!-- trader sttings -->
                        <li class="{{ Request::is('system/trader-settings') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="theme_setup"
                                href="{{ route('system.trader-settings.by-system-admin') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">Trader Module</span>
                            </a>
                        </li>
                        <!-- ib settins -->
                        <li class="{{ Request::is('system/ib-settings') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="theme_setup"
                                href="{{ route('system.ib-settings.by-system-admin') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">IB Module</span>
                            </a>
                        </li>
                        <!-- admin module -->
                        <li class="{{ Request::is('system/system-module') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="theme_setup"
                                href="{{ route('system.system-modules') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Theme Setup">Admin Module</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Payments setting -->
                <li class="nav-item {{ Request::is('system/payments-settings/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" id="payments_setting" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-dollar-sign">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span class="menu-item text-truncate" data-i18n="Payments Settings">
                            Payments Settings
                        </span>
                    </a>
                    <!-- Payments Methods -->
                    <ul class="menu-content">
                        <!-- help2pay sttings -->
                        <li class="{{ Request::is('system/payments-settings/help2pay') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="payments_setting"
                                href="{{ route('system.help2pay-settings') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Help2pay</span>
                            </a>
                        </li>
                        <!-- praxis settins -->
                        <li class="{{ Request::is('system/payments-settings/praxis') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.praxis-settings') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Praxis</span>
                            </a>
                        </li>
                        <!-- B2B settins -->
                        <li class="{{ Request::is('system/payments-settings/b2b') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('system.b2b-settings') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">B2B</span>
                            </a>
                        </li>
                        <!-- Paypal Settings -->
                        <li class="">
                            <a class="d-flex align-items-center" href="">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Paypal</span>
                            </a>
                        </li>
                        <!-- Neteler Settings -->
                        <li class="">
                            <a class="d-flex align-items-center" href="">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Neteler</span>
                            </a>
                        </li>
                        <!-- GCash Settings -->
                        <li class="">
                            <a class="d-flex align-items-center" href="">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Gcash</span>
                            </a>
                        </li>
                        <!-- M2pay Settings -->
                        <li class="">
                            <a class="d-flex align-items-center" href="">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Match2pay</span>
                            </a>
                        </li>
                        <!-- Crypto Settings -->
                        <li class="">
                            <a class="d-flex align-items-center" href="">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Crypto</span>
                            </a>
                        </li>
                        <!-- Perfect Money Settings -->
                        <li class="">
                            <a class="d-flex align-items-center" href="">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Payments Settings">Perfect
                                    Money</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>
                <!-- mobile application -->
                <li class=" nav-item {{ Request::is('/system/mobile-application/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='phone'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">Mobile</span>
                    </a>
                    <!-- activities log reports -->
                    <ul class="menu-content">
                        <li class="{{ Request::is('/system/mobile-application/logo-controll') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('mobile.app.logo_controll') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">Mobile App
                                    Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    @yield('content')
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span
                class="float-md-start d-block d-md-inline-block mt-25">{{ get_copyright() }}</span><span
                class="float-md-end d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script>
    @yield('vendor-js')
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>
    @yield('page-vendor-js')
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('admin-assets/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/core/confirm-alert.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('admin-assets/app-assets/js/scripts/ui/ui-feather.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/js/scripts/table-color.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/lang-change.js') }}"></script>
    <!-- BEGIN: Page tour JS-->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/tether.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/shepherd.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/extensions/ext-component-tour.js') }}"></script>
    @yield('page-js')
    <!-- END: Page JS-->
    <!-- enter key handler -->
    <script src="{{ asset('common-js/enter-key-handler.js') }}"></script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
