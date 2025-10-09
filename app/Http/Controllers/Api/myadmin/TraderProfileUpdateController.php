<?php

namespace App\Http\Controllers\Api\myadmin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\UserDescription;
use App\Rules\AgeCheck;
use App\Services\api\FileApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class TraderProfileUpdateController extends Controller
{
    // update client profile data
    public function profile_update(Request $request)
    {
        try {
            $validation_ruls = [
                'name' => 'nullable|string|max:191|min:2',
                'phone' => 'nullable|string|max:25',
                'country' => 'nullable|integer|exists:countries,id',
                'state' => 'nullable|string|max:191',
                'city' => 'nullable|string|max:191',
                'address' => 'nullable|string|max:191',
                'zip_code' => 'nullable|string|max:35',
                'gender' => 'nullable|string|max:25|in:male,female,other',
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
            // filter updateable data
            $user = $user_description = [];
            $require_user_update = $update = false;
            $require_descriptions_upate = false;
            // take name
            if ($request->name != null && $request->name != "") {
                $user['name'] = $request->name;
                $require_user_update = true;
            }
            // take phone
            if ($request->phone != null && $request->phone != "") {
                $user['phone'] = $request->phone;
                $require_user_update = true;
            }
            // take country
            if ($request->country != null && $request->country != "") {
                // get country
                $user_description['country_id'] = $request->country;
                $require_descriptions_upate = true;
            }
            // take state
            if ($request->state != null && $request->state != "") {
                $user_description['state'] = $request->state;
                $require_descriptions_upate = true;
            }
            // take city
            if ($request->city != null && $request->city != "") {
                $user_description['city'] = $request->city;
                $require_descriptions_upate = true;
            }
            // take address
            if ($request->address != null && $request->address != "") {
                $user_description['address'] = $request->address;
                $require_descriptions_upate = true;
            }
            // take zip_code
            if ($request->zip_code != null && $request->zip_code != "") {
                $user_description['zip_code'] = $request->zip_code;
                $require_descriptions_upate = true;
            }
            // take gender
            if ($request->gender != null && $request->gender != "") {
                $user_description['gender'] = $request->gender;
                $require_descriptions_upate = true;
            }
            // take date_of_birth
            if ($request->date_of_birth != null && $request->date_of_birth != "") {
                $user_description['date_of_birth'] = date('Y-m-d', strtotime($request->date_of_birth));
                $require_descriptions_upate = true;
            }
            // profile photo
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
                $update = User::where('users.id', auth()->user()->id)->update($user);
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
                activity("Trader profile update")
                    ->causedBy($user)
                    ->withProperties($request->all())
                    ->event("Trader profile update")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been upadte trader profile");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Trader profile successfully updated'
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
                'message' => 'Got a server error, please contact for support',
                'error'=>$th->getMessage(),
            ]);
        }
    }
}
