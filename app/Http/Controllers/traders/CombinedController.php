<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CombinedController extends Controller
{
    //convert request for ib
    // for combined crm only
    public function convert(Request $request)
    {
        switch ($request->id) {
            case 'mail':
                $mail_status = EmailService::send_email('request-for-ib',[
                    'user_id'=>auth()->user()->id,
                ]);
                if ($mail_status) {
                    return Response::json([
                        'status'=>true,
                        'message'=>'Request successfully send to '.config('app.name')
                    ]);
                }
                return Response::json([
                    'status'=>false,
                    'message'=>'Request successfully send, Mail sending failed!'
                ]);
                break;
            
            default:
                // converting request to admin
                $update = User::where('id',auth()->user()->id)->update([
                    'combine_access'=>2,
                ]);
                if ($update) {
                    return Response::json([
                        'status'=>true,
                        'message'=>'Combine request successfully sending to '.config('app.name')
                    ]);
                }
                return Response::json([
                    'status'=>false,
                    'message'=>'Combine request faild, Please try again later!'
                ]);
                break;
        }
    }
}
