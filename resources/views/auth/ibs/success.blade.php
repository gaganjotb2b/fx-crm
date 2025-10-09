<!--
=========================================================
* Soft UI Dashboard PRO - v1.0.8
=========================================================

* Product Page:  https://www.creative-tim.com/product/soft-ui-dashboard-pro 
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('trader-assets/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('trader-assets/assets/img/favicon.png') }}">

    <title>{{ strtoupper(config('app.name')) }} - IB Registration Success </title>

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{asset('trader-assets/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{asset('trader-assets/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{asset('trader-assets/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8')}}" rel="stylesheet" />
</head>

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">

      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <h3 >Registration Success</h3>
                <p><span class="text-success font-weight-bold">Congratulations.... you successfully registrered as an IB. </span>We send an email, please check your email. and click the given button to activate your account. If you cannot found any email from system. please contact with your desk.</p>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
                <img src="{{asset('trader-assets/assets/img/shapes/pattern-lines.svg')}}" alt="pattern-lines" class="position-absolute opacity-4 start-0">
                <div class="position-relative">
                  <img class="max-width-500 w-100 position-relative z-index-2" src="{{asset('trader-assets/assets/img/illustrations/danger-chat-ill.png')}}" alt="chart-ill">
                </div>
                <h4 class="mt-5 text-white font-weight-bolder">"Attention is the new Regiseter user"</h4>
                <p class="text-white">If you need any help please contact with help desk. They are ready for you to make your life easy.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="{{asset('trader-assets/assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('trader-assets/assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <!-- Kanban scripts -->
  <script src="{{asset('trader-assets/assets/js/plugins/dragula/dragula.min.js')}}"></script>
  <script src="{{asset('trader-assets/assets/js/plugins/jkanban/jkanban.js')}}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('trader-assets/assets/js/soft-ui-dashboard.min.js?v=1.0.8')}}"></script>
</body>

</html>