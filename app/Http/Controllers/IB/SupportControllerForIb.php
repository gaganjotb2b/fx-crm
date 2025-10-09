<?php

namespace App\Http\Controllers\IB;

use App\Http\Controllers\Controller;
use App\Models\ticketAttachment;
use App\Models\ticketReply;
use App\Models\tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\GetPhotosService;
use Illuminate\Support\Facades\Auth;

class SupportControllerForIb extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('support_ticket', 'ib'));
        $this->middleware(AllFunctionService::access('support', 'ib'));
        $this->middleware('is_ib'); //check combined user is an IB
    }
    public function index(Request $request)
    {
        return view('ibs.support.client-ticket');
    }
    public function get_support(Request $request)
    {
        if ($request->ajax()) {
            $list = tickets::where('user_id', auth()->user()->id)->select();
            if (isset($request->userType)) {  //filter  by user  type
                if ($request->userType  != 'all') {
                    $list = $list->where('user_type', $request->userType);
                }
            }
            if (isset($request->status)) {  //filter by status
                if ($request->status  != 'all') {
                    $list = $list->where('status', $request->status);
                }
            }

            if (isset($request->searchval)) {  //filter by status
                $list = $list->where('subject', 'LIKE', '%' . $request->searchval . '%');
            }

            $total_record = $list->limit(10)->count('id');
            $data = array();
            $timeService =  new GetPhotosService;
            if ($total_record != 0) {
                $list = $list->skip($request->current)->take($request->limit)->orderBy('id', 'DESC')->get();

                $group_list = '';
                $i = 0;
                foreach ($list as $group) :
                    $user = User::select('name')->where('id', $group->user_id)->first();
                    $avatar = asset(avatar($group->user_id));
                    $date =  json_decode($timeService->timeis($group->created_at))->date;
                    $time =  json_decode($timeService->timeis($group->created_at))->time;
                    $prority  = "border-secondary";
                    $bulltColor  = 'bg-success';
                    if ($group->status == 'Open') {
                        $bulltColor  = 'bg-success';
                    } else if ($group->status == 'Closed') {
                        $bulltColor  = 'bg-primary';
                    } else if ($group->status == 'Answered') {
                        $bulltColor  = 'bg-warning';
                    } else if ($group->status == 'In-Progress') {
                        $bulltColor  = 'bg-danger';
                    } else if ($group->status == 'On-Hold') {
                        $bulltColor  = 'bg-secondary';
                    }

                    if ($group->priority == 'high') {
                        $prority  = "border-info";
                    } else if ($group->priority == 'critical') {
                        $prority  = "border-danger";
                    }
                    $group_list = ' <a href="javascript:;" class="d-block p-2 "  data-id="' . $group->id . '">
                                        <div class="d-flex p-2 align-items-baseline justify-content-between   ">
                                           
                                            <div class="d-flex align-items-baseline text-truncate al_tr_width">
                                                <div>
                                                    <span class="flex-w-bullt bullet ' . $bulltColor . '"></span>
                                                </div>
                                                <div class="ms-1 text-truncate">
                                                    <h6 class="mb-0"> <span class="bullet' . $bulltColor . '"></span>' . $group->subject . '</h6>
                                                    <span class="text-muted text-sm col-11 p-0 text-truncate d-block">' . $group->description . '</span>
                                                </div>
                                            </div>
                                            <p class="text-muted text-xs  mb-2">' . $date . ' <br> ' . $time . '</p>
                                        </div>
                                    </a>';
                    array_push($data, $group_list);
                    $i++;
                endforeach;
            } else {
                $group_list =  '
                                <div class="no-results d-block">
                                    <h5>No Items Found</h5>
                                </div>
                ';
                array_push($data, $group_list);
            }
            return Response::json([
                'list' => $data,
                'totalRecord' => $total_record
            ]);
        }
    }

    public function get_support_reply(Request $request)
    {
        $timeService =  new GetPhotosService;
        $this->setUpSessionReplay($request->id);
        if ($request->id) {
            $data = array();
            $id  = $request->id;
            $tickets =  tickets::where('id', $id)->first();
            $user = User::select('name', 'email')->where('id', $tickets->user_id)->first();
            $avatar = asset(avatar($tickets->user_id));
            $date =  json_decode($timeService->timeis($tickets->created_at))->date;
            $time =  json_decode($timeService->timeis($tickets->created_at))->time;
            $tickDateTime = $date . ' ' . $time;
            if (isset($tickets->attch_id)) {
                $getAttech = ticketAttachment::where('id', $tickets->attch_id)->first();
                $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
                // get contabo file
                if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {

                    $showImage = $getAttech->path;
                } else {
                    $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
                }
                $defattechment = '
                                <div class="d-flex flex-column">
                                    <a  target="_blank" href="' . $showImage . '" class="mb-50">
                                        <img src="' . $showImage . '"
                                            class="me-25" alt="png" height="100" />
                                        <small class="text-muted fw-bolder">' . $getAttech->path  . '</small>
                                    </a>
                                </div>
                        ';
            } else {
                $defattechment = '';
            }
            $defaultItem =  '
                            <div class="row">
                        
                                <div class="col-md-12 ">
                                    <div class="text-justify mb-2">
                                        <span class=" text-dark">' . $tickets->description . '</span>
                                    </div>
                                    ' . $defattechment . '
                                </div>
                            </div>
                             <hr>';
            array_push($data, $defaultItem);
            //repay table data  
            $allRepay = ticketReply::where('ticket_id', $id)->orderBy('id', 'ASC')->get();
            $item =  "";

            $i = 0;
            if (count($allRepay) != 0) {
                foreach ($allRepay as $item) {

                    $user = User::select('name', 'email')->where('id', $item->replay_by)->first();
                    $avatar = asset(avatar($item->replay_by));
                    $date =  json_decode($timeService->timeis($item->created_at))->date;
                    $time =  json_decode($timeService->timeis($item->created_at))->time;
                    if (isset($item['attch_id'])) {
                        $getAttech = ticketAttachment::where('id', $item['attch_id'])->first();
                        $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
                        // contabo file get

                        if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {
                            // contabo file url
                            $showImage = $getAttech->path;
                        } else {
                            $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
                        }
                        $attechment = '
                                <div class="d-flex flex-column">
                                    <a  target="_blank" href="' . $showImage . '" class="mb-50">
                                        <img src="' . $showImage . '"
                                            class="me-25" alt="png" height="100" /> <br>
                                        <small class="text-muted fw-bolder">' . $getAttech->path  . '</small>
                                    </a>
                                </div>
                                <hr>
                        ';
                    } else {
                        $attechment = '';
                    }

                    if ($item['replay_by'] == auth()->user()->id) {
                        $item = '

                        <div class="row justify-content-end text-right mb-4">
                            <div class="col-auto cheting_auto_card">
                                <div class="card ">
                                    <div class="card-body py-2 px-3">
                                        ' . $attechment . '
                                        <p class="mb-1 mt-1">
                                        ' . $item['reply_description'] . '
                                        </p>
                                    
                                        <div class="d-flex align-items-center text-sm justify-content-end">
                                            <div class="d-flex align-items-center ">
                                                <i class="ni ni-check-bold text-sm me-1"></i>
                                                <small>' . $time . '</small>
                                            </div>
                                            <div>
                                                <span role="button" class="dropdown-toggle font-small-3 text-muted" id="dropdownMenuButton200-' . $i . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                </span>
                                                <div class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton200-' . $i . '">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-end">From:</td>
                                                                <td>' . $user->email . '</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">Date:</td>
                                                                <td>' . $date . ' <br> ' . $time . '</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                    } else {
                        $item  = '
                        <div class="row justify-content-start mb-4">
                            <div class="col-auto  cheting_auto_card  d-flex align-items-end">
                                <div class="mail-items d-flex align-items-center justify-content-start">
                                    <img src="' . $avatar . '" alt="' . $user->name . '" width="30" height="30" style="border-radius: 50%; border: 1px solid;" />
                                </div>
                                <div class="card ms-1">
                                    <div class="card-body py-2 px-3">
                                    ' . $attechment . '
                                        <p class="mb-1 mt-1">
                                        ' . $item['reply_description'] . '
                                        </p>
                                        <div class="d-flex align-items-center text-sm justify-content-start">
                                            <div class="d-flex align-items-center ">
                                                <i class="ni ni-check-bold text-sm me-1"></i>
                                                <small>' . $time . '</small>
                                            </div>
                                            <div>
                                                <span role="button" class="dropdown-toggle font-small-3 text-muted" id="dropdownMenuButton200-' . $i . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                </span>
                                                <div class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton200-' . $i . '">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-end">Name:</td>
                                                                <td>' . $user->name . '</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">From:</td>
                                                                <td>' . $user->email . '</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">Date:</td>
                                                                <td>' . $date . ' <br> ' . $time . '</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        ';
                    }


                    array_push($data, $item);
                    $i++;
                }
            } else {
                $item =  '
                        <div class="text-center">
                            <span class="badge text-dark">No Replay Found</span>
                        </div>
                    
                ';
                array_push($data, $item);
            }

            //other  information 
            $prority  = "#8392AB";
            $bulltColor  = 'bg-success';
            if ($tickets->status == 'Open') {
                $bulltColor  = 'bg-success';
            } else if ($tickets->status == 'Closed') {
                $bulltColor  = 'bg-primary';
            } else if ($tickets->status == 'Answered') {
                $bulltColor  = 'bg-warning';
            } else if ($tickets->status == 'In-Progress') {
                $bulltColor  = 'bg-danger';
            } else if ($tickets->status == 'On-Hold') {
                $bulltColor  = 'bg-secondary';
            }

            if ($tickets->priority == 'high') {
                $prority  = "#17c1e8 ";
            } else if ($tickets->priority == 'critical') {
                $prority  = "#ea0606 ";
            }

            return Response::json([
                'status' => true,
                'html' => $data,
                'headerinfo' => [
                    'subject'  => $tickets->subject,
                    'bullet_class' => $bulltColor,
                    'dateTime'  => $tickDateTime,
                    'prority_color' => $prority,
                    'prority' => $tickets->priority
                ]
            ]);
        }
    }

    public function delete_ticket(Request $request)
    {
        $id = $request->id;
        $ifHas = tickets::where('id', $id)->count();
        $delte = false;
        if ($ifHas != 0) {
            $delte = tickets::where('id', $id)->delete();
            if ($delte) {
                return Response::json([
                    'status' => true,
                    'itemId' => $id
                ]);
            }
        } else {
            return Response::json([
                'status' => true,
                'itemId' => $id
            ]);
        }
    }
    public function send_support_reply(Request $request)
    {

        if ($request->file('file') != null) {
            $validation_rules = [
                'file' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => "Can't upload this file"
                ]);
            }
        }

        $timeService =  new GetPhotosService;
        $ticketId  =  $request->id;
        if (strlen($request->msg) < 2) {
            return Response::json(['status' => false, 'emtyMsg'  =>  true, 'message' => 'write somthing on the message box']);
        }
        if ($request->file('file') != null) {
            $fileNewName = time() . '_support_image_' . $request->file('file')->getClientOriginalName();
            // contabo file upload
            $client = FileApiService::s3_clients();
            $client->putObject([
                'Bucket' => FileApiService::contabo_bucket_name(),
                'Key' => $fileNewName,
                'Body' => file_get_contents($request->file('file'))
            ]);

            $createAttech = ticketAttachment::create([
                'path'  => $fileNewName,
            ]);
            $create = ticketReply::create([
                'ticket_id'  => $request->id,
                'reply_description' => $request->msg,
                'replay_by' => auth()->user()->id,
                'attch_id' =>  $createAttech->id
            ]);
        } else {
            $create = ticketReply::create([
                'ticket_id'  => $request->id,
                'reply_description' => $request->msg,
                'replay_by' => auth()->user()->id
            ]);
        }
        if ($create) {
            $RepayData = ticketReply::where('id', $create->id)->first();
            $this->setUpSessionReplay($request->id);
            $user = User::select('name', 'email')->where('id', auth()->user()->id)->first();
            $avatar = asset(avatar());
            $date =  json_decode($timeService->timeis($RepayData->created_at))->date;
            $time =  json_decode($timeService->timeis($RepayData->created_at))->time;
            $attechment = '';
            if ($RepayData->attch_id != null) {
                $getAttech = ticketAttachment::where('id', $RepayData->attch_id)->first();
                $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
                // contabo file upload

                if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {

                    // contabof ile path
                    $showImage = $getAttech->path;
                } else {
                    $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
                }
                $attechment = ' 

                <div class="d-flex flex-column">
                    <a  target="_blank" href="' . $showImage . '" class="mb-50">
                        <img src="' . $showImage . '"
                            class="me-25" alt="png" height="100" /> <br>
                        <small class="text-muted fw-bolder">' . $getAttech->path  . '</small>
                    </a>
                </div>
                <hr>
                ';
            }
            $backData  = '

                        <div class="row justify-content-end text-right mb-4">
                            <div class="col-auto cheting_auto_card">
                                        
                                <div class="card ">
                                    <div class="card-body py-2 px-3">
                                        ' . $attechment . '
                                        <p class="mb-1 mt-1">
                                        ' . $RepayData->reply_description . '
                                        </p>
                                       
                                        <div class="d-flex align-items-center text-sm justify-content-end">
                                            <div class="d-flex align-items-center ">
                                                <i class="ni ni-check-bold text-sm me-1"></i>
                                                    <small>' . $time . '</small>
                                            </div>
                                            <div>
                                                <span role="button" class="dropdown-toggle font-small-3 text-muted" id="dropdownMenuButton200-' . $RepayData->id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                </span>
                                                <div class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton200-' . $RepayData->id . '">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-end">From:</td>
                                                                <td>' . $user->email . '</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">Date:</td>
                                                                <td>' . $date . ' <br> ' . $time . '</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>';

            return Response::json(['status' => true, 'message' => 'Replay success', 'backData' => $backData]);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed!']);
        }
    }
    public function create_ticket(Request $request)
    {
        $subject = $request->subject;
        $Priority = $request->Priority;
        $description = $request->description;
        $validation_rules = [
            'subject' => 'required|min:5|max:255',
            'Priority' => 'required',
            'description' => 'required|min:7|max:600',
            'attch' => 'file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => "Fix the following errors"
            ]);
        } else {
            $userType = User::select('type')->where('id', auth()->user()->id)->first()->type;
            $createAttech =  false;
            if ($request->attch) {
                $file_document = $request->file('attch');
                $fileNewName = time() . '_support_image_' . $request->file('attch')->getClientOriginalName();
                // contabo file upload
                $client = FileApiService::s3_clients();
                $client->putObject([
                    'Bucket' => FileApiService::contabo_bucket_name(),
                    'Key' => $fileNewName,
                    'Body' => file_get_contents($file_document)
                ]);
                $createAttech = ticketAttachment::create([
                    'path'  => $fileNewName,
                ]);
            }
            if ($createAttech) {
                $insert = tickets::insert([
                    'user_id' =>  auth()->user()->id,
                    'user_type' => $userType,
                    'subject' => $subject,
                    'description' => $description,
                    'priority'  => $Priority,
                    'attch_id' => $createAttech->id
                ]);
            } else {
                $insert = tickets::insert([
                    'user_id' =>  auth()->user()->id,
                    'user_type' => $userType,
                    'subject' => $subject,
                    'description' => $description,
                    'priority'  => $Priority,
                ]);
            }
        }
        if ($insert) {
            return Response::json([
                'status' => true,
                'message'  => 'Ticket create Success'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message'  => 'somthing went wrong'
            ]);
        }
    }

    // START:  Server realtime replay  show system 
    public function setUpSessionReplay($ticktId)
    {
        $ifRExi = ticketReply::where('ticket_id', $ticktId);
        if ($ifRExi->count() != 0) {
            $lrId = $ifRExi->select('id')->orderBy('id', 'DESC')->first()->id;
        } else {
            $lrId = null;
        }
        session([
            'ReplayShowInfo'  => [
                'status' => true,
                'SupportTicketId' => (int)$ticktId,
                'SupportTicketReplayID'  => $lrId
            ]

        ]);
    }

    public function server_replay(Request $request)
    {
        if ($request->session()->has('ReplayShowInfo')) {
            $sessionaray = session('ReplayShowInfo');
            if ($sessionaray['status'] == true) {
                $ifRExi = ticketReply::where('ticket_id', $sessionaray['SupportTicketId']);
                if ($ifRExi->count() != 0) {
                    $dblri =  $ifRExi->select('id')->orderBy('id', 'DESC')->first()->id;
                    if ($dblri != $sessionaray['SupportTicketReplayID']) {
                        $allData  = ticketReply::where('id', $dblri)->first();
                        $this->setUpSessionReplay($sessionaray['SupportTicketId']);
                        return Response::json([
                            'status' => true,
                            'data' => $this->showHtml($allData)
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                        ]);
                    }
                } else {
                    return Response::json([
                        'status' => false,
                    ]);
                }
            }
        }
    }


    public function showHtml($data)
    {
        $item  = $data;
        $timeService =  new GetPhotosService;
        $user = User::select('name', 'email')->where('id', $item->replay_by)->first();
        $avatar = asset(avatar($item->replay_by));
        $date =  json_decode($timeService->timeis($item->created_at))->date;
        $time =  json_decode($timeService->timeis($item->created_at))->time;
        if (isset($item['attch_id'])) {
            $getAttech = ticketAttachment::where('id', $item['attch_id'])->first();
            $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
            // contabo file get
            if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {

                // contabo file path
                $showImage = $getAttech->path;
            } else {
                $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
            }
            // contabo file url
            $attechment = '
                                <div class="d-flex flex-column">
                                    <a  target="_blank" href="' . $showImage . '" class="mb-50">
                                        <img src="' . $showImage . '"
                                            class="me-25" alt="png" height="100" /> <br>
                                        <small class="text-muted fw-bolder">' . $getAttech->path  . '</small>
                                    </a>
                                </div>
                                <hr>
                        ';
        } else {
            $attechment = '';
        }

        if ($item['replay_by'] == auth()->user()->id) {
            $item = '

                        <div class="row justify-content-end text-right mb-4">
                            <div class="col-auto cheting_auto_card">
                                <div class="card ">
                                    <div class="card-body py-2 px-3">
                                        ' . $attechment . '
                                        <p class="mb-1 mt-1">
                                        ' . $item['reply_description'] . '
                                        </p>
                                    
                                        <div class="d-flex align-items-center text-sm justify-content-end">
                                            <div class="d-flex align-items-center ">
                                                <i class="ni ni-check-bold text-sm me-1"></i>
                                                <small>' . $time . '</small>
                                            </div>
                                            <div>
                                                <span role="button" class="dropdown-toggle font-small-3 text-muted" id="dropdownMenuButton200-' . $item['id'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                </span>
                                                <div class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton200-' . $item['id'] . '">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-end">From:</td>
                                                                <td>' . $user->email . '</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">Date:</td>
                                                                <td>' . $date . ' <br> ' . $time . '</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        ';
        } else {
            $item  = '
                        <div class="row justify-content-start mb-4">
                            <div class="col-auto  cheting_auto_card  d-flex align-items-end">
                                <div class="mail-items d-flex align-items-center justify-content-start">
                                    <img src="' . $avatar . '" alt="' . $user->name . '" width="30" height="30" style="border-radius: 50%; border: 1px solid;" />
                                </div>
                                <div class="card ms-1">
                                    <div class="card-body py-2 px-3">
                                    ' . $attechment . '
                                        <p class="mb-1 mt-1">
                                        ' . $item['reply_description'] . '
                                        </p>
                                        <div class="d-flex align-items-center text-sm justify-content-start">
                                            <div class="d-flex align-items-center ">
                                                <i class="ni ni-check-bold text-sm me-1"></i>
                                                <small>' . $time . '</small>
                                            </div>
                                            <div>
                                                <span role="button" class="dropdown-toggle font-small-3 text-muted" id="dropdownMenuButton200-' . $item['id'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                </span>
                                                <div class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton200-' . $item['id'] . '">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-end">Name:</td>
                                                                <td>' . $user->name . '</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">From:</td>
                                                                <td>' . $user->email . '</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">Date:</td>
                                                                <td>' . $date . ' <br> ' . $time . '</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        ';
        }



        return  $item;
    }
    // END:  Server realtime replay  show system 
    public function allSession(Request $request)

    {
        // $request->session()->forget(['ReplayShowInfo']);
        return $request->session()->all();
    }
}
