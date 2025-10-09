@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Crypto Deposit Settings')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<style>
    .has-error .error-msg {
        position: absolute !important;
        bottom: -20px !important;
    }

    .add-crypto {
        padding-bottom: 10px;
    }

    @media screen and (max-width: 767px) {
        .description-section {
            display: none;
        }
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('page.Add_Crypto_Address') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin-management.home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('category.Settings') }} </a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('page.Add_Crypto_Address') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Note cards -->
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 description-section">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>{{ __('page.note') }}</b><br></h4>
                            <p>{{ __('page.i_note') }}</p>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                <p>{{ __('page.i_note1') }}</p>
                            </div>
                            <div class="border-start-3 border-start-success p-1 mb-1 bg-light-info">
                                <p>{{ __('page.i_note2') }}</p>
                            </div>
                            <div class="border-start-3 border-start-info p-1 mb-1 bg-light-info">
                                <p>{{ __('page.i_note3') }}</p>
                            </div>
                            <div class="border-start-3 border-start-danger p-1 mb-1 bg-light-info">
                                <p>{{ __('page.i_note4') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->hasDirectPermission('create add crypto address'))
                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>{{ __('page.Add_Crypto_Address') }}</b></h4>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.add_crypto_address') }}" method="post" id="form-crypto-address">
                                @csrf
                                <div class="mb-1 row add-crypto">
                                    <label for="crypto-currency" class="col-sm-3 col-form-label ">Crypto Currency<span class="text-danger">&#9734;</span></label>
                                    <div class="col-sm-9 has-error">
                                        <select class="form-control select2" name="block_chain" id="crypto-currency">
                                            <option value="">Please select a crypto currency</option>
                                            <option value="USDT">USDT</option>
                                            <option value="BTC">BTC</option>
                                            <option value="ETH">ETH</option>
                                            <option value="TRON">TRON</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-1 row add-crypto">
                                    <label for="block-chain" class="col-sm-3 col-form-label ">Blockchain <span class="text-danger">&#9734;</span></label>
                                    <div class="col-sm-9 has-error">
                                        <select class="form-control select2" name="instrument" id="block-chain">
                                            <option value="">Please select a blockchain</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-1 row add-crypto">
                                    <label for="name" class="col-sm-3 col-form-label">{{ __('page.address') }} <span class="text-danger">&#9734;</span></label>
                                    <div class="col-sm-9 has1-error">
                                        <input type="text" class="form-control" name="crypto_address" id="crypto_address" placeholder="Crypto Address" />
                                    </div>
                                </div>

                                <div class="mb-1 row mt-2">
                                    <div class="col-lg-8"></div>
                                    <div class="col-lg-4">
                                        <button class="btn btn-primary float-end prop_disabled" type="button" id="add-crypto" onclick="submitCryptoForm()" style="width:180px">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @include('errors.permission')
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!--/ Form cards -->
        </div>
    </div>
</div>
<!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<!-- number input -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')


<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/create-manager.js') }}"></script>
<script>
    // var btnId = document.querySelector('.prop_disabled');
    //     btnId.addEventListener("click", function() {
    //         this.disabled = true;
    //         setTimeout(() => {
    //             this.disabled =
    //             ;
    //         }, 1000);
    // },true);

    function submitCryptoForm() {
        const form = document.getElementById('form-crypto-address');
        const formData = new FormData(form);
        
        console.log('Submitting crypto form with data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        const btn = document.getElementById("add-crypto");
        const originalText = btn.innerHTML;
        btn.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
        btn.disabled = true;
        
        fetch('{{ route("admin.settings.add_crypto_address") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                block_chain: formData.get('block_chain'),
                instrument: formData.get('instrument'),
                crypto_address: formData.get('crypto_address')
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response received:', data);
            crypto_call_back(data);
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            toastr['error']('An error occurred while submitting the form. Please try again.', 'Error');
        });
    }

    function crypto_call_back(data) {
        console.log('Crypto callback received:', data);
        const btn = document.getElementById("add-crypto");
        const originalText = 'Save';
        
        if (data.success) {
            toastr['success'](data.message, 'Create', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });

            btn.innerHTML = originalText;
            btn.disabled = false;
            $("#form-crypto-address")[0].reset();
            $("#crypto-currency").prop('selectedIndex', 0).trigger("change");
            $("#block-chain").prop('selectedIndex', 0).trigger("change");

        } else {
            console.log('Crypto callback error:', data);
            if (data.errors) {
                $.validator("form-crypto-address", data.errors);
            }
            toastr['error'](data.message, 'Failed', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }


    $(document).on("change", "#crypto-currency", function() {
        let crypto_currency = $(this).val();

        if (crypto_currency === 'USDT') {
            $('#block-chain').html(
                '<option value="">Please select block chain</option><option value="BEP20">BEP20</option><option value="ERC20">ERC20</option><option value="TRC20">TRC20</option>'
            );
            $("#crypto_address").val("");
        } else if (crypto_currency === 'BTC') {
            $('#block-chain').html('<option value="">Please select block chain</option><option value="bitcoin">Bitcoin</option>');
            $("#crypto_address").val("");
        } else if (crypto_currency === 'ETH') {
            $('#block-chain').html('<option value="">Please select block chain</option><option value="ethereum">Ethereum</option>');
            $("#crypto_address").val("");
        } else if (crypto_currency === 'TRON') {
            $('#block-chain').html('<option value="">Please select block chain</option><option value="tron">TRON</option>');
            $("#crypto_address").val("");
        } else {
            $('#block-chain').html('<option value="">Please select crypto currency first</option>');
            $("#crypto_address").val("");
        }
    })
    $(document).on("change", "#block-chain", function() {
        let block_chain = $(this).val();
        // get existing address
        $.ajax({
            dataType: 'json',
            method: 'GET',
            url: '/admin/settings/get_crypto_address/' + block_chain,
            success: function(data) {
                console.log(data)
                $("#crypto_address").val(data.address);
            }
        });
    })

    if (document.getElementById('instrument')) {
        var element = document.getElementById('instrument');
        const example = new Choices(element, {
            searchEnabled: false,
            itemSelectText: ''
        });
    };
</script>
@stop
<!-- BEGIN: page JS -->