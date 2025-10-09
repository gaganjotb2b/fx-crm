<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDescription;
use App\Rules\AgeCheck;
use App\Services\api\FileApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class IbProfileController extends Controller
{
    public function profile_update(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $validation_ruls = [
                'name' => 'nullable|string|max:191|min:2',
                'phone' => 'nullable|string|max:25',
                'country' => 'nullable|integer|exists:countries,id',
                'state' => 'nullable|string|max:191',
                'city' => 'nullable|string|max:191',
                'address' => 'nullable|string|max:191',
                'zip_code' => 'nullable|string|max:35',
                'gender' => 'nullable|string|max:25',
                'date_of_birth' => ['nullable', 'date', new AgeCheck],
                'photo' => 'nullable|file|mimes:jpg,png,jpeg|max:2048', // Assuming maximum size is 2MB (2048 kilobytes)
            ];
            $validator = Validator::make($request->all(), $validation_ruls);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the validation errors',
                    'errors' => $validator->errors(),
                ]);
            }
            if (!$ib_user) {
                return Response::json([
                    'status'=>false,
                    'message'=>'You are not an IB',
                ]);
            }
            $user_description = [];
            $require_descriptions_upate = false;
            $require_user_update = false;
            if ($request->input('name')) {
                $ib_user->name = $request->input('name');
                $require_user_update = true;
            }
            if ($request->input('phone')) {
                $ib_user->phone = $request->input('phone');
                $require_user_update = true;
            }
            if ($request->input('country')) {
                $user_description['country_id'] = $request->input('country');
                $require_descriptions_upate = true;
            }
            // take state
            if ($request->input('state')) {
                $user_description['state'] = $request->input('state');
                $require_descriptions_upate = true;
            }
            // take city
            if ($request->input('city')) {
                $user_description['city'] = $request->input('city');
                $require_descriptions_upate = true;
            }
            // take address
            if ($request->input('address')) {
                $user_description['address'] = $request->input('address');
                $require_descriptions_upate = true;
            }
            // take zip_code
            if ($request->input('zip_code')) {
                $user_description['zip_code'] = $request->input('zip_code');
                $require_descriptions_upate = true;
            }
            // take gender
            if ($request->input('gender')) {
                $user_description['gender'] = $request->input('gender');
                $require_descriptions_upate = true;
            }
            // take date_of_birth
            if ($request->input('date_of_birth')) {
                $user_description['date_of_birth'] = date('Y-m-d', strtotime($request->input('date_of_birth')));
                $require_descriptions_upate = true;
            }
            $photo = $request->file('photo');
            if ($photo) {
                $extension = $photo->getClientOriginalExtension();
                $uuid = Uuid::uuid4();
                $filename = str_replace(' ', '-', strtolower(auth()->guard('api')->user()->name)) . '-profile-' . $uuid . '.' . $extension;
                $client = FileApiService::s3_clients();
                // upload file
                $client->putObject([
                    'Bucket' => FileApiService::contabo_bucket_name(),
                    'Key' => $filename,
                    'Body' => file_get_contents($photo)
                ]);
                // delete file
                $user_info = User::with('user_description')->find(auth()->guard('api')->user()->id);
                $profile_photo = $user_info->user_description->getProfileAvaterWithoutPrefix();
                if ($user_info->user_description != "" && $profile_photo != "") {
                    $client->deleteObject([
                        'Bucket' => FileApiService::contabo_bucket_name(),
                        'Key' => $profile_photo,
                    ]);
                }
                $user_description['profile_avater'] = $filename;
                $require_descriptions_upate = true;
            }
            // check all request are empty or not
            if ($require_user_update == false && $require_descriptions_upate == false) {
                return Response::json([
                    'status' => false,
                    'message' => 'You send empty request, for profile update you need to send request atleast one data'
                ]);
            }
            // update user table | when user request not empty
            if ($require_user_update) {
                $update = $ib_user->save();
            }
            // update description table | when description request not empty
            if ($require_descriptions_upate) {
                $update = UserDescription::updateOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                    ],
                    $user_description
                );
            }
            if ($update) {
                // insert activity-----------------
                // $user = new User();
                $user = User::find(auth()->user()->id);
                activity("IB profile update")
                    ->causedBy($user)
                    ->withProperties($request->all())
                    ->event("IB profile update")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been upadte IB profile");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'IB profile successfully updated'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Update failed, Please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, contact for support',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
