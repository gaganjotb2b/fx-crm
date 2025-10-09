<?php

namespace App\Http\Controllers\admins\Bonus;

use App\Http\Controllers\Controller;
use App\Models\BonusCountry;
use App\Models\BonusFor;
use App\Models\BonusGroup;
use App\Models\BonusPackage;
use App\Models\BonusUser;
use App\Models\Country;
use App\Services\AllFunctionService;
use App\Services\client_groups\ClientGroupService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BonusCreateController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('create_bonus', 'admin'));
        $this->middleware(AllFunctionService::access('offers', 'admin'));
    }
    public function createBonus(Request $request)
    {
        $countries = Country::select()->get();
        $bonus = BonusPackage::select()->get();
        $bonus_value = '';
        foreach ($bonus as $value) {
            $value->is_global;
        }
        return view(
            'admins.bonus.create-bonus',
            [
                'countries' => $countries,
                'contest_value' => $bonus_value,
                'bonus_value' => $bonus_value,
                'groups' => ClientGroupService::get_client_group(),
            ]
        );
    }
    // create all clients bonus
    // this is deposit bonus
    public function all_client_bonus(Request $request)
    {
        try {
            // validation
            $country_require = isset($request->is_global) ? 'nullable' : 'required';
            $deposit_require = ($request->bonus_type === 'specific_deposit') ? 'required' : 'nullable';
            $validation_rules = [
                'bonus_name' => 'required|max:191|string',
                'client' => 'nullable|exists:users,id',
                'bonus_type' => 'nullable|max:100',
                'country' => "$country_require|exists:countries,id",
                'groups' => 'nullable',
                'credit_type' => 'nullable',
                'credit_amount' => 'required|numeric|min:1',
                'maximum_bonus' => 'nullable|numeric|min:1',
                'credit_expire' => 'required|numeric|min:1',
                'expire' => 'required',
                'date_to' => 'nullable',
                'date_from' => 'nullable',
                'min_deposit' => "$deposit_require|numeric|min:1"
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // create bonus
            // get expire at date from expire data
            if ($request->expire === 'days') {
                $days = $request->credit_expire;
            } elseif ($request->expire === 'years') {
                $days = $request->credit_expire * 365;
            } elseif ($request->expire === 'months') {
                $days = $request->credit_expire * 30;
            }
            $expire_at = Carbon::now()->addDays($days);
            // update bonus package table
            $create = BonusPackage::create([
                'pkg_name' => $request->bonus_name,
                'credit_type' => $request->credit_type,
                'bonus_amount' => $request->credit_amount,
                'max_bonus' => $request->maximum_bonus,
                'bonus_currency' => $request->currency,
                'bonus_for' => ($request->bonus_client == 1) ? 'all' : 'specific_client',
                'active_status' => 1,
                'is_global' => ($request->is_global == 1) ? 1 : 0,
                'created_by' => auth()->user()->id,
                'start_date' => $request->date_from,
                'end_date' => $request->date_to,
                'min_deposit' => $request->min_deposit,
                'max_deposit' => $request->max_deposit,
                'expire_at' => $expire_at,
                'expire_after' => $days,
                'min_lot' => $request->withdraw_requirement,
                'bonus_type' => $request->bonus_type,
                'bonus_on' => 'deposit'
            ]);
            // create bonus group table
            $total_group = is_array($request->client_groups) ? count($request->client_groups) : 0;
            for ($i = 0; $i < $total_group; $i++) {
                $create_group_package = BonusGroup::create([
                    'bonus_package' => $create->id,
                    'group_id' => $request->client_groups[$i],
                ]);
            }
            // create bonus country table
            $total_country = is_array($request->country) ? count($request->country) : 0;
            for ($i = 0; $i < $total_country; $i++) {
                $create_country = BonusCountry::create([
                    'bonus_package' => $create->id,
                    'country' => $request->country[$i],
                ]);
            }
            // create bonus users
            $total_clients = is_array($request->client) ? count($request->client) : 0;
            for ($i = 0; $i < $total_clients; $i++) {
                $create_client = BonusFor::create([
                    'bonus_package' => $create->id,
                    'user_id' => $request->client[$i],
                ]);
            }
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bonus successfully created',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
    // edit all client bonus
    public function all_client_bonus_edit(Request $request)
    {
        try {
            // validation
            $country_require = ($request->is_global == 1) ? 'nullable' : 'required';
            $deposit_require = ($request->bonus_type === 'specific_deposit') ? 'required' : 'nullable';
            $validation_rules = [
                'bonus_name' => 'required|max:191|string',
                'client' => 'nullable|exists:users,id',
                'bonus_type' => 'nullable|max:100',
                'country' => "$country_require|exists:countries,id",
                'groups' => 'nullable',
                'credit_type' => 'nullable',
                'credit_amount' => 'required|numeric|min:1',
                'maximum_bonus' => 'nullable|numeric|min:1',
                'credit_expire' => 'required|numeric|min:1',
                'expire' => 'required',
                'date_to' => 'nullable',
                'date_from' => 'nullable',
                'min_deposit' => "$deposit_require|numeric|min:1"
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // create bonus
            // get expire at date from expire data
            if ($request->expire === 'days') {
                $days = $request->credit_expire;
            } elseif ($request->expire === 'years') {
                $days = $request->credit_expire * 365;
            } elseif ($request->expire === 'months') {
                $days = $request->credit_expire * 30;
            }
            $expire_at = Carbon::now()->addDays($days);
            // update bonus package table
            $create = BonusPackage::where('id', $request->package_id)->update([
                'pkg_name' => $request->bonus_name,
                'credit_type' => $request->credit_type,
                'bonus_amount' => $request->credit_amount,
                'max_bonus' => $request->maximum_bonus,
                'bonus_currency' => $request->currency,
                'bonus_for' => 'all',
                'active_status' => 1,
                'is_global' => ($request->is_global == 1) ? 1 : 0,
                'created_by' => auth()->user()->id,
                'start_date' => $request->date_from,
                'end_date' => $request->date_to,
                'min_deposit' => $request->min_deposit,
                'max_deposit' => $request->max_deposit,
                'expire_at' => $expire_at,
                'expire_after' => $days,
                'expire_type' => $request->expire,
                'min_lot' => $request->withdraw_requirement,
                'bonus_type' => $request->bonus_type,
            ]);
            // create bonus group table
            $total_group = is_array($request->client_groups) ? count($request->client_groups) : 0;
            for ($i = 0; $i < $total_group; $i++) {
                $create_group_package = BonusGroup::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'group_id' => $request->client_groups[$i],
                ]);
            }
            // create bonus country table
            $total_country = is_array($request->country) ? count($request->country) : 0;
            for ($i = 0; $i < $total_country; $i++) {
                $create_country = BonusCountry::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'country' => $request->country[$i],
                ]);
            }
            // create bonus users
            $total_clients = is_array($request->client) ? count($request->client) : 0;
            for ($i = 0; $i < $total_clients; $i++) {
                $create_client = BonusFor::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'user_id' => $request->client[$i],
                ]);
            }
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bonus successfully updated',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error',
            ]);
        }
    }
    // new registration bonus
    public function new_registration_bonus(Request $request)
    {
        try {

            $country_require = isset($request->is_global) ? 'nullable' : 'required';
            $deposit_require = ($request->bonus_type === 'specific_deposit') ? 'required' : 'nullable';
            $validation_rules = [
                'bonus_name' => 'required|max:191|string',
                'bonus_type' => 'nullable|max:100',
                'country' => "$country_require|exists:countries,id",
                'groups' => 'nullable',
                'credit_type' => 'nullable',
                'credit_amount' => 'required|numeric|min:1',
                'maximum_bonus' => 'nullable|numeric|min:1',
                'credit_expire' => 'required|numeric|min:1',
                'expire' => 'required',
                'date_to' => 'nullable',
                'date_from' => 'nullable',
                'min_deposit' => "$deposit_require|numeric|min:1"
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // create bonus
            // get expire at date from expire data
            if ($request->expire === 'days') {
                $days = $request->credit_expire;
            } elseif ($request->expire === 'years') {
                $days = $request->credit_expire * 365;
            } elseif ($request->expire === 'months') {
                $days = $request->credit_expire * 30;
            }
            $expire_at = Carbon::now()->addDays($days);
            $create = BonusPackage::create([
                'pkg_name' => $request->bonus_name,
                'credit_type' => $request->credit_type,
                'bonus_amount' => $request->credit_amount,
                'max_bonus' => $request->maximum_bonus,
                'bonus_currency' => $request->currency,
                'bonus_for' => 'new_registration',
                'active_status' => 1,
                'is_global' => ($request->is_global === 'on') ? 1 : 0,
                'created_by' => auth()->user()->id,
                'start_date' => $request->date_from,
                'end_date' => $request->date_to,
                'expire_at' => $expire_at,
                'expire_after' => $days,
                'expire_type' => $request->expire,
                'min_lot' => $request->withdraw_requirement,
            ]);
            // create bonus group table
            $total_group = is_array($request->client_groups) ? count($request->client_groups) : 0;
            for ($i = 0; $i < $total_group; $i++) {
                $create_group_package = BonusGroup::create([
                    'bonus_package' => $create->id,
                    'group_id' => $request->client_groups[$i],
                ]);
            }
            // create bonus country table
            $total_country = is_array($request->country) ? count($request->country) : 0;
            for ($i = 0; $i < $total_country; $i++) {
                $create_country = BonusCountry::create([
                    'bonus_package' => $create->id,
                    'country' => $request->country[$i],
                ]);
            }
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bonus successfully created',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
    // account create bonus
    public function new_account_bonus(Request $request)
    {
        try {

            $country_require = isset($request->is_global) ? 'nullable' : 'required';
            $deposit_require = ($request->bonus_type === 'specific_deposit') ? 'required' : 'nullable';
            $validation_rules = [
                'bonus_name' => 'required|max:191|string',
                'client' => 'nullable|exists:users,id',
                'bonus_type' => 'nullable|max:100',
                'country' => "$country_require|exists:countries,id",
                'groups' => 'nullable',
                'credit_type' => 'nullable',
                'credit_amount' => 'required|numeric|min:1',
                'maximum_bonus' => 'nullable|numeric|min:1',
                'credit_expire' => 'required|numeric|min:1',
                'expire' => 'required',
                'date_to' => 'nullable',
                'date_from' => 'nullable',
                'min_deposit' => "$deposit_require|numeric|min:1"
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // create bonus
            // get expire at date from expire data
            if ($request->expire === 'days') {
                $days = $request->credit_expire;
            } elseif ($request->expire === 'years') {
                $days = $request->credit_expire * 365;
            } elseif ($request->expire === 'months') {
                $days = $request->credit_expire * 30;
            }
            $expire_at = Carbon::now()->addDays($days);
            $create = BonusPackage::create([
                'pkg_name' => $request->bonus_name,
                'credit_type' => $request->credit_type,
                'bonus_amount' => $request->credit_amount,
                'max_bonus' => $request->maximum_bonus,
                'bonus_currency' => $request->currency,
                'bonus_for' => ($request->bonus_client == 1) ? 'all' : 'specific_client',
                'active_status' => 1,
                'is_global' => ($request->is_global === 1) ? 1 : 0,
                'created_by' => auth()->user()->id,
                'start_date' => $request->date_from,
                'end_date' => $request->date_to,
                'expire_at' => $expire_at,
                'expire_after' => $days,
                'expire_type' => $request->expire,
                'min_lot' => $request->withdraw_requirement,
                'bonus_on' => 'new_account'
            ]);
            // create bonus group table
            $total_group = is_array($request->client_groups) ? count($request->client_groups) : 0;
            for ($i = 0; $i < $total_group; $i++) {
                $create_group_package = BonusGroup::create([
                    'bonus_package' => $create->id,
                    'group_id' => $request->client_groups[$i],
                ]);
            }
            // create bonus country table
            $total_country = is_array($request->country) ? count($request->country) : 0;
            for ($i = 0; $i < $total_country; $i++) {
                $create_country = BonusCountry::create([
                    'bonus_package' => $create->id,
                    'country' => $request->country[$i],
                ]);
            }
            // create bonus users
            $total_clients = is_array($request->client) ? count($request->client) : 0;
            for ($i = 0; $i < $total_clients; $i++) {
                $create_client = BonusFor::create([
                    'bonus_package' => $create->id,
                    'user_id' => $request->client[$i],
                ]);
            }
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bonus successfully created',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
    // edit new accoun bonus
    public function new_account_bonus_edit(Request $request)
    {
        try {
            $country_require = isset($request->is_global) ? 'nullable' : 'required';
            $deposit_require = ($request->bonus_type === 'specific_deposit') ? 'required' : 'nullable';
            $validation_rules = [
                'bonus_name' => 'required|max:191|string',
                'client' => 'nullable|exists:users,id',
                'bonus_type' => 'nullable|max:100',
                'country' => "$country_require|exists:countries,id",
                'groups' => 'nullable',
                'credit_type' => 'nullable',
                'credit_amount' => 'required|numeric|min:1',
                'maximum_bonus' => 'nullable|numeric|min:1',
                'credit_expire' => 'required|numeric|min:1',
                'expire' => 'required',
                'date_to' => 'nullable',
                'date_from' => 'nullable',
                'min_deposit' => "$deposit_require|numeric|min:1"
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // create bonus
            // get expire at date from expire data
            if ($request->expire === 'days') {
                $days = $request->credit_expire;
            } elseif ($request->expire === 'years') {
                $days = $request->credit_expire * 365;
            } elseif ($request->expire === 'months') {
                $days = $request->credit_expire * 30;
            }
            $expire_at = Carbon::now()->addDays($days);
            $create = BonusPackage::where('id', $request->package_id)->update([
                'pkg_name' => $request->bonus_name,
                'credit_type' => $request->credit_type,
                'bonus_amount' => $request->credit_amount,
                'max_bonus' => $request->maximum_bonus,
                'bonus_currency' => $request->currency,
                'bonus_for' => 'new_account',
                'active_status' => 1,
                'is_global' => ($request->is_global == 1) ? 1 : 0,
                'created_by' => auth()->user()->id,
                'start_date' => $request->date_from,
                'end_date' => $request->date_to,
                'expire_at' => $expire_at,
                'expire_after' => $days,
                'expire_type' => $request->expire,
                'min_lot' => $request->withdraw_requirement,
            ]);
            // create bonus group table
            $total_group = is_array($request->client_groups) ? count($request->client_groups) : 0;
            for ($i = 0; $i < $total_group; $i++) {
                $create_group_package = BonusGroup::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'group_id' => $request->client_groups[$i],
                ]);
            }
            // create bonus country table
            $total_country = is_array($request->country) ? count($request->country) : 0;
            for ($i = 0; $i < $total_country; $i++) {
                $create_country = BonusCountry::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'country' => $request->country[$i],
                ]);
            }
            // create bonus users
            $total_clients = is_array($request->client) ? count($request->client) : 0;
            for ($i = 0; $i < $total_clients; $i++) {
                $create_client = BonusFor::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'user_id' => $request->client[$i],
                ]);
            }
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bonus successfully updated',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a Server error',
            ]);
        }
    }
    // edit new registration bonus
    public function new_reg_bonus_edit(Request $request)
    {
        try {
            $country_require = isset($request->is_global) ? 'nullable' : 'required';
            $deposit_require = ($request->bonus_type === 'specific_deposit') ? 'required' : 'nullable';
            $validation_rules = [
                'bonus_name' => 'required|max:191|string',
                'bonus_type' => 'nullable|max:100',
                'country' => "$country_require|exists:countries,id",
                'groups' => 'nullable',
                'credit_type' => 'nullable',
                'credit_amount' => 'required|numeric|min:1',
                'maximum_bonus' => 'nullable|numeric|min:1',
                'credit_expire' => 'required|numeric|min:1',
                'expire' => 'required',
                'date_to' => 'nullable',
                'date_from' => 'nullable',
                'min_deposit' => "$deposit_require|numeric|min:1"
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // create bonus
            // get expire at date from expire data
            if ($request->expire === 'days') {
                $days = $request->credit_expire;
            } elseif ($request->expire === 'years') {
                $days = $request->credit_expire * 365;
            } elseif ($request->expire === 'months') {
                $days = $request->credit_expire * 30;
            }
            $expire_at = Carbon::now()->addDays($days);
            $create = BonusPackage::where('id', $request->package_id)->update([
                'pkg_name' => $request->bonus_name,
                'credit_type' => $request->credit_type,
                'bonus_amount' => $request->credit_amount,
                'max_bonus' => $request->maximum_bonus,
                'bonus_currency' => $request->currency,
                'bonus_for' => 'new_registration',
                'active_status' => 1,
                'is_global' => ($request->is_global === 'on') ? 1 : 0,
                'created_by' => auth()->user()->id,
                'start_date' => $request->date_from,
                'end_date' => $request->date_to,
                'expire_at' => $expire_at,
                'expire_after' => $days,
                'expire_type' => $request->expire,
                'max_deposit' => $request->max_deposit,
                'min_deposit' => $request->min_deposit,
                'bonus_type' => $request->bonus_type,
                'min_lot' => $request->withdraw_requirement,
            ]);
            // update bonus group table
            $total_group = is_array($request->client_groups) ? count($request->client_groups) : 0;
            for ($i = 0; $i < $total_group; $i++) {
                $create_group_package = BonusGroup::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'group_id' => $request->client_groups[$i]
                ]);
            }
            // update bonus country table
            $total_country = is_array($request->country) ? count($request->country) : 0;
            for ($i = 0; $i < $total_country; $i++) {
                $create_country = BonusCountry::updateOrCreate([
                    'bonus_package' => $request->package_id,
                    'country' => $request->country[$i],
                ]);
            }
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bonus successfully updated',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Get a server error',
            ]);
        }
    }
}
