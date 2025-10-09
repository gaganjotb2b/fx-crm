@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Participate contest')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<style>
    .prize-icon-container {
        width: 113px;
        height: 113px;
    }

    .prize-icon-container img {
        width: 56% !important;
        margin-top: 10px;
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <!-- contest available -->
                        <!-- contest header -->
                        <div class="d-flex align-content-between justify-content-between">
                            <div class="d-flex align-items-center">
                                <span>
                                    <span class="prize-icon-container bg-gradient-info rounded-circle">
                                        <img src="{{asset('comon-icon/svgs/custom/price-icon.svg')}}" class="img img-fluid ms-4" alt="">
                                    </span>
                                </span>
                                <span>
                                    <h5>{{ucfirst($value->contest_name)}}</h5>
                                    <span>@contest</span>
                                </span>
                            </div>
                            <div class="align-items-center d-flex">
                                <span>
                                    <span>Total participant</span>
                                    <h5>{{\App\Services\contest\ContestService::count_total_participant($value->id)}}/{{$value->max_contest}}</h5>
                                </span>
                            </div>
                        </div>
                        <!-- /end contest header -->
                        <!-- contest body -->
                        <!-- contest image -->
                        <img src="{{\App\Services\contest\ContestService::contest_popup_file($value->id)}}" alt="popup image" class="img-fluid">
                        <div class="contest-description">
                            <p>
                                {!!$value->description!!}.
                            </p>
                        </div>
                        <table class="table">
                            <tr>
                                <th>Contest</th>
                                <th>:</th>
                                <td>{{ucwords(str_replace('_',' ',$value->contest_type))}}</td>
                            </tr>
                            <tr>
                                <th>Start date</th>
                                <th>:</th>
                                <td>{{date('d M Y',strtotime($value->start_date))}}</td>
                            </tr>
                            <tr>
                                <th>End date</th>
                                <th>:</th>
                                <td>{{date('d M Y',strtotime($value->end_date))}}</td>
                            </tr>
                            <tr>
                                <th colspan="3">
                                    Prizes
                                    <table class="table table-active bg-primary table-borderless text-white">
                                        <?php
                                        $prices = json_decode($value->contest_prices);
                                        ?>
                                        @foreach($prices as $price)
                                        @foreach($price as $key=>$pr)
                                        <tr>
                                            <th>{{$key}}</th>
                                            <th>:</th>
                                            <th>$ {{$pr}}</th>
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </table>
                                </th>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-instagram w-100 btn-join-contest" data-id="{{$value->id}}">Join Now</button>
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

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script>
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
            notify('error', data.message, 'Join Contest');
        }
        $.validator('contest-join-form', data.errors);
    }
</script>
@endsection