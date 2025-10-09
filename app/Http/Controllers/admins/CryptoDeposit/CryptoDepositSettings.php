<?php

namespace App\Http\Controllers\admins\CryptoDeposit;

use App\Http\Controllers\Controller;
use App\Models\CryptoAddress;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CryptoDepositSettings extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:add crypto address"]);
        $this->middleware(["role:settings"]);
        // module permission
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('add_crypto_address', 'admin'));
    }
    public function CryptoDeposit(Request $request)
    {
        return view("admins.crypto_settings.crypto_deposit_settings");
    }

    public function CryptoAddress(Request $request, $block_chain)
    {
        $address = CryptoAddress::select('address')->where('name', '=', $block_chain)->first();
        return Response::json(['address' => $address->address ?? ""]);
    }

    public function AddCryptoAddress(Request $request)
    {
        try {
            \Log::info('AddCryptoAddress called with data: ' . json_encode($request->all()));
            
            $validation_rules = [
                'block_chain' => 'required',
                'instrument' => 'required',
                'crypto_address' => 'required',
            ];

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                \Log::info('Validation failed: ' . json_encode($validator->errors()));
                return Response::json(['success' => false, 'message' => 'Please Fix The Following Error', 'errors' => $validator->errors()]);
            }
            $admin_id = auth()->user()->id;
            $admin_email = User::Find($admin_id);
            $user_description = UserDescription::select('country_id')->where('user_id', $admin_id)->first();
            $country_id = $user_description ? $user_description->country_id : null;
            
            \Log::info('Admin ID: ' . $admin_id . ', Country ID: ' . $country_id);
            $ipaddress = request()->ip();
            $token = Str::random(30);
            $data = [
                'address' => $request->crypto_address,
                'name' => strtoupper($request->instrument),
                'block_chain' => strtoupper($request->block_chain),
                'created_by' =>  auth()->user()->email,
                'admin_id' =>  $admin_id,
                'created_ip' => $ipaddress,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'country' => $country_id,
                'token' => $token
            ];

            //check existing address
            $existing_address = CryptoAddress::where('block_chain', strtoupper($request->block_chain))->where('name', strtoupper($request->instrument))->first();
            
            \Log::info('Existing address check - Block chain: ' . strtoupper($request->block_chain) . ', Instrument: ' . strtoupper($request->instrument) . ', Found: ' . ($existing_address ? 'Yes' : 'No'));

            if (isset($existing_address)) {
                $existing_address->address = $request->crypto_address;
                $existing_address->name = $request->instrument;
                $existing_address->block_chain = $request->block_chain;
                $existing_address->created_by = auth()->user()->email;
                $existing_address->admin_id = $admin_id;
                $existing_address->created_ip = $ipaddress;
                $existing_address->browser = $_SERVER['HTTP_USER_AGENT'];
                $existing_address->country = $country_id;
                $existing_address->token = $token;
                $existing_address->verify_1 = 1;
                $existing_address->verify_2 = 1;
                $existing_address->verify_1_at = now();
                $existing_address->verify_2_at = now();
                $existing_address->verify_1_ip = $ipaddress;
                $existing_address->verify_2_ip = $ipaddress;
                $existing_address->status = 1;
                $created = $existing_address->save();
            } else {
                $data['verify_1'] = 1;
                $data['verify_2'] = 1;
                $data['verify_1_at'] = now();
                $data['verify_2_at'] = now();
                $data['verify_1_ip'] = $ipaddress;
                $data['verify_2_ip'] = $ipaddress;
                $data['status'] = 1;
                $created = CryptoAddress::create($data);
            }


            if ($created) {
                \Log::info('Crypto address created/updated successfully');
                return Response::json(['success' => true, 'message' => 'Crypto address successfully saved and activated', 'success_title' => 'Address Activated']);
            }
            
            return Response::json(['success' => false, 'message' => 'Failed to create crypto address']);
            
        } catch (\Exception $e) {
            \Log::error('Crypto Address Creation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return Response::json(['success' => false, 'message' => 'An error occurred while saving the crypto address. Please try again.']);
        }
    }
}
