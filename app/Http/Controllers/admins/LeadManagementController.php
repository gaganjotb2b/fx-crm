<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Country;
use App\Models\LeadManagement;
use App\Models\User;
use App\Models\Task;
use App\Models\LeadComment;
use App\Models\ActiveTask;
use App\Models\ActionsTask;
use App\Services\EmailService;
use App\Models\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\AllFunctionService;
use App\Services\OpenLiveTradingAccountService;
use App\Services\systems\VersionControllService;
use App\Models\UserDescription;


class LeadManagementController extends Controller
{
    public function getLeadManagement(Request $request){

        $categories = Category::select()->get();
        $countries = Country::select()->get();
        

        $op = $request->input('op');

        if($op == 'getEditLead'){
          $id = $request->input('eid');
          $leaduser = LeadManagement::select()->where('id','=',$id)->first();
        }
        if ($op == "data_table") {
          
            return $this->leadManagementTable($request);

            $data['lead'] = $leaduser ;
        }

     
        $data['categories'] = $categories;
        $data['countries'] = $countries;
       
      
        return view('admins.lead-management.lead-management', $data);
    }


    public function leadManagementTable(Request $request){

      $transaction_type = $request->input('transaction_type');
      $status = $request->input('status');
      $info=$request->input('info');
      $verification_status=$request->input('verification_status');
      $ib_email=$request->input('ib_email');
      $from = $request->input('from');
      $to = $request->input('to');
      $min = $request->input('min');
      $max = $request->input('max');


      $result = LeadManagement::select();


      $count_row = $result->count();
      $recordsTotal = $count_row;
      $recordsFiltered = $count_row;
      $result = $result->orderBy('id', 'DESC')->get();
      $data = array();
      $i = 0;
      foreach ($result as $user) {
        
         
          $countries = Country::find($user->country);
          
     
          
          
          $categories = Category::where('id', '=', $user->category_id)->first();
          $account_manager = User::where('id', '=', $user->manager_id)->first();
          $desk_manager = User::where('id', '=', $user->desk_id)->first();
          $account_manager_email = $account_manager->email ?? 'No Account Manager';
          $desk_manager_email = $desk_manager->email ?? 'No Desk Manager';

          if($user->account == ''){
              $account = 'No Account';
          }else{
              $account = $user->account;
          }

          if($user->have_task == '0'){
              $task = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-disc" style="margin-right: 5px; color: #D64B4B"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>';
          }elseif($user->have_task == '1'){
              $task = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-disc" style="margin-right: 5px; color: #4DD79C"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>';
          }elseif($user->have_task == '2'){
              $task = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-disc" style="margin-right: 5px; color: #0090D9"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>';
          }
          $details =	'
          
          <div class="card-body">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab-fill" data-bs-toggle="tab" href="#home-fill'.$user->id.'" role="tab" aria-controls="home-fill" aria-selected="true">Details</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab-fill" data-bs-toggle="tab" href="#profile-fill'.$user->id.'" role="tab" aria-controls="profile-fill" aria-selected="false">Tasks Management</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="messages-tab-fill" data-bs-toggle="tab" href="#messages-fill'.$user->id.'" role="tab" aria-controls="messages-fill" aria-selected="false">Comment</a>
            </li>
          
          </ul>

          <!-- Tab panes -->
          <div class="tab-content pt-1">
            <div class="tab-pane active" id="home-fill'.$user->id.'" role="tabpanel" aria-labelledby="home-tab-fill">
            
                  
                              
                           
                            
                            
                            
                  <table style="text-align: center;" class="table table-responsive tbl-balance">
                      <tbody>
                          <tr>
                              <th>Name : </th>
                              <th>'.$user->name.'</th>

                              <th>Email : </th>
                              <th>'.$user->email.'</th>
                              
                          </tr>

                          <tr>
                              <th>Phone :</th>
                              <th>'.$user->phone.'</th>

                              <th>City : </th>
                              <th>'.$user->city.'</th>
                              
                          </tr>
                          <tr>
                              <th>State :</th>
                              <th>'.$user->state.'</th>

                              <th>Country : </th>
                              <th>'.$countries->name.'</th>
                              
                          </tr>
                          <tr>
                              <th>Category :</th>
                              <th>'.$categories->name.'</th>

                              <th>Manager : </th>
                              <th>
                              <p>Account Manager:  '.$account_manager_email.'</p><p>Desk Manager:  '.$desk_manager_email.'</p>
                              </th>
                             
                              
                          </tr>
                      </tbody>
                     
                  
                  </table></br>
                  <div class="panel-footer" style="float: left;">
                      
                       <button  data-toggle="tooltip" type="button" onclick="convertToAccount('.$user->id.')"  id="convertToAccountBtn"  data-bs-toggle="modal" data-bs-target="#convertToAccount"  class="btn btn-sm btn-primary pull-left">Convert To Account</button>
    
                      <button  data-toggle="tooltip" type="button" onclick="accountManager('.$user->id.')"  id="accountManagerBtn"  data-bs-toggle="modal" data-bs-target="#addAccountManager"  class="btn btn-sm btn-warning pull-left">Asign To Account Manager</button>
                      
                      <button  data-original-title="Edit this user" onclick="deskManager('.$user->id.')"  data-toggle="tooltip" type="button"   id="deskManagerBtn"  data-bs-toggle="modal" data-bs-target="#addDeskManager" class="btn btn-sm btn-danger">Asign To Desk Manager</button>
                      
                  
                  
              </div>

                  <div class="panel-footer" style="float: right;">
                      <button  data-toggle="tooltip" type="button" onclick="sendMail(this)"  data-id="'.$user->id.'" data-name="'.$user->name.'" data-email="'.$user->email.'"  data-bs-toggle="modal" data-bs-target="#sendMail" class="btn btn-sm btn-primary pull-left"><i class="fa fa-envelope" aria-hidden="true"></i></button>
    
                      <button  data-toggle="tooltip" type="button" onclick="clickEdit(this)"  data-id="'.$user->id.'" data-name="'.$user->name.'" data-email="'.$user->email.'" data-phone="'.$user->phone.'"  data-city="'.$user->city.'"  data-state="'.$user->state.'" data-country="'.$user->country.'" data-zip="'.$user->zip.'" data-Category="'.$categories->name.'" id="editleadBtn"  data-bs-toggle="modal" data-bs-target="#updatelead"  class="btn btn-sm btn-warning pull-left"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                      <button  data-original-title="Edit this user" onclick="clickDeleteBtn('.$user->id.')"  data-toggle="tooltip" type="button"   id="deleteLeadBtn"  data-bs-toggle="modal" data-bs-target="#deletelead" class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
                      
                  
                  
              </div>

            </div>
            <div class="tab-pane" id="profile-fill'.$user->id.'" role="tabpanel" aria-labelledby="profile-tab-fill">
              <button  data-toggle="tooltip" style="margin-bottom: 16px;" type="button" onclick="addTask('.$user->id.')"   id="editleadBtn"  data-bs-toggle="modal" data-bs-target="#addtask"  class="btn btn-sm btn-warning pull-left">+ Add Task</button><br>
                <table id="lead_report" style="text-align: center;" class="datatable-inner trading_account table dt-inner-table-darkdt-inner-table-light m-0 no-footer dataTable">
                  <thead>
                      <tr>
                         
                          <th>Task</th>
                          <th>Status</th>
                          <th>Action</th>
                         
                      </tr>
                  </thead>';
                  $taskdetils = Task::where('user_id','=',$user->id)->first();
                  if($taskdetils == ''){
                    $details .=	'<tbody>
                    <tr>
                      <td>No data Available in table</td>
                      <td></td>
                      <td></td>
                      
                    </tr>
                  </tbody>';
                  }else{
                    $details .=	'
                    <tbody>
                     <tr>
                       <td>'.$taskdetils->task_name.'</td>
                       <td>'.$taskdetils->status.'</td>
                       <td>
                       <span class="text-center">
                         <a onclick="completedtask('.$taskdetils->id.')" href="javascript: void(0);"  data-toggle="tooltip" data-placement="right"  title="Completed Task" type="button"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;</a>
                          <a onclick="editetask(this)"  data-id="'.$taskdetils->id.'" data-task_name="'.$taskdetils->task_name.'"  href="javascript: void(0);" data-original-title="" data-toggle="tooltip" data-placement="right" title="Edite Task" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                          <a onclick="deletetask('.$taskdetils->id.')"  href="javascript: void(0);" data-original-title=""  data-bs-toggle="modal" data-bs-target="#deletetask"  data-toggle="tooltip" data-placement="right" title="Delete Task" type="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                      </span>
                       </td>
                     </tr>
                   </tbody>';
                  }
                 





            $details .=	'   
              </table>
            </div>
            <div class="tab-pane" id="messages-fill'.$user->id.'" role="tabpanel" aria-labelledby="messages-tab-fill">
            <button  data-toggle="tooltip" style="margin-bottom: 16px;" type="button" onclick="addComment('.$user->id.')"   id="CommentBtn"  data-bs-toggle="modal" data-bs-target="#addComment"  class="btn btn-sm btn-warning pull-left">+ Add Comment</button><br>
            <table style="text-align: center;" class="datatable-inner trading_account table dt-inner-table-darkdt-inner-table-light m-0 no-footer dataTable">
              <thead>
                  <tr>
                     
                      <th>Comment</th>
                      <th>Action</th>
                     
                  </tr>
              </thead>';
              $Commentdetils = LeadComment::where('user_id','=',$user->id)->first();
              if($Commentdetils == ''){
                $details .=	'<tbody>
                <tr>
                  <td>No data Available in table</td>
                  <td></td>
                  
                </tr>
              </tbody>';
                }else{
                  $details .=	'
                  <tbody>
                    <tr>
                      <td>'.$Commentdetils->note.'</td>
                      <td>
                      <span class="text-center">
                        
                          <a onclick="editeComment(this)" data-acid="0" data-note="'.$Commentdetils->note.'" data-cid="'.$Commentdetils->id.'" href="javascript: void(0);" data-original-title="" data-toggle="tooltip" data-placement="right" title="Edite Task" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                          <a onclick="deleteComment('.$Commentdetils->id.')" data-acid="0"  href="javascript: void(0);" data-original-title=""  data-bs-toggle="modal" data-bs-target="#deleteComment"  data-toggle="tooltip" data-placement="right" title="Delete Task" type="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                      </span>
                      </td>
                    </tr>
                  </tbody>';
                }
                $details .=	'   
              </table>
            </div> 
          </div>
        </div>';
   
          $data[$i]['name'] = $user->name;
          $data[$i]['email'] = $user->email;
          $data[$i]['mobile'] = $user->phone;
          $data[$i]['country'] = $countries->name;
          $data[$i]['category'] =  $categories->name;
          $data[$i]['account'] =  $account;
          $data[$i]['join_date'] =  date($user->created_at);
          $data[$i]['task'] = $task;
          $data[$i]['action'] = '<span class="text-center">
          
          <a onclick="actionCreate(this)" data-acid="0" data-name="'.$user->name.'" data-auid="'.$user->id.'"  href="javascript: void(0);" data-original-title="" data-toggle="tooltip" data-placement="right" title="Add New Action" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        </span>';
          $data[$i]["extra"] 		  = $details;
          $i++;

      }

      
      $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
      $output['data'] = $data;

      return Response::json($output);

  }


  public function addNewLead(Request $request){


      $validation_rules = [
          'name' => 'required',
          'email' => 'required',
          'phone' => 'required',
          'city' => 'required',
          'state' => 'required',
          'country' => 'required',
          'zip' => 'required',
          'category_id' => 'required',
          
      ];
        
          
      $validator = Validator::make($request->all(), $validation_rules);
      if ($validator->fails()) {
          if ($request->ajax()) {
              return Response::json(['status' => false, 'errors' => $validator->errors()]);
          } 
      } 
      $admin = User::find(auth()->user()->id);

      $create = LeadManagement::create([
          'name' =>$request->name,
          'email' => $request->email,
          'phone' => $request->phone,
          'city'=> $request->city,
          'state' => $request->state,
          'country' => $request->country,
          'account' => '',
          'have_task' => 0,
          'category_id' => $request->category_id,
          'zip' => $request->zip,
          'created_by' => $admin->id,
      ]);

      if($create){
        $response['success'] = true;
        $response['message'] = 'Credited successfully';
      }
      echo json_encode($response);
  }

  public function updateLeadManagement(Request $request){


      $validation_rules = [
          'name' => 'required',
          'email' => 'required',
          'phone' => 'required',
          'city' => 'required',
          'country' => 'required',
          'category_id' => 'required',
         
      ];
       
          
      $validator = Validator::make($request->all(), $validation_rules);
      if ($validator->fails()) {
          if ($request->ajax()) {
              return Response::json(['status' => false, 'errors' => $validator->errors()]);
          } 
      } 
      $admin = User::find(auth()->user()->id);

      $create = LeadManagement::where('id', $request->id)->update([
          'name' =>$request->name,
          'email' => $request->email,
          'phone' => $request->phone,
          'city'=> $request->city,
          'state' => $request->state,
          'country' => $request->country,
          'account' => '',
          'have_task' => 0,
          'desk_id' => '',
          'am_id' => '',
          'category_id' => $request->category_id,
          'zip' => $request->zip,
          'updated_by' => $admin->id,
          
      ]);

      if($create){
        $response['success'] = true;
        $response['message'] = 'Credited successfully';
      }
      echo json_encode($response);
  }

  public function deleteLeadManagement(Request $request){


    $id = $request->deletid;
  
          
    LeadManagement:: where('id','=', $id)->delete();

    
      $response['success'] = true;
      $response['message'] = 'Delete successfully<br/>';
      
      echo json_encode($response);


  }

  public function postAddTask(Request $request){

    $id = $request->addtaskuserid;
    $admin = User::find(auth()->user()->id);

    $create = Task::create([
        'task_name' =>$request->task_name,
        'user_id' =>$request->addtaskuserid,
        'open_date' => date('Y-m-d h:i:s'),
        'reminder_time' => date('Y-m-d h:i:s'),
        'status'=> 'open',
        'created_by' => $admin->id,

    ]);

    $updatelead = LeadManagement::where('id', $id)
    ->update(['have_task' => 1]);

    if($create){
      $response['success'] = true;
      $response['message'] = 'Credited successfully';
    }
    echo json_encode($response);
  }

  public function updateTask(Request $request){

    $id = $request->updatetaskId;
    $admin = User::find(auth()->user()->id);

    $create = Task::where('id', $id)->update([
        'task_name' =>$request->update_task_name,
        'updated_by' => $admin->id,
    ]);

    if($create){
      $response['success'] = true;
      $response['message'] = 'Update successfully';
    }
    echo json_encode($response);
  }
  
  public function taskcomplete(Request $request){

    $id = $request->taskuserID;
    $taskName = $request->taskName;
    $admin = User::find(auth()->user()->id);

    $create = Task::where('id', $id)->update([
        'status' =>$taskName,
        'updated_by' => $admin->id,
    ]);
    
      $create = LeadManagement::where('id', $id)->update([
        'have_task' =>2,
    ]);

    if($create){
      $response['success'] = true;
      $response['message'] = 'Task Completed';
    }
    echo json_encode($response);
  }

  public function deletTask(Request $request){


    $id = $request->delettaskid;
  
          
    Task:: where('id','=', $id)->delete();

    
      $response['success'] = true;
      $response['message'] = 'Delete successfully<br/>';
      
      echo json_encode($response);


  }

  public function addComment(Request $request){

    $id = $request->addCommentuserid;


    $admin = User::find(auth()->user()->id);
    $create = LeadComment::create([
        'user_id' =>$request->addCommentuserid,
        'note' =>$request->add_comment,
        'client_id_number' =>0,
        'user_type'=> 'lead',
        'created_by' => $admin->id,
    ]);

    if($create){
      $response['success'] = true;
      $response['message'] = 'Credited successfully';
    }
    echo json_encode($response);
  }

  public function updateComment(Request $request){
    $response['success'] = false;
    $response['message'] = 'Update successfully';

    $id = $request->updateCommentuserid;
    $admin = User::find(auth()->user()->id);


    $create = LeadComment::where('id', $id)->update([
        'note' =>$request->update_comment,
        'updated_by' => $admin->id,
    ]);

    if($create){
      $response['success'] = true;
      $response['message'] = 'Update successfully';
    }
    echo json_encode($response);
  }

  public function deleteComment(Request $request){


    $id = $request->deleteCommentid;

          
    LeadComment:: where('id','=', $id)->delete();

    
      $response['success'] = true;
      $response['message'] = 'Delete successfully<br/>';
      
      echo json_encode($response);


  }

  public function addactions(Request $request){


    $validation_rules = [
        'cname' => 'required',
        'action_type' => 'required',
        'action_status' => 'required',
        'date' => 'required',
        
    ];
      
        
    $validator = Validator::make($request->all(), $validation_rules);
    if ($validator->fails()) {
        if ($request->ajax()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } 
    } 
    $admin = User::find(auth()->user()->id);

    $create = ActionsTask::create([
        'user_id' =>$request->acid,
        'user_type' => $request->user_type,
        'action_type' => $request->action_type,
        'action_status' => $request->action_status,
        'notification' => '',
        'description' => $request->desciption,
        'notify_for' => $request->acid,
        'action_date' => $request->date,
        'created_by' => $admin->id,
    ]);
   $create = LeadManagement::where('id', $request->acid)->update([
        'have_task' =>2,
    ]);
    if($create){
      $response['success'] = true;
      $response['message'] = 'Credited successfully';
    }
    echo json_encode($response);
  }
  
  public function addmanager(Request $request){


      $validation_rules = [
          'accountManagerEmail' => 'required',

      ];
        
          
      $validator = Validator::make($request->all(), $validation_rules);
      if ($validator->fails()) {
          if ($request->ajax()) {
              return Response::json(['status' => false, 'errors' => $validator->errors()]);
          } 
      } 
       $id = $request->AccountManagerid;
       $managerEmail = $request->accountManagerEmail;

      $admin = User::where([
    ['email', '=', $managerEmail],
    ['type', '=', '5']])->first();


      $create = LeadManagement::where('id', $id)->update([
          'manager_id' =>$admin->id,
 
      ]);

      if($create){
        $response['success'] = true;
        $response['message'] = 'Accounr Manager add successfully';
      }
      echo json_encode($response);
  }
  
  public function deskmanager(Request $request){


      $validation_rules = [
          'deskManagerEmail' => 'required',

      ];
        
          
      $validator = Validator::make($request->all(), $validation_rules);
      if ($validator->fails()) {
          if ($request->ajax()) {
              return Response::json(['status' => false, 'errors' => $validator->errors()]);
          } 
      } 
       $id = $request->deskManagerid;
       $managerEmail = $request->deskManagerEmail;

      $admin = User::where([
        ['email', '=', $managerEmail],
        ['type', '=', '5']])->first();


      $create = LeadManagement::where('id', $id)->update([
          'desk_id' =>$admin->id,
 
      ]);

      if($create){
        $response['success'] = true;
        $response['message'] = 'Accounr Manager add successfully';
      }
      echo json_encode($response);
  }
  
  public function sendmail(Request $request){
        
        $id = $request->sendmailId;
        $sendmailEmail = $request->sendmailEmail;
        $subject = $request->subject;
        $message = $request->message;
$user = User::find($id);
    $mail_status = EmailService::send_email('custom-mail', [
            'message_header'                   => $subject,
            'sessage_body'                   => $message,
           
        ]);
         if ($mail_status) {
            // save activity log
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been send verification mail";
            activity('send welcome mail')
                ->causedBy(auth()->user()->id)
                ->withProperties($user)
                ->event('email send')
                ->performedOn($user)
                ->log($description);
            // end: activity log------------------
            $response['success'] = true;
            $response['message'] = 'Mail Send successfully';
            echo json_encode($response);
        } else {
            
             $response['success'] = true;
            $response['message'] = 'Somthing went wrong please try again later!';
            echo json_encode($response);
          
        }
    }
    
public function convertToAccount(Request $request){
       
        $validation_rules = [

            'leverage' => 'required',
            'platform' => 'required',
            'group' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        $user = LeadManagement::where('id', $request->user_id)->first();


        // return $request->user_id;
        $response = OpenLiveTradingAccountService::open_live_account([
            'user_id' => $user->id,
            'platform' => $request->platform,
            'leverage' => $request->leverage,
            'account_type' => $request->group,
        ]);
        
        $password = 'A12345';
        $trans_pin = 'A12345';
        
       if($response['success']){
               $create = User::create([
                  'name' =>$user->name,
                  'email' => $user->email,
                  'phone' => $user->phone,
                  'type' => 0,
                  'password' => Hash::make($password),
                  'transaction_password' => Hash::make($trans_pin),
                  'client_type' => live,
              ])->id;
              
             $user_description = UserDescription::create([
                        'country_id' => $user->country,
                        'city' => $user->city,
                        'state' => $user->state,
                        'zip_code' => $user->zip,
                        'user_id' => $create->id,
                    ]);
    
          if($create){
              
               $log = Log::create([
                        'user_id' => $create->id,
                        'password' => encrypt($password),
                        'transaction_password' => encrypt($trans_pin),
                    ]);
                    
            $response['success'] = true;
            $response['message'] = 'Credited successfully';
          }
      echo json_encode($response);  
       }
        return Response::json($response);
    }
  
}
