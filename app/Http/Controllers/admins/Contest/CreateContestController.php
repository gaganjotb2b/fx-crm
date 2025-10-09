<?php

namespace App\Http\Controllers\admins\Contest;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\Contest;
use App\Models\ContestCountry;
use App\Models\Country;
use App\Models\IbGroup;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CreateContestController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('create_contest', 'admin'));
        $this->middleware(AllFunctionService::access('contest', 'admin'));
    }
    public function createContest(Request $request)
    {
        $countries = Country::select()->get();
        $group = ClientGroup::whereNot('visibility', 'deleted')->where('account_category', 'live')->get();
        $ib_group = IbGroup::get();
        return view(
            'admins.contest.create-contest',
            [
                'countries' => $countries,
                'groups' => $group,
                'ib_group' => $ib_group,
            ]
        );
    }
    // create and store trader contest
    public function trader_contest(Request $request)
    {
        try {
            $validation_rules = [
                'contest_name' => 'required|max:255',
                'client' => 'required',
                'contest_type' => 'required',
                'client_group' => 'nullable',
                'expire_after' => 'required|numeric',
                'expire_type' => 'required',
                'maximum_contest' => 'nullable|numeric',
                'minimum_join' => 'required|numeric',
                'level' => 'required',
                'amount' => 'required',
                'popup_image' => 'required|max:2048|image|dimensions:6/2',
                'hidden_groups' => 'nullable|array'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // create contest
            $level = $request->level;
            $amount = $request->amount;
            $prices = [];
            $i = 0;
            foreach ($level as $value) {
                $prices[] = [
                    "$value" => $amount[$i],
                ];
                $i++;
            }
            // file upload
            $uploaded_file = $request->file('popup_image');
            $filename = time() . '_popup_' . $uploaded_file->getClientOriginalName();
            // contabo file upload
            $client = FileApiService::s3_clients();
            $client->putObject([
                'Bucket' => FileApiService::contabo_bucket_name(),
                'Key' => $filename,
                'Body' => file_get_contents($uploaded_file)
            ]);
            $create_contest = Contest::create([
                'user_type' => 'trader',
                'contest_name' => $request->contest_name,
                'allowed_for' => $request->client,
                'kyc' => ($request->kyc == 1) ? 1 : 0,
                'contest_type' => $request->contest_type,
                'is_global' => ($request->is_global == 1) ? 1 : 0,
                'client_group' => $request->group,
                'credit_type' => 'fixed',
                'expire_after' => $request->expire_after,
                'expire_type' => $request->expire_type,
                'max_contest' => $request->maximum_contest,
                'min_join' => $request->minimum_join,
                'start_date' => $request->from,
                'end_date' => $request->to,
                'description' => $request->description,
                'contest_prices' => json_encode($prices),
                'status' => 'active',
                'popup_image' => $filename,
                'hidden_groups' => $request->hidden_groups ? json_encode($request->hidden_groups) : null,
            ]);
            // create contest country
            if ($create_contest) {
                if ($request->is_global != 1) {
                    foreach ($request->countries as $key => $value) {
                        $create_country = ContestCountry::create([
                            'country_id' => $value,
                            'contest_id' => $create_contest->id,
                        ]);
                    }
                }
                return redirect()->back()->with('success', 'Contest successfully created');
            }
            return redirect()->back()->withErrors(['error' => 'Something went wrong, please try again later'])->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Got a server error!'])->withInput();
        }
    }
    // create and store trader contest
    public function ib_contest(Request $request)
    {
        try {
            $validation_rules = [
                'contest_name' => 'required|max:255',
                'client' => 'required',
                'contest_type' => 'required',
                'group' => 'nullable',
                'expire_after' => 'required|numeric',
                'expire_type' => 'required',
                'maximum_contest' => 'nullable|numeric',
                'minimum_join' => 'required|numeric',
                'level' => 'required',
                'amount' => 'required',
                'popup_image' => 'required|max:2048|image|dimensions:6/2',
                'hidden_groups' => 'nullable|array'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // create contest
            $level = $request->level;
            $amount = $request->amount;
            $prices = [];
            $i = 0;
            foreach ($level as $value) {
                $prices[] = [
                    "$value" => $amount[$i],
                ];
                $i++;
            }
            // file upload
            $uploaded_file = $request->file('popup_image');
            $filename = time() . '_popup_' . $uploaded_file->getClientOriginalName();
            $uploaded_file->move(public_path('/Uploads/contest'), $filename);
            $create_contest = Contest::create([
                'user_type' => 'ib',
                'contest_name' => $request->contest_name,
                'allowed_for' => $request->client,
                'kyc' => ($request->kyc == 1) ? 1 : 0,
                'contest_type' => $request->contest_type,
                'is_global' => ($request->is_global == 1) ? 1 : 0,
                'ib_group' => $request->group,
                'credit_type' => 'fixed',
                'expire_after' => $request->expire_after,
                'expire_type' => $request->expire_type,
                'max_contest' => $request->maximum_contest,
                'min_join' => $request->minimum_join,
                'start_date' => $request->from,
                'end_date' => $request->to,
                'description' => $request->description,
                'contest_prices' => json_encode($prices),
                'status' => 'active',
                'popup_image' => $filename,
                'hidden_groups' => $request->hidden_groups ? json_encode($request->hidden_groups) : null,
            ]);
            // create contest country
            if ($create_contest) {
                if ($request->is_global != 1) {
                    foreach ($request->countries as $key => $value) {
                        $create_country = ContestCountry::create([
                            'country_id' => $value,
                            'contest_id' => $create_contest->id,
                        ]);
                    }
                }
                return Response::json([
                    'status' => true,
                    'message' => 'Contest successfully created',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // close contest
    public function close_contest(Request $request)
    {
        try {
            // \Log::info('Starting contest close process for contest ID: ' . $request->contest_id);
            
            // Check if frozen_equity column exists
            try {
                $columnExists = \Schema::hasColumn('contest_joins', 'frozen_equity');
                // \Log::info('frozen_equity column exists: ' . ($columnExists ? 'true' : 'false'));
                
                if (!$columnExists) {
                    // \Log::error('frozen_equity column does not exist in contest_joins table');
                    return Response::json([
                        'status' => false,
                        'message' => 'Database column missing. Please run the migration first.',
                    ]);
                }
            } catch (\Exception $e) {
                // \Log::error('Error checking column existence: ' . $e->getMessage());
            }
            
            // First, get all participants for this contest
            $participants = \App\Models\ContestJoin::where('contest_id', $request->contest_id)->get();
            // \Log::info('Found ' . $participants->count() . ' participants for contest');
            
            if ($participants->count() > 0) {
                try {
                    // Get real-time equity for all participants using direct MT5 API
                    $contestStatusController = new \App\Http\Controllers\traders\contest\ContestStatusController();
                    
                    // Update each participant with their frozen equity
                    foreach ($participants as $participant) {
                        try {
                            $accountNumber = $participant->account_number;
                            // \Log::info('Processing account: ' . $accountNumber);
                            
                            // Get direct MT5 equity (the live equity that's working)
                            $frozenEquity = $contestStatusController->getDirectMT5EquityForAccount($accountNumber);
                            
                            // \Log::info("Direct MT5 equity for account {$accountNumber}: {$frozenEquity}");
                            
                            // If direct MT5 equity is 0, try alternative calculation
                            if ($frozenEquity == 0) {
                                // \Log::info('Direct MT5 equity is 0 for account ' . $accountNumber . ', trying alternative calculation');
                                $frozenEquity = $contestStatusController->getAlternativeEquityForAccount($accountNumber);
                            }
                            
                            // Update the participant with frozen equity
                            $participant->update([
                                'frozen_equity' => $frozenEquity
                            ]);
                            
                            // \Log::info("Frozen equity captured for account {$accountNumber}:", [
                            //     'contest_id' => $request->contest_id,
                            //     'frozen_equity' => $frozenEquity
                            // ]);
                        } catch (\Exception $e) {
                            // \Log::error('Error processing participant ' . $participant->account_number . ': ' . $e->getMessage());
                            // Continue with other participants
                        }
                    }
                } catch (\Exception $e) {
                    // \Log::error('Error in equity calculation: ' . $e->getMessage());
                    // Continue with contest closing even if equity calculation fails
                }
            }
            
            // Now close the contest
            $result = Contest::where('id', $request->contest_id)->update([
                'status' => 'closed',
            ]);
            
            if ($result) {
                // \Log::info('Contest successfully closed');
                return Response::json([
                    'status' => true,
                    'message' => 'Contest successfully closed with frozen equity captured',
                ]);
            }
            
                            // \Log::error('Contest closing failed - no rows updated');
            return Response::json([
                'status' => false,
                'message' => 'Contest closing failed, please try again later',
            ]);
            
        } catch (\Throwable $th) {
            // \Log::error('Error closing contest: ' . $th->getMessage());
            // \Log::error('Stack trace: ' . $th->getTraceAsString());
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Please try again later!'
            ]);
        }
    }
    // delete contest parmanentlly
    public function contest_delete(Request $request)
    {
        try {
            $result = Contest::where('id', $request->contest_id)->delete();
            if ($result) {
                return Response::json([
                    'status' => true,
                    'message' => 'Contest successfully deleted',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Contest could not delete, please try again later',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Please try again later'
            ]);
        }
    }
    //Edit Contest
    public function contest_edit(Request $request)
    {
        try {
            $result = Contest::where('id', $request->contest_id)->first();
            if ($result) {
                if ($request->ajax()) {
                    return Response::json([
                        'status'    => true,
                        'user_type' => $result->user_type,
                        'contest_name' => $result->contest_name,
                        'require_kyc'  => $result->kyc,
                        'credit_type'  => $result->credit_type,
                        'expire_after' => $result->expire_after,
                        'expire_type'  => $result->expire_type,
                        'contest_type' => $result->contest_type,
                        'allowed_for'  => $result->allowed_for,
                        'is_global'    => $result->is_global,
                        'client_group' => $result->client_group,
                        'ib_group'     => $result->ib_group,
                        'max_contest'  => $result->max_contest,
                        'description'  => $result->description,
                        'client_limit' => $result->client_limit,
                        'min_join'     => $result->min_join,
                        'start_date'   => $result->start_date,
                        'end_date'     => $result->end_date,
                        'contest_price' => $result->contest_prices,
                        'popup_image'   => $result->popup_image,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
    //Contest list updated by trader
    public function trader_contest_update(Request $request)
    {
        try {
            $validation_rules = [
                'contest_name' => 'required|max:255',
                'client' => 'required',
                'contest_type' => 'required',
                'client_group' => 'nullable',
                'expire_after' => 'required|numeric',
                'expire_type' => 'required',
                'maximum_contest' => 'nullable|numeric',
                'minimum_join' => 'required|numeric',
                'level' => 'required',
                'amount' => 'required',
                'popup_image' => 'required|max:2048|image|dimensions:6/2',
                'hidden_groups' => 'nullable|array'
            ];

            $validator = Validator::make($request->all(), $validation_rules);

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }

            // Create contest prices
            $level = $request->level;
            $amount = $request->amount;
            $prices = [];

            foreach ($level as $index => $value) {
                $prices[] = [
                    $value => $amount[$index],
                ];
            }

            // File upload
            $uploaded_file = $request->file('popup_image');
            $filename = time() . '_popup_' . $uploaded_file->getClientOriginalName();
            $uploaded_file->move(public_path('/Uploads/contest'), $filename);

            // Find the existing contest to update
            $existing_contest = Contest::where('id', $request->id)->update([
                'contest_name' => $request->contest_name,
                'allowed_for'  => $request->client,
                'kyc'          => ($request->kyc == 1) ? 1 : 0,
                'contest_type' => $request->contest_type,
                'is_global'    => ($request->is_global == 1) ? 1 : 0,
                'client_group' => $request->group,
                'credit_type'  => 'fixed',
                'expire_after' => $request->expire_after,
                'expire_type'  => $request->expire_type,
                'max_contest'  => $request->maximum_contest,
                'min_join'     => $request->minimum_join,
                'start_date'   => $request->from,
                'end_date'     => $request->to,
                'description'  => $request->description,
                'contest_prices' => json_encode($prices),
                'status' => 'active',
                'popup_image' => $filename,
                'hidden_groups' => $request->hidden_groups ? json_encode($request->hidden_groups) : null,
            ]);

            // Update or create contest countries if applicable
            if ($request->is_global != 1 && $request->has('countries')) {
                foreach ($request->countries as $value) {
                    ContestCountry::updateOrCreate(
                        [
                            'country_id' => $value,
                            'contest_id' => $existing_contest->id,
                        ]
                    );
                }
            }
            if ($existing_contest) {
                return Response::json([
                    'status' => true,
                    'message' => 'Contest successfully updated',
                ]);
            }

            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }

    //Contest list Update by ib
    public function ib_contest_update(Request $request)
    {
        try {
            $validation_rules = [
                'contest_name' => 'required|max:255',
                'client' => 'required',
                'contest_type' => 'required',
                'group' => 'nullable',
                'expire_after' => 'required|numeric',
                'expire_type' => 'required',
                'maximum_contest' => 'nullable|numeric',
                'minimum_join' => 'required|numeric',
                'level' => 'required',
                'amount' => 'required',
                'popup_image' => 'required|max:2048|image|dimensions:6/2',
                'hidden_groups' => 'nullable|array'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // create contest
            $level = $request->level;
            $amount = $request->amount;
            $prices = [];
            $i = 0;
            foreach ($level as $value) {
                $prices[] = [
                    "$value" => $amount[$i],
                ];
                $i++;
            }
            // file upload
            $uploaded_file = $request->file('popup_image');
            $filename = time() . '_popup_' . $uploaded_file->getClientOriginalName();
            $uploaded_file->move(public_path('/Uploads/contest'), $filename);
            $existing_contest_ib = Contest::where('id', $request->id)->update([
                'contest_name' => $request->contest_name,
                'allowed_for' => $request->client,
                'kyc' => ($request->kyc == 1) ? 1 : 0,
                'contest_type' => $request->contest_type,
                'is_global' => ($request->is_global == 1) ? 1 : 0,
                'ib_group' => $request->group,
                'credit_type' => 'fixed',
                'expire_after' => $request->expire_after,
                'expire_type' => $request->expire_type,
                'max_contest' => $request->maximum_contest,
                'min_join' => $request->minimum_join,
                'start_date' => $request->from,
                'end_date' => $request->to,
                'description' => $request->description,
                'contest_prices' => json_encode($prices),
                'status' => 'active',
                'popup_image' => $filename,
                'hidden_groups' => $request->hidden_groups ? json_encode($request->hidden_groups) : null,
            ]);
            if ($request->is_global != 1) {
                foreach ($request->countries as $key => $value) {
                    ContestCountry::updateOrCreate([
                        'country_id' => $value,
                        'contest_id' => $existing_contest_ib->id,
                    ]);
                }
            }
            if ($existing_contest_ib) {
                return Response::json([
                    'status' => true,
                    'message' => 'Contest successfully Updated',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later',

            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
