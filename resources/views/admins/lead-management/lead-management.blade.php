@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Lead Management')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
@stop

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<style>
    input#issue_from:hover+span#typePrompt {
        display: inline;
    }

    input#issue_to:hover+span#typePrompt {
        display: inline;
    }

    .error-msg {
        color: #D64B4B;
    }

    /* .form-control {
        margin-top: 5px;
        margin-bottom: 5px
    } */


    td.details-control {
        background-image: url("{{ asset('datatable-icon/plus.png') }}");
        cursor: pointer;
        background-repeat: no-repeat;
        background-position: center;
    }

    tr.details td.details-control {

        background-image: url("{{ asset('datatable-icon/minus.png') }}");
        cursor: pointer;
        background-repeat: no-repeat;
        background-position: center;

    }

    .modal-content {
        width: auto !important;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0rem 1rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.45;
        color: #6e6b7b;
        text-align: center;
        white-space: nowrap;
        background-color: #fff;
        border: 1px solid #d8d6de;
        border-radius: 0.357rem;
    }
    thead, tbody, tfoot, tr, td, th {
            border-style: hidden;
    }
</style>
@stop


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
                        <h2 class="content-header-title float-start mb-0">Lead Management</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Lead Management</a>
                                </li>

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle disabled" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <!-- <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="message-square"></i><span class="align-middle">Chat</span></a><a class="dropdown-item" href="app-email.html"><i class="me-1" data-feather="mail"></i><span class="align-middle">Email</span></a><a class="dropdown-item" href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span class="align-middle">Calendar</span></a></div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">Filter Report</h4>

                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4 mb-1">
                                            <select class="select2 form-select" name="status" id="status">
                                                <optgroup label="Search By  Method">
                                                    <option value="">All</option>
                                                    <option value="1">Interested</option>
                                                    <option value="2">Not interested</option>
                                                    <option value="3">Wrong Number</option>
                                                    <option value="4">No answer</option>
                                                </optgroup>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="userne" id="userne" placeholder="Name / Email" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="amemail" id="amemail" placeholder="Account Manager Email" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4  mb-1">
                                            <select class="select2 form-select" name="account" id="account">
                                                <optgroup label="Search By Account Type">
                                                    <option value="">All</option>
                                                    <option value="1">Have Account</option>
                                                    <option value="0">Not Account</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4  mb-1">

                                            <select class="select2 form-select" name="category" id="category">
                                                <optgroup label="Search By Status">
                                                    <option value="">All</option>
                                                    @foreach($categories as $value)
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>



                                        <div class="col-md-4">
                                            <div class="input-group" data-toggle="tooltip" data-trigger="hover" class="form-control" data-original-title="Issue Date">
                                                <span class="input-group-text">
                                                    <div class="icon-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                                        </svg>
                                                    </div>
                                                </span>
                                                <input id="from" type="text" title="Issue date" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                                <span class="input-group-text">To</span>
                                                <input id="to" type="text" title="Issue date" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                            </div>
                                        </div>




                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4  mb-1">
                                            <select class="select2 form-select" name="have_task" id="have_task">
                                                <optgroup label="Search By Status">

                                                    <option value="">All</option>
                                                    <option value="0">No Task</option>
                                                    <option value="1">Open Task</option>
                                                    <option value="2">Completed Task</option>

                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="manager_email" id="manager_email" placeholder="Desk Manager Email" data-column-index="0" />
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">RESET</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">FILTER</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="my-0" />

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">Lead Management</h4>

                                <div class="panel-actions" style="top:6px;">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#shareProject"><i data-feather='user-plus'></i> &nbsp; Add New Lead</button>

                                    <!--<button class="mb-xs mt-xs mr-xs btn btn-primary btn btn-primary" data-toggle="modal" data-target="#modal_import"> <i data-feather='upload'></i> &nbsp; Import Leads</button>-->
                                    <!-- <button class="mb-xs mt-xs mr-xs btn btn-primary btn btn-primary" onclick="actionView(this)" data-uid="0" > <i data-feather='list'></i> &nbsp; All Actions</button> -->
                                </div>

                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">

                                <div>

                                    Open Task <i data-feather='disc' style="margin-right: 5px; color: #4DD79C"></i>&nbsp;
                                    Completed Task <i data-feather='disc' style="margin-right: 5px; color: #0090D9"></i>&nbsp;
                                    No Task <i data-feather='disc' style="margin-right: 5px; color: #D64B4B"></i>&nbsp;

                                    <!-- <span style="float: right;"><a href="#myModal_task" data-target="#myModal_remainder" data-toggle="modal" ><i data-feather='bell' style="margin-right: 5px; color: #EC12B1"></i>Set Remainder &nbsp;&nbsp;</a></span> -->
                                </div>

                                <table id="lead_report" class="datatables-ajax table table-responsive">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Country</th>
                                            <th>Category</th>
                                            <th>Account</th>
                                            <th>Join Date</th>
                                            <th>Task</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
            <!--/ Ajax Sourced Server-side -->
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Registration Form</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="addNewLead" action="{{route('admin.add_new_lead')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Full Name" value="" data-msg="Please Enter Full Name" /></br>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="email" class="form-control" placeholder="Email" value="" data-msg="Please Enter Email" />
                        </div>

                        <div class="col-md-6">
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="" data-msg="Please Enter Phone Number" /></br>
                        </div>

                        <div class="col-md-6">
                            <input type="text" name="city" class="form-control" placeholder="City" value="" data-msg="Please Enter City" />
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="state" class="form-control" placeholder="state" value="" data-msg="Please Enter State" /></br>
                        </div>
                        <div class="col-md-6">
                          
                            
                            <select class="select2 form-select" name="country" id="country">
                                <optgroup label="Search By Status">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            
                            
                           
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="zip" class="form-control" placeholder="Zip Code" value="" data-msg="Please Enter Zip code" />
                        </div>

                        <div class="col-md-6">
                            <select class="select2 form-select" name="category_id" id="category_id">
                                <optgroup label="Search By Status">
                                    <option value="">Select Categories</option>
                                    @foreach($categories as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="addNewLeadBtn" onclick="_run(this)" data-form="addNewLead" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" data-callback="addNewLeadCallBack" data-btnid="addNewLeadBtn">Save</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updatelead" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Update Form</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="updateLeadForm" action="{{route('admin.lead-management.update')}}" method="post">

                    @csrf
                    <input type="hidden" name="id" id="id" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-6">
                            <label>User name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Full Name" value="" data-msg="Please Enter Full Name" /></br>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="Email" value="" data-msg="Please Enter Email" />
                        </div>

                        <div class="col-md-6">
                            <label>Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="" data-msg="Please Enter Phone Number" /></br>
                        </div>

                        <div class="col-md-6">
                            <label>City</label>
                            <input type="text" id="city" name="city" class="form-control" placeholder="City" value="" data-msg="Please Enter City" />
                        </div>
                        <div class="col-md-6">
                            <label>State</label>
                            <input type="text" id="state" name="state" class="form-control" placeholder="State" value="" data-msg="Please Enter State" /></br>
                        </div>
                        <div class="col-md-6">
                            <label>Country</label>
                            
                             <select class="select2 form-select" name="country" id="edit_country">
                                <optgroup label="Search By Status">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Zip Code</label>
                            <input type="text" id="zip" name="zip" class="form-control" placeholder="Zip Code" value="" data-msg="Please Enter Zip code" />
                        </div>

                        <div class="col-md-6">
                            <label>Category</label>
                            <select class="select2 form-select" name="category_id" id="edit_category_id">
                                <optgroup label="Search By Status">
                                    <option value="">Select Categories</option>
                                    @foreach($categories as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="updateLeadBtn" onclick="_run(this)" data-form="updateLeadForm" data-callback="updateLeadCallBack" data-btnid="updateLeadBtn">Save</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="deletelead" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Delete Lead</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="deleteLead" action="{{route('admin.lead-management.delete')}}" method="post">
                    @csrf

                    <input type="hidden" name="deletid" id="deletid" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                           are you sure deleted this Lead
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="deleteLeadBtn" onclick="_run(this)" data-form="deleteLead" data-callback="deleteLeadCallBack" data-btnid="deleteLeadBtn">Delete</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="addtask" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Add Task</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="addTaskForm" action="{{route('admin.lead-management.addTask')}}" method="post">
                    @csrf

                    <input type="hidden" name="addtaskuserid" id="addtaskuserid" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="task_name" id="task_name" class="form-control" value="" />
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="addTaskBtn" onclick="_run(this)" data-form="addTaskForm" data-callback="addTaskCallBack" data-btnid="addTaskBtn">Add Task</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="updateTask" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Update Task</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="updateTaskForm" action="{{route('admin.lead-management.updateTask')}}" method="post">
                    @csrf

                    <input type="hidden" name="updatetaskId" id="updatetaskId" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="update_task_name" id="update_task_name" class="form-control" value="" />
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="updateTaskBtn" onclick="_run(this)" data-form="updateTaskForm" data-callback="updateTaskCallBack" data-btnid="updateTaskBtn">Update Task</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="deletetask" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Delete task</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="deleteTaskForm" action="{{route('admin.lead-management.deletetask')}}" method="post">
                    @csrf

                    <input type="hidden" name="delettaskid" id="delettaskid" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            Do you want to delete it?
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="deletetaskBtn" onclick="_run(this)" data-form="deleteTaskForm" data-callback="deletetaskCallBack" data-btnid="deletetaskBtn">Delete</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="addComment" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Add Comment</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="addCommentForm" action="{{route('admin.lead-management.addComment')}}" method="post">
                    @csrf

                    <input type="hidden" name="addCommentuserid" id="addCommentuserid" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <textarea type="text" name="add_comment" id="add_comment" class="form-control" value=""></textarea>
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="addCommentBtn" onclick="_run(this)" data-form="addCommentForm" data-callback="addCommentCallBack" data-btnid="addCommentBtn">Add Comment</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="updateComment" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Update Comment</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="updateCommentForm" action="{{route('admin.lead-management.updateComment')}}" method="post">
                    @csrf

                    <input type="hidden" name="updateCommentuserid" id="updateCommentuserid" class="form-control" value="" />

                    <div class="row">
                        <div class="col-md-12">
                            <textarea type="text" name="update_comment" id="update_comment" class="form-control" value=""></textarea>
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="updateCommentBtn" onclick="_run(this)" data-form="updateCommentForm" data-callback="updateCommentCallBack" data-btnid="updateCommentBtn">Update Comment</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="deleteComment" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Delete Comment</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="deleteCommentForm" action="{{route('admin.lead-management.deleteComment')}}" method="post">
                    @csrf

                    <input type="hidden" name="deleteCommentid" id="deleteCommentid" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            Do you want to delete it?
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="deleteCommentBtn" onclick="_run(this)" data-form="deleteCommentForm" data-callback="deleteCommentCallBack" data-btnid="deleteCommentBtn">Delete</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="actionsCreate" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>New Action</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="addactionsCreateForm" action="{{route('admin.lead-management.addactions')}}" method="post">
                    @csrf
                    <input type="hidden" name="acid" id="acid" class="form-control" />
                    <input type="hidden" name="user_type" value="lead">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Client Name</label>
                            <input type="text" name="cname" id="cname" class="form-control" placeholder="Full Name" value="" data-msg="Please Enter Full Name" />
                        </div>


                        <div class="col-md-6">
                            <label>Action Type</label>
                            <select class="select2 form-select" name="action_type" id="action_type">
                                <optgroup label="Search By Status">
                                    <option value="phone call">Phone Call</option>
                                    <option value="on mail">On Mail</option>
                                    <option value="discuss">Discuss</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Action Status</label>
                            <select class="select2 form-select" name="action_status" id="action_status">
                                <optgroup label="Search By Status">
                                    <option value="in proccess">In Proccess</option>
                                    <option value="completed">Completed</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Action Date</label>

                            <input id="date" type="text" name="date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                        </div>

                        <div class="col-md-12">
                            <label>Desciption</label>
                            <textarea class="form-control" name="desciption" id="desciption" rows="3"></textarea>
                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="addactionsCreateBtn" onclick="_run(this)" data-form="addactionsCreateForm" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" data-callback="addactionsCreateCallBack" data-btnid="addactionsCreateBtn">Save</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="addAccountManager" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-transparent">
                <h3>Add account Manager</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="managerLeadfrom" action="{{route('admin.lead-management.addmanager')}}" method="post">
                    @csrf

                    <input type="hidden" name="AccountManagerid" id="AccountManagerid" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <level>Manager Email</level>
                            <input type="text" name="accountManagerEmail" id="accountManagerEmail" class="form-control" value="" />
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="managerLeadBtn" onclick="_run(this)" data-form="managerLeadfrom" data-callback="managerLeadCallBack" data-btnid="managerLeadBtn">Submite</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="addDeskManager" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-transparent">
                <h3>Add Desk Manager</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="deskmanagerLeadfrom" action="{{route('admin.lead-management.deskmanager')}}" method="post">
                    @csrf

                    <input type="hidden" name="deskManagerid" id="deskManagerid" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <level>Desk Manager Email</level>
                            <input type="text" name="deskManagerEmail" id="deskManagerEmail" class="form-control" value="" />
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="deskmanagerLeadBtn" onclick="_run(this)" data-form="deskmanagerLeadfrom" data-callback="deskmanagerLeadCallBack" data-btnid="deskmanagerLeadBtn">Submite</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="sendMail" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-transparent">
                <h3>Send Mail</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="sendmailfrom" action="{{route('admin.lead-management.sendmail')}}" method="post">
                    @csrf

                    <input type="hidden" name="sendmailId" id="sendmailId" class="form-control" value="" />
                    <input type="hidden" name="sendmailEmail" id="sendmailEmail" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <level>Subject</level>
                            <input type="text" name="subject" id="subject" class="form-control" value="" />
                        </div>
                        <div class="col-md-12">
                            <level>Message</level>
                           
                             <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>

                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="sendMailLeadBtn" onclick="_run(this)" data-form="sendmailfrom" data-callback="sendMailCallBack" data-btnid="sendMailLeadBtn">Submite</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="taskCompleted" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-transparent">
                <h3>Send Mail</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body px-sm-5 mx-50 pb-4">
                <form id="taskcompletefrom" action="{{route('admin.lead-management.taskcomplete')}}" method="post">
                    @csrf

                    <input type="hidden" name="taskuserID" id="taskuserID" class="form-control" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <select class="select2 form-select" name="taskName" id="taskName">
                                <optgroup label="Search By Status">
                                    <option value="">Select Status</option>
                                    <option value="done">Completed</option>
                                   
                                </optgroup>
                            </select>
                        </div>


                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button style="float: right;margin-top: 23px;" class="btn btn-primary modal-confirm savebtn col-md-5" type="button" id="completetaskBtn" onclick="_run(this)" data-form="taskcompletefrom" data-callback="taskcompleteCallBack" data-btnid="completetaskBtn">Submite</button>

                            </div>
                        </div>
                    </footer>

                </form>
            </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="ConvertToAccount" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-transparent">
                <h3>Convert Account</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body px-sm-5 mx-50 pb-4">
                 <form action="{{ route('admin.lead-management.convertToAccount') }}" method="post" id="form-account-auto">
                                @csrf
                                <input type="hidden" name="user_id" id="user-for-auto">
                                <div class="row">
                                    {{-- <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="platform-live">Platform</label>
                                            <select name="platform" class="select2 form-select" id="platform-live">
                                                <option value="">Select a platform</option>
                                                {!! $all_platform !!}
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- single or multiple platform handle from the component --}}
                                    {{-- check condition single platform true or false --}}
                                    {{-- if platform is single then platform field will be hidden and not otherwise --}}
                                    <x-platform-option account-type="live"
                                        use-for="admin_portal_auto"></x-platform-option>
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="group-live">Group</label>
                                            <select name="group" class="select2 form-select" id="group-live">
                                                <option value="">Select a group</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="leverage-live">Leverage</label>
                                            <select name="leverage" class="select2 form-select" id="leverage-live">
                                                <option value="">Select leverage</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <!-- submit button auto -->
                                    <div class="col-xl-6 col-md-6 col-12 ms-auto">
                                        <button type="button" class="btn btn-primary form-control text-center mt-2" id="btn-account-auto" onclick="_run(this)" data-el="fg" data-form="form-account-auto" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="account_auto_call_back" data-btnid="btn-account-auto">Create</button>
                                    </div>
                                </div>
                            </form>
            </div>
    </div>
  </div>
</div>



<!-- Modal Action View-->
<div id="ActionView" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3>Active Actions</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="outer" id="actionTableOverlay" style="display: none;">
                    <div class="middle">
                        <div class="inner">
                            Loading....
                        </div>
                    </div>
                </div>
                <form id="actionViewForm" action="ajax/action_process.php" method="post">
                    <input type="" name="process" value="action_view">
                    <input type="" name="user_id" id="acv_uid" value="">
                    <input type="" name="who" value="lead">
                </form>

                <table class="table table-bordered" id="actionTable">
                    <thead>
                        <tr>

                            <th scope="col">Client Email</th>
                            <th scope="col">Action Type</th>
                            <th scope="col">Action Status</th>
                            <th scope="col">Action Date</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Operation</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div id="pagination"></div>
            </div>
            <div style="clear: both;"></div>

        </div>
    </div>
</div>



@stop

@section('vendor-js')

@stop

@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>

<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>


<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop

@section('page-js')

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/modal-add-new-cc.js') }}"></script>
<script src="{{ asset('admin-assets/assets/js/common-ajax.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>




<!-- datatable  -->
<script>
    function format(d) {

        return d.extra;
    }

    var dt = $('#lead_report').DataTable({
        language: {
            search: "",
            lengthMenu: " _MENU_ "
        },
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/admin/lead-management?op=data_table",
            "data": function(d) {
                return $.extend({}, d, {
                    // "status": $("#status").val(),
                    // "userne": $("#userne").val(),
                    // "amemail": $("#amemail").val(),
                    // "category":$("#category").val(),
                    // "from":$("#from").val(),
                    // "to":$("#to").val(),
                    // "have_task":$("#have_task").val(),
                    // "manager_email":$("#manager_email").val(),
                });
            }
        },
        "columns": [{
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
            {
                "data": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "mobile"
            },
            {
                "data": "country"
            },
            {
                "data": "category"
            },
            {
                "data": "account"
            },
            {
                "data": "join_date"
            },
            {
                "data": "task"
            },
            {
                "data": "action"
            },
        ],
        "drawCallback": function(settings) {
            $("#filterBtn").html("FILTER");
        },
        "order": [
            [1, 'desc']
        ]
    });



    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('#lead_report tbody').on('click', 'tr td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = dt.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice(idx, 1);
        } else {
            tr.addClass('details');
            row.child(format(row.data())).show();

            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });

    // On each draw, loop over the `detailRows` array and show any child rows
    dt.on('draw', function() {
        $.each(detailRows, function(i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });

    $('#filterBtn').click(function(e) {

        dt.draw();
    });
    



   $(document).on("click", "#convertToAccountBtn" , function () {
            let server = $("#platform-live").val();
            let client_type = 'live';
            $.ajax({
                url: '/admin/client-management/get-client-groups/' + server + '/meta-server/' + client_type,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $("#group-live").html(data.client_groups);
                    $("#leverage-live").html(data.leverage);
                }
            });
        });






    function addNewLeadCallBack(data) {
        $('#addNewLeadBtn').prop('disabled', true);
        if (data.success) {

            notify('success', data.message, 'Add New Lead');
            // toastr['message'](data.message, 'Update',{
            //             showMethod: 'slideDown',
            //             hideMethod: 'slideUp',
            //             closeButton: true,
            //             tapToDismiss: false,
            //             progressBar: true,
            //             timeOut: 2000,
            //         });

            location.reload();
            $("#shareProject").hide();

        } else {
            notify('error', data.message, 'Add New Lead');
            $('#addNewLeadBtn').prop('disabled', false);
            $.validator("addNewLead", data.errors);
        }
    }


    function updateLeadCallBack(data) {
        $('#updateLeadBtn').prop('disabled', true);
        if (data.success) {
            notify('success', data.message, 'Lead Update');
            $("#shareProject").hide();
             location.reload();
        } else {
             notify('error', data.message, 'Lead Update');
            $('#updateLeadBtn').prop('disabled', false);
            $.validator("updateLeadForm", data.errors);
        }
    }
    
    function account_auto_call_back(data) {
        $('#btn-account-auto').prop('disabled', true);
        if (data.success) {
            notify('success', data.message,'Account Convert');
            $("#ConvertToAccount").hide();
             location.reload();
        } else {
             notify('error', data.message ,'Account Convert');
            $('#btn-account-auto').prop('disabled', false);
            $.validator("form-account-auto", data.errors);
        }
    }
    
    function sendMailCallBack(data) {
        $('#sendMailLeadBtn').prop('disabled', true);
        if (data.success) {
             notify('success', data.message,'Mail Send');
            $("#sendMail").hide();
             location.reload();
        } else {
             notify('error', data.message,'Mail Send');
            $('#sendMailLeadBtn').prop('disabled', false);
            $.validator("sendmailfrom", data.errors);
        }
    }
    
    function taskcompleteCallBack(data) {
        $('#completetaskBtn').prop('disabled', true);
        if (data.success) {
             notify('success', data.message,'Task');
            $("#taskCompleted").hide();
             location.reload();
        } else {
             notify('error', data.message,'Task');
            $('#completetaskBtn').prop('disabled', false);
            $.validator("taskcompletefrom", data.errors);
        }
    }
    
    function managerLeadCallBack(data) {
        $('#managerLeadBtn').prop('disabled', true);
        if (data.success) {
            notify('success', data.message, 'Account Manager');
            $("#addAccountManager").hide();
            location.reload();
        } else {
             notify('error', data.message, 'Account Manager');
            $('#managerLeadBtn').prop('disabled', false);
            $.validator("managerLeadfrom", data.errors);
        }
    }
    
    function deskmanagerLeadCallBack(data) {
        $('#deskmanagerLeadBtn').prop('disabled', true);
        if (data.success) {
            notify('success', data.message, 'Desk Manager');
            $("#addDeskManager").hide();
            location.reload();
        } else {
             notify('error', data.message, 'Desk Manager');
            $('#deskmanagerLeadBtn').prop('disabled', false);
            $.validator("deskmanagerLeadfrom", data.errors);
        }
    }






    function deleteLeadCallBack(data) {
        $('#deleteLeadBtn ').prop('disabled', true);
        if (data.success) {
            $("#deletelead").hide();
            notify('success', data.message, 'Lead Delete');
            location.reload();
        } else {
             notify('error', data.message, 'Lead Delete');
            $('#deleteLeadBtn ').prop('disabled', false);

            $.validator("addNewLead", data.errors);
        }
    }


    function addTaskCallBack(data) {
        $('#addTaskBtn ').prop('disabled', true);
        if (data.success) {
            $("#addtask").hide();
            notify('success', data.message, 'Add Task');
            location.reload();
        } else {
             notify('error', data.message, 'Add Task');
            $('#addTaskBtn ').prop('disabled', false);

            $.validator("addTaskForm", data.errors);
        }
    }

    function updateTaskCallBack(data) {
        $('#updateTaskBtn ').prop('disabled', true);
        if (data.success) {
            $("#updateTask").hide();
            notify('success', data.message, 'Update Task');
            location.reload();
        } else {
             notify('error', data.message, 'Update Task');
            $('#updateTaskBtn ').prop('disabled', false);

            $.validator("updateTaskForm", data.errors);
        }
    }

    function addactionsCreateCallBack(data) {
        $('#addactionsCreateBtn ').prop('disabled', true);
        if (data.success) {
            $("#actionsCreate").hide();
            notify('success', data.message,'Lead Action');
            location.reload();
        } else {
             notify('error', data.message,'Lead Action');
            $('#addactionsCreateBtn ').prop('disabled', false);

            $.validator("addactionsCreateForm", data.errors);
        }
    }




    function deletetaskCallBack(data) {
        $('#deletetaskBtn ').prop('disabled', true);
        if (data.success) {
            $("#deletelead").hide();
            notify('success', data.message,'Delete Task');
            location.reload();
        } else {
             notify('errors', data.message,'Delete Task');
            $('#deletetaskBtn ').prop('disabled', false);

            $.validator("deleteTaskForm", data.errors);
        }
    }

    function addCommentCallBack(data) {
        $('#addCommentBtn ').prop('disabled', true);
        if (data.success) {
            notify('success', data.message,'Add Comment');
            location.reload();
        } else {
             notify('error', data.message,'Add Comment');
            $('#addCommentBtn ').prop('disabled', false);

            $.validator("deleteTaskForm", data.errors);
        }
    }

    function updateCommentCallBack(data) {
        $('#updateCommentBtn').prop('disabled', true);
        if (data.success) {
            notify('success', data.message,'Update Comment');
            location.reload();
        } else {
             notify('error', data.message,'Update Comment');
            $('#updateCommentBtn').prop('disabled', false);
            notify('errore', data.message);
            $.validator("updateCommentForm", data.errors);
        }
    }

    function deleteCommentCallBack(data) {
        $('#deleteCommentBtn ').prop('disabled', true);
        if (data.success) {
            $("#deletelead").hide();
            notify('success', data.message,'Delete Comment');
            location.reload();
        } else {
             notify('error', data.message,'Delete Comment');
            $('#deleteCommentBtn ').prop('disabled', false);
        }
    }



    function clickEdit(e) {
        var userDetails = $(e);
        $("#id").val(userDetails.data('id'));
        $("#name").val(userDetails.data('name'));
        $("#email").val(userDetails.data('email'));
        $("#phone").val(userDetails.data('phone'));
        $("#city").val(userDetails.data('city'));
        $("#state").val(userDetails.data('state'));
        $("#edit_country").val(userDetails.data('country'));
        $("#zip").val(userDetails.data('zip'));
        $("#edit_category_id").val(userDetails.data('Category'));

    }
    
    function sendMail(e) {
        var userDetails = $(e);
        $("#sendmailId").val(userDetails.data('id'));

        $("#sendmailEmail").val(userDetails.data('email'));
      
    }


    function clickDeleteBtn(id) {

        $("#deletid").val(id);
    }
    
    function completedtask(id) {
        
        $('#taskCompleted').modal('show');
        $("#taskuserID").val(id);
    }
    
    function convertToAccount(id) {
        
        $('#ConvertToAccount').modal('show');
        $("#user-for-auto").val(id);
    }
    
    function accountManager(id) {

        $("#AccountManagerid").val(id);
    }
    function deskManager(id) {

        $("#deskManagerid").val(id);
    }


    function addTask(id) {
        $("#addtaskuserid").val(id);
    }

    function deletetask(id) {

        $("#delettaskid").val(id);
    }

    function addComment(id) {
        $("#addCommentuserid").val(id);
    }

    function deleteComment(id) {

        $("#deleteCommentid").val(id);
    }

    function editeComment(e) {
        var _self = $(e);

        $("#updateCommentuserid").val(_self.data('cid'));
        $("#update_comment").val(_self.data('note'));
        $("#updateComment").modal('show');

    }

    function editetask(e) {
        var editetasks = $(e);

        $("#updatetaskId").val(editetasks.data('id'));
        $("#update_task_name").val(editetasks.data('task_name'));
        $("#updateTask").modal('show');

    }


    function actionCreate(e) {
        var actionCreates = $(e);

        $("#acid").val(actionCreates.data('auid'));
        $("#cname").val(actionCreates.data('name'));

        $("#actionsCreate").modal('show');

    }


    function actionView(e) {
        var _self = $(e);
        $("#acv_uid").val(_self.data('uid'));
        $("#ActionView").modal('show');

    }
</script>
@stop