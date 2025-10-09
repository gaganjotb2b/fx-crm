<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPverificationMail;
use App\Models\admin\SystemConfig;
use App\Models\Log;
use App\Models\TradingAccount;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\admin\InternalTransfer;
use App\Models\LoginAttempt;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\EmailService;
use App\Services\GoogleAuthenticator;
use App\Services\PermissionService;
use App\Services\systems\VersionControllService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

class MigrationController extends Controller
{
    // view trader login form
    public function showMigration()
    {
        return view('auth.migration');
    }
    public function submitMigration(Request $request)
    {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            // Get file details
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];
            $fileName = $_FILES['csv_file']['name'];
            $fileSize = $_FILES['csv_file']['size'];
            $fileType = $_FILES['csv_file']['type'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
            // Validate file type
            $allowedExtensions = ['csv'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                die("Error: Invalid file type. Only CSV files are allowed.");
            }
        
            // Check file size (example: max 5MB)
            if ($fileSize > 5 * 1024 * 1024) {
                die("Error: File size exceeds 5MB.");
            }
        
            // ***Process CSV file
            $records = array_map('str_getcsv', file($fileTmpPath));
            if (count($records) === 0) {
                die("Error: Uploaded file is empty.");
            }
        
            // Extract header and data
            $fields = array_map('strtolower', $records[0]);
            $fields = array_map(fn($field) => str_replace(' ', '_', $field), $fields);
            array_shift($records); // Remove header row
        
            // Insert into `hb_registrations` table
            foreach ($records as $record) {
                try {
                    $data = array_combine($fields, $record);
                    
                    // // trading account
                    // $trading_account = TradingAccount::where('account_number',$data['cusername'])->first();
                    // $user = User::where('email', $data['clientmail'])->where('type', 0)->first();
                    
                    // if(!$trading_account && $user){
                    //     $it = TradingAccount::create([
                    //         'user_id' => $user->id,     
                    //         'account_number' => $data['cusername'],     
                    //         'group_id' => $data['group_id'],     
                    //         'leverage' => $data['leverage'],     
                    //         'platform' => 'MT5',     
                    //         'account_status' => 1,     
                    //         'client_type' => 'live',     
                    //         'investor_password' => $data['cipassword'],     
                    //         'master_password' => $data['cpassword'],     
                    //         'phone_password' => $data['cppassword'],     
                    //         'block_status' => 1,     
                    //         'commission_status' => 1,     
                    //         'deposit_status' => 1,     
                    //         'withdraw_status' => 1,     
                    //         'active_status' => 1
                    //     ]);
                        
                    //     $it->created_at = date('Y-m-d H:i:s', strtotime($data['date']));
                    //     $it->updated_at = date('Y-m-d H:i:s', strtotime($data['updated_at']));
                    //     $it->save();
                    //     // die;
                    // }
                    
                    // // internal transfer 
                    // $trading_account = TradingAccount::where('account_number',$data['account'])->first();
                    // $it = InternalTransfer::create([
                    //     'user_id' => $trading_account->user_id,     
                    //     'account_id' => $trading_account->id,     
                    //     'platform' => 'MT5',     
                    //     'amount' => $data['amount'],     
                    //     'charge' => 0,     
                    //     'order_id' => null,     
                    //     'type' => $data['type'],     
                    //     'status' => 'A',     
                    //     'invoice_code' => substr(hash('sha256', mt_rand() . microtime()), 0, 16),
                    // ]);
                    
                    // $it->created_at = date('Y-m-d H:i:s', strtotime($data['created_at']));
                    // $it->updated_at = date('Y-m-d H:i:s', strtotime($data['updated_at']));
                    // $it->save();
                    
                    
                    // // trader deposit
                    // $user = User::where('email',$data['cmail'])->where('type', 0)->first();
                    // $status = "P";
                    // if($data['status'] == "Declined"){
                    //     $status = "D";
                    // }elseif($data['status'] == "Approved"){
                    //     $status = "A";
                    // }
                    // $transaction_type = ($data['type'] == "Voucher Deposit")?'voucher':$data['type'];
                    // $it = Deposit::create([
                    //     'user_id' => $user->id,
                    //     'invoice_id' => substr(hash('sha256', mt_rand() . microtime()), 0, 16),
                    //     'transaction_type' => $transaction_type,
                    //     'transaction_id' => null,
                    //     'incode' => '',
                    //     'amount' => $data['ammount'],
                    //     'charge' => 0,
                    //     'bank_proof' => $data['bank_proof'],
                    //     'other_transaction_id' => ($data['other_transaction_id']=="NULL")?null:$data['other_transaction_id'],
                    //     'approved_status' => $status,
                    //     'wallet_type' => 'trader',
                    // ]);
                    
                    // $it->created_at = date('Y-m-d H:i:s', strtotime($data['datetime']));
                    // $it->updated_at = date('Y-m-d H:i:s', strtotime($data['updated_at']));
                    // $it->save();
                    // // die;
                    
                    // trader withdraw
                    $user = User::where('email',$data['email'])->where('type', 0)->first();
                    $status = "P";
                    if($data['status'] == "Declined"){
                        $status = "D";
                    }elseif($data['status'] == "Done"){
                        $status = "A";
                    }
                    
                    $it = Withdraw::create([
                        'user_id' => $user->id,
                        'transaction_id' => substr(hash('sha256', mt_rand() . microtime()), 0, 16),
                        'transaction_type' => $data['type'],
                        'other_transaction_id' => $data['tid'],
                        'amount' => $data['amount'],
                        'charge' => 0,
                        'approved_status' => $status,
                        'wallet_type' => 'trader',
                    ]);
                    
                    $it->created_at = date('Y-m-d H:i:s', strtotime($data['datetime']));
                    $it->updated_at = date('Y-m-d H:i:s', strtotime($data['updated_at']));
                    $it->save();
                    // die;
                    
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
        }
    }
}