<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ticketAttachment;
use App\Models\ticketReply;
use App\Models\tickets;
use App\Models\User;
use App\Services\api\CrmApiService;
use App\Services\api\FileApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class SupportTicketController extends Controller
{
    // create ticket
    public function create_ticket(Request $request)
    {
        try {
            $validation_rules = [
                'subject' => 'required|string|max:100',
                'priority' => 'required|string|max:20|in:normal,high,critical',
                'file_document' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
                'description' => 'required|string|max:191'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            // if validation fails
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation error, Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }

            // if everything was good
            // check rerquest an attatchment
            $photo = $request->file('file_document');
            $filename = $create_attachment = '';
            if ($photo) {
                $extension = $photo->getClientOriginalExtension();
                // / Generate a UUID
                $uuid = Uuid::uuid4();
                $filename = str_replace(' ', '-', $request->input('priority')) . '-ticket-' . $uuid . '.' . $extension;
                $client = FileApiService::s3_clients();
                $client->putObject([
                    'Bucket' => FileApiService::contabo_bucket_name(),
                    'Key' => $filename,
                    'Body' => file_get_contents($photo)
                ]);
                // return $filename;
                $create_attachment = ticketAttachment::create([
                    'path' => $filename,
                ]);
                // return $create_attachment;
            }
            // create ticket
            $create_ticket = tickets::create([
                'user_id' => auth()->guard('api')->user()->id,
                'user_type' => auth()->guard('api')->user()->type,
                'subject' => $request->input('subject'),
                'description' => $request->input('description'),
                'priority' => $request->input('priority', 'normal'),
                'attch_id' => (isset($create_attachment->id)) ? $create_attachment->id : '',
                'status' => 'Open'
            ]);
            // if all everithing done
            if ($create_ticket) {
                return ([
                    'status' => true,
                    'message' => 'Ticket successfully created',
                    'data' => tickets::with(['attachment'])->find($create_ticket->id)
                ]);
            }
            return ([
                'status' => false,
                'message' => 'Somthing went wrong!, Please try again later',
                'code' => '000' //database error
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // get all tickets
    public function get_tickets(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'nullable|string|in:Open,Closed,Answered,In-Progress,On-Hold|max:12',
                'search' => 'nullable|string|max:100',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $tickets = tickets::where('user_id', auth()->guard('api')->user()->id);
            // filter by status
            if ($request->input('status')) {
                $tickets = $tickets->where('status', $request->input('status'));
            }
            // filter by seacrch value
            if ($request->input('search') != "") {
                $tickets = $tickets->where('subject', 'LIKE', "%" . $request->input('search') . "%");
            }
            // count total recodes
            $tickets = $tickets->with(['attachment', 'replyTicket', 'replyTicket.replyBy' => function ($query) {
                $query->select('id', 'name');
            }, 'replyTicket.replyBy.user_description' => function ($query) {
                $query->select('id', 'profile_avater', 'user_id');
            }])->paginate($request->input('per_page', 5));
            if ($tickets) {
                return ([
                    'status' => true,
                    'tickets' => $tickets,
                ]);
            }
            return ([
                'status' => false,
                'tickets' => $tickets,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // get client ticket details
    public function client_ticket_details(Request $request, tickets $ticket_id)
    {

        try {

            $tickets = ticketReply::where('ticket_id', $ticket_id->id)
                ->whereHas('replyOf', function ($query) {
                    $query->where('user_id', auth()->guard('api')->user()->id);
                })
                ->with(['replyBy' => function ($query) {
                    $query->select('id', 'name');
                }, 'replyBy.user_description' => function ($query) {
                    $query->select('id', 'profile_avater', 'user_id');
                }, 'replyOf', 'replyOf.attachment'])
                ->paginate($request->input('per_page', 5));


            if ($tickets) {
                return ([
                    'status' => true,
                    'data' => $tickets,
                ]);
            }
            return ([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // client delete ticket
    public function client_delete_ticket(Request $request, tickets $ticket)
    {
        try {
            // delete from datatable
            $delete = tickets::where('id', $ticket->id)->where('user_id', auth()->guard('api')->user()->id)->delete();
            // delet status true
            if ($delete) {
                return ([
                    'status' => true,
                    'message' => 'Ticket successfully deleted!',
                ]);
            }
            // delete status false
            return ([
                'status' => false,
                'message' => 'Ticket deletion failed, Connection error!',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // create client reply
    public function create_client_reply(Request $request, tickets $ticket)
    {
        try {
            $validation_rules = [
                'file_document' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
                'content' => 'required|max:191|string'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation failed, Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            $photo = $request->file('file_document');
            $filename = $create_attachment = '';
            if ($photo) {
                $extension = $photo->getClientOriginalExtension();
                $uuid = Uuid::uuid4();
                $filename = 'reply-ticket-' . $uuid . '.' . $extension;
                $client = FileApiService::s3_clients();
                $client->putObject([
                    'Bucket' => FileApiService::contabo_bucket_name(),
                    'Key' => $filename,
                    'Body' => file_get_contents($photo)
                ]);
                $create_attachment = ticketAttachment::create([
                    'path' => $filename,
                ]);
            }
            // create ticket reply
            $create = ticketReply::create([
                'ticket_id' => $ticket->id,
                'reply_description' => $request->input('content'),
                'replay_by' => auth()->guard('api')->user()->id,
                'attch_id' => isset($create_attachment->id) ? $create_attachment->id : null,
            ]);
            if ($create) {
                return ([
                    'status' => true,
                    'message' => 'Ticket Reply succesfully created',
                    'data' => ticketReply::with('attachment')->find($create->id)
                ]);
            }
            return ([
                'status' => false,
                'message' => 'Message reply failed, May be database error'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // client ticket reply delte
    public function delete_client_reply(Request $request, ticketReply $reply)
    {
        try {
            $delete = $reply->where('replay_by', auth()->guard('api')->user()->id)->delete();
            if ($delete) {
                return Response::json([
                    'status' => true,
                    'message' => 'Reply message successfully deleted',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Reply message not deleted, please try again later',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
            ]);
        }
    }
}
