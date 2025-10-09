<?php

namespace App\Http\Controllers\admins;

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

class SupportControllerForAdmin extends Controller
{
    public function __construct()
    {
        // $this->middleware(["role:support"]);
        // $this->middleware(["role:client ticket"]);
        // system module control
        $this->middleware(AllFunctionService::access('support', 'admin'));
        $this->middleware(AllFunctionService::access('support_tickets', 'admin'));
    }
    public function index(Request $request)
    {
        $allcount = tickets::count();
        $IbTCount = tickets::where('user_type', 'ib')->count();
        $traderCount = tickets::where('user_type', 'trader')->count();
        return view('admins.support.client-ticket', [
            'allCount'    => $allcount,
            'IbTCount'    => $IbTCount,
            'traderCount' => $traderCount
        ]);
    }

    public function getCount(Request $request)
    {
        $allcount = tickets::count();
        $IbTCount = tickets::where('user_type', 'ib')->count();
        $traderCount = tickets::where('user_type', 'trader')->count();
        return Response::json([
            'status' => true,
            'allcount' => $allcount,
            'IbTCount' => $IbTCount,
            'traderCount' => $traderCount
        ]);
    }

    public function get_support(Request $request)
    {
        if ($request->ajax()) {
            $list = tickets::select();
            if (isset($request->userType)) {  //filter  by user  type
                if ($request->userType  != 'all') {
                    $list = $list->where('user_type', $request->userType);
                }
            }
            if (isset($request->status)) {  //filter by status
                $list = $list->Where('status', $request->status);
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
                    $bulltColor  = 'bullet-success';
                    if ($group->status == 'Open') {
                        $bulltColor  = 'bullet-success';
                    } else if ($group->status == 'Closed') {
                        $bulltColor  = 'bullet-primary';
                    } else if ($group->status == 'Answered') {
                        $bulltColor  = 'bullet-warning';
                    } else if ($group->status == 'In-Progress') {
                        $bulltColor  = 'bullet-danger';
                    } else if ($group->status == 'On-Hold') {
                        $bulltColor  = 'bullet-secondary';
                    }

                    if ($group->priority == 'high') {
                        $prority  = "border-info";
                    } else if ($group->priority == 'critical') {
                        $prority  = "border-danger";
                    }
                    $group_list = '
                                        <div class="mail-left pe-50" data-id="' . $group->id . '">
                                            <div class="avatar">
                                                <img src="' . $avatar . '" alt="' . $user->name . '" />
                                            </div>
                                            <div class="user-action">
                                                <div class="form-check">
                                                    <input type="checkbox" name="ticketID" value="' . $group->id . '" class="form-check-input"
                                                        id="customCheck-' . $i . '" />
                                                    <label class="form-check-label" for="customCheck-' . $i . '"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mail-body">
                                            <div class="mail-details">
                                                <div class="mail-items">
                                                    <h5 class="mb-25">' . $user->name . '</h5>
                                                    <span class="text-truncate subject">' . $group->subject . '
                                                    </span>
                                                </div>
                                                <div class="mail-meta-item d-flex align-items-center  bullet_html">
                                                <span class="me-50 bullet ' . $bulltColor . ' bullet-sm"></span>
                                                    <p class="text-capitalize item_prority m-0 border-1 ' . $prority . '">' . $group->priority . '</p>
                                                   
                                                    <span class="mail-date">' . $date . ' <br> ' . $time . '</span>
                                                </div>
                                            </div>
                                            <div class="mail-message">
                                                <p class="text-truncate mb-0">
                                                ' . $group->description . '
                                                </p>
                                            </div>
                                        </div>';
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
            if (isset($tickets->attch_id)) {
                $getAttech = ticketAttachment::where('id', $tickets->attch_id)->first();
                $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
                if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {

                    $showImage = $getAttech->path;
                } else {
                    $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
                }

                $defattechment = '
                        <div class="card-footer">
                            <div class="mail-attachments">
                                <div class="d-flex align-items-center mb-1">
                                    <i data-feather="paperclip" class="font-medium-1 me-50"></i>
                                    <h5 class="fw-bolder text-body mb-0">1 Attachments</h5>
                                </div>
                                <div class="d-flex flex-column">
                                    <a  target="_blank" href="' . $showImage . '" class="mb-50">
                                        <img src="' . $showImage . '"
                                            class="me-25" alt="png" height="100" />
                                        <small class="text-muted fw-bolder">' . $getAttech->path . '</small>
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                        ';
            } else {
                $defattechment = '';
            }
            $defaultItem =  '
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header email-detail-head">
                            <div
                                class="user-details d-flex justify-content-between align-items-center flex-wrap">
                                <div class="avatar me-75">
                                    <img src="' . $avatar . '"
                                        alt="' . $user->name . '" width="48" height="48" />
                                </div>
                                <div class="mail-items">
                                    <h5 class="mb-0">' . $user->name . '</h5>
                                    <div class="email-info-dropup dropdown">
                                        <span role="button"
                                            class="dropdown-toggle font-small-3 text-muted"
                                            id="dropdownMenuButton200-def" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            ' . $user->email . '
                                        </span>
                                        <div class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton200-def">
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
                            <div class="mail-meta-item d-flex align-items-center">
                                <small class="mail-date-time text-muted">' . $date . ' <br> ' . $time . '</small>
                                <div class="dropdown ms-50">
                                    <div role="button" class="dropdown-toggle hide-arrow"
                                        id="email_more_2-def" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="email_more_2-def">
                                        <div class="dropdown-item"><i
                                                data-feather="corner-up-left"
                                                class="me-50"></i>Reply</div>
                                        <div class="dropdown-item"><i
                                                data-feather="corner-up-right"
                                                class="me-50"></i>Forward</div>
                                        <div class="dropdown-item"><i data-feather="trash-2"
                                                class="me-50"></i>Delete</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body mail-message-wrapper pt-2">
                            <div class="mail-message">
                                <p class="card-text">
                                ' . $tickets->description . '
                                </p>
                            </div>
                        </div>
                        ' . $defattechment . '
                    </div>
                </div>
            </div>
            ';
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

                        if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {

                            // contabo file path
                            $showImage = $getAttech->path;
                        } else {
                            $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
                        }
                        // contabo file url
                        $attechment = '
                        <div class="card-footer">
                            <div class="mail-attachments">
                                <div class="d-flex align-items-center mb-1">
                                    <i data-feather="paperclip" class="font-medium-1 me-50"></i>
                                    <h5 class="fw-bolder text-body mb-0">1 Attachments</h5>
                                </div>
                                <div class="d-flex flex-column">
                                    <a  target="_blank" href="' . $showImage . '" class="mb-50">
                                        <img src="' . $showImage . '"
                                            class="me-25" alt="png" height="100" />
                                        <small class="text-muted fw-bolder">' . $getAttech->path . '</small>
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                        ';
                    } else {
                        $attechment = '';
                    }
                    $derection  =  '';
                    $rowReverce  = '';
                    if ($item['replay_by'] == auth()->user()->id) {
                        $derection  = 'justify-content-end';
                        $rowReverce = 'flex-row-reverse';
                    } else {
                        $derection  = 'justify-content-start';
                    }

                    $item  = '
                    <div class="row ' . $derection . '">
                        <div class="col-8">
                            <div class="card">
                                <div class="card-header email-detail-head ' . $rowReverce . '">
                                    <div
                                        class="user-details d-flex justify-content-between align-items-center flex-wrap ' . $rowReverce . '">
                                        <div class="avatar me-75">
                                            <img src="' . $avatar . '"
                                                alt="' . $user->name . '" width="48" height="48" />
                                        </div>
                                        <div class="mail-items">
                                            <h5 class="mb-0">' . $user->name . '</h5>
                                            <div class="email-info-dropup dropdown">
                                                <span role="button"
                                                    class="dropdown-toggle font-small-3 text-muted"
                                                    id="dropdownMenuButton200-' . $i . '" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    ' . $user->email . '
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
                                    <div class="mail-meta-item d-flex align-items-center">
                                        <small class="mail-date-time text-muted">' . $date . ' <br> ' . $time . '</small>
                                        <div class="dropdown ms-50">
                                            <div role="button" class="dropdown-toggle hide-arrow"
                                                id="email_more_2-' . $i . '" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" class="font-medium-2"></i>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="email_more_2-' . $i . '">
                                                <div class="dropdown-item"><i
                                                        data-feather="corner-up-left"
                                                        class="me-50"></i>Reply</div>
                                                <div class="dropdown-item"><i
                                                        data-feather="corner-up-right"
                                                        class="me-50"></i>Forward</div>
                                                <div class="dropdown-item"><i data-feather="trash-2"
                                                        class="me-50"></i>Delete</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body mail-message-wrapper pt-2">
                                    <div class="mail-message">
                                        <p class="card-text">
                                           ' . $item['reply_description'] . '
                                        </p>
                                    </div>
                                </div>
                                ' . $attechment . '
                            </div>
                        </div>
                    </div>
                    ';

                    array_push($data, $item);
                    $i++;
                }
            } else {
                $item =  '
                                <div class="no-results d-block">
                                    <h5>No Replay Found</h5>
                                </div>
                ';
                array_push($data, $item);
            }
            tickets::where('id', $request->id)->update([
                'status' => 'Open'
            ]);

            return $data;
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
            tickets::where('id', $request->id)->update([
                'status' => 'Answered'
            ]);
            $this->setUpSessionReplay($request->id);

            $RepayData = ticketReply::where('id', $create->id)->first();
            $user = User::select('name', 'email')->where('id', auth()->user()->id)->first();
            $avatar = asset(avatar());
            $date =  json_decode($timeService->timeis($RepayData->created_at))->date;
            $time =  json_decode($timeService->timeis($RepayData->created_at))->time;
            $attechment = '';
            if ($RepayData->attch_id != null) {
                $getAttech = ticketAttachment::where('id', $RepayData->attch_id)->first();
                $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
                // contabo file api service
                if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {
                    
                    $showImage = $getAttech->path;
                } else {
                    $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
                }
               
                $attechment = '
                <div class="card-footer">
                    <div class="mail-attachments">
                        <div class="d-flex align-items-center mb-1">
                            <i data-feather="paperclip" class="font-medium-1 me-50"></i>
                            <h5 class="fw-bolder text-body mb-0">1 Attachments</h5>
                        </div>
                        <div class="d-flex flex-column">
                            <a  target="_blank" href="' . $showImage . '" class="mb-50">
                                <img src="' . $showImage . '"
                                    class="me-25" alt="png" height="100" />
                                <small class="text-muted fw-bolder">' . $getAttech->path . '</small>
                            </a>
                            
                        </div>
                    </div>
                </div>
                ';
            }
            $backData  = '
            <div class="row justify-content-end">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header email-detail-head flex-row-reverse">
                            <div
                                class="user-details d-flex justify-content-between align-items-center flex-wrap  flex-row-reverse">
                                <div class="avatar me-75">
                                    <img src="' . $avatar . '"
                                        alt="' . $user->name . '" width="48" height="48" />
                                </div>
                                <div class="mail-items">
                                    <h5 class="mb-0">' . $user->name . '</h5>
                                    <div class="email-info-dropup dropdown">
                                        <span role="button"
                                            class="dropdown-toggle font-small-3 text-muted"
                                            id="dropdownMenuButton200-def" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            ' . $user->email . '
                                        </span>
                                        <div class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton200-def">
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
                            <div class="mail-meta-item d-flex align-items-center">
                                <small class="mail-date-time text-muted">' . $date . ' <br> ' . $time . '</small>
                                <div class="dropdown ms-50">
                                    <div role="button" class="dropdown-toggle hide-arrow"
                                        id="email_more_2-def" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="email_more_2-def">
                                        <div class="dropdown-item"><i
                                                data-feather="corner-up-left"
                                                class="me-50"></i>Reply</div>
                                        <div class="dropdown-item"><i
                                                data-feather="corner-up-right"
                                                class="me-50"></i>Forward</div>
                                        <div class="dropdown-item"><i data-feather="trash-2"
                                                class="me-50"></i>Delete</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body mail-message-wrapper pt-2">
                            <div class="mail-message">
                                <p class="card-text">
                                ' . $RepayData->reply_description . '
                                </p>
                            </div>
                        </div>
                        ' . $attechment . '
                    </div>
                </div>
            </div>';


            return Response::json([
                'status' => true,
                'message' => 'Replay success',
                'backData' => $backData
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Failed!'
            ]);
        }
    }

    public function delete_ticket(Request $request)
    {
        $retArr = array();
        $ids = $request->ids;
        $delte = false;
        if (count($ids) != 0) {
            foreach ($ids as $id) {
                $delte = tickets::where('id', $id)->delete();
                if ($delte) {
                    array_push($retArr, $id);
                }
            }
            return Response::json([
                'status' => true,
                'retArr' => $retArr
            ]);
        }
    }
    public function update_ticket(Request $request)
    {
        $retArr = array();
        $ids = $request->ids;
        $st  = $request->st;
        $update = false;
        if (count($ids) != 0) {
            foreach ($ids as $id) {
                $update = tickets::where('id', $id)->update([
                    'status' => $st
                ]);
                if ($update) {
                    array_push($retArr, $id);
                }
            }
            return Response::json([
                'status' => true,
                'retArr' => $retArr
            ]);
        }
    }


    // this task is  pending
    public function ShowRealTimeRepay(Request $request)
    {

        $timeService =  new GetPhotosService;
        $ticketId  = $request->id;
        $RepayData = ticketReply::where('id', $ticketId)->first();
        $user = User::select('name', 'email')->where('id', auth()->user()->id)->first();
        $avatar = asset(avatar());
        $date =  json_decode($timeService->timeis($RepayData->created_at))->date;
        $time =  json_decode($timeService->timeis($RepayData->created_at))->time;
        $attechment = '';
        if ($RepayData->attch_id != null) {
            $getAttech = ticketAttachment::where('id', $RepayData->attch_id)->first();
            $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
            // contabo file get
            if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {
               
                $showImage = $getAttech->path;
            } else {
                $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
            }
            
            $attechment = '
            <div class="card-footer">
                <div class="mail-attachments">
                    <div class="d-flex align-items-center mb-1">
                        <i data-feather="paperclip" class="font-medium-1 me-50"></i>
                        <h5 class="fw-bolder text-body mb-0">1 Attachments</h5>
                    </div>
                    <div class="d-flex flex-column">
                        <a  target="_blank" href="' . $showImage . '" class="mb-50">
                            <img src="' . $showImage . '"
                                class="me-25" alt="png" height="100" />
                            <small class="text-muted fw-bolder">' . $getAttech->path . '</small>
                        </a>
                        
                    </div>
                </div>
            </div>
            ';
        }
        $backData  = '
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header email-detail-head">
                        <div
                            class="user-details d-flex justify-content-between align-items-center flex-wrap">
                            <div class="avatar me-75">
                                <img src="' . $avatar . '"
                                    alt="' . $user->name . '" width="48" height="48" />
                            </div>
                            <div class="mail-items">
                                <h5 class="mb-0">' . $user->name . '</h5>
                                <div class="email-info-dropup dropdown">
                                    <span role="button"
                                        class="dropdown-toggle font-small-3 text-muted"
                                        id="dropdownMenuButton200-def" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        ' . $user->email . '
                                    </span>
                                    <div class="dropdown-menu"
                                        aria-labelledby="dropdownMenuButton200-def">
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
                        <div class="mail-meta-item d-flex align-items-center">
                            <small class="mail-date-time text-muted">' . $date . ' <br> ' . $time . '</small>
                            <div class="dropdown ms-50">
                                <div role="button" class="dropdown-toggle hide-arrow"
                                    id="email_more_2-def" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-vertical" class="font-medium-2"></i>
                                </div>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="email_more_2-def">
                                    <div class="dropdown-item"><i
                                            data-feather="corner-up-left"
                                            class="me-50"></i>Reply</div>
                                    <div class="dropdown-item"><i
                                            data-feather="corner-up-right"
                                            class="me-50"></i>Forward</div>
                                    <div class="dropdown-item"><i data-feather="trash-2"
                                            class="me-50"></i>Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mail-message-wrapper pt-2">
                        <div class="mail-message">
                            <p class="card-text">
                            ' . $RepayData->reply_description . '
                            </p>
                        </div>
                    </div>
                    ' . $attechment . '
                </div>
            </div>
        </div>';
        return Response::json(['status' => true, 'message' => 'Replay success', 'backData' => $backData]);
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
        $timeService =  new GetPhotosService;
        $item  =  $data;
        $user = User::select('name', 'email')->where('id', $item->replay_by)->first();
        $avatar = asset(avatar($item->replay_by));
        $date =  json_decode($timeService->timeis($item->created_at))->date;
        $time =  json_decode($timeService->timeis($item->created_at))->time;
        if (isset($item['attch_id'])) {
            $getAttech = ticketAttachment::where('id', $item['attch_id'])->first();
            $ext = pathinfo($getAttech->path, PATHINFO_EXTENSION);
            // contabo file get
            if ($ext == 'PNG' or $ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'JPEG' or $ext == 'gif' or $ext == 'GIF') {
                $showImage = $getAttech->path;
            } else {
                $showImage  = asset('admin-assets/app-assets/images/icons/doc.png');
            }
            
            $attechment = '
            <div class="card-footer">
                <div class="mail-attachments">
                    <div class="d-flex align-items-center mb-1">
                        <i data-feather="paperclip" class="font-medium-1 me-50"></i>
                        <h5 class="fw-bolder text-body mb-0">1 Attachments</h5>
                    </div>
                    <div class="d-flex flex-column">
                        <a  target="_blank" href="' . $showImage . '" class="mb-50">
                            <img src="' . $showImage . '"
                                class="me-25" alt="png" height="100" />
                            <small class="text-muted fw-bolder">' . $getAttech->path . '</small>
                        </a>
                        
                    </div>
                </div>
            </div>
            ';
        } else {
            $attechment = '';
        }
        $derection  =  '';
        $rowReverce  = '';
        if ($item['replay_by'] == auth()->user()->id) {
            $derection  = 'justify-content-end';
            $rowReverce = 'flex-row-reverse';
        } else {
            $derection  = 'justify-content-start';
        }

        $item  = '
        <div class="row ' . $derection . '">
            <div class="col-8">
                <div class="card">
                    <div class="card-header email-detail-head ' . $rowReverce . '">
                        <div
                            class="user-details d-flex justify-content-between align-items-center flex-wrap ' . $rowReverce . '">
                            <div class="avatar me-75">
                                <img src="' . $avatar . '"
                                    alt="' . $user->name . '" width="48" height="48" />
                            </div>
                            <div class="mail-items">
                                <h5 class="mb-0">' . $user->name . '</h5>
                                <div class="email-info-dropup dropdown">
                                    <span role="button"
                                        class="dropdown-toggle font-small-3 text-muted"
                                        id="dropdownMenuButton200-' . $item['id'] . '" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        ' . $user->email . '
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
                        <div class="mail-meta-item d-flex align-items-center">
                            <small class="mail-date-time text-muted">' . $date . ' <br> ' . $time . '</small>
                            <div class="dropdown ms-50">
                                <div role="button" class="dropdown-toggle hide-arrow"
                                    id="email_more_2-' . $item['id'] . '" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-vertical" class="font-medium-2"></i>
                                </div>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="email_more_2-' . $item['id'] . '">
                                    <div class="dropdown-item"><i
                                            data-feather="corner-up-left"
                                            class="me-50"></i>Reply</div>
                                    <div class="dropdown-item"><i
                                            data-feather="corner-up-right"
                                            class="me-50"></i>Forward</div>
                                    <div class="dropdown-item"><i data-feather="trash-2"
                                            class="me-50"></i>Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mail-message-wrapper pt-2">
                        <div class="mail-message">
                            <p class="card-text">
                               ' . $item['reply_description'] . '
                            </p>
                        </div>
                    </div>
                    ' . $attechment . '
                </div>
            </div>
        </div>
        ';


        return  $item;
    }
    // END:  Server realtime replay  show system 
    public function allSession(Request $request)

    {
        // $request->session()->forget(['ReplayShowInfo']);
        return $request->session()->all();
    }
}
