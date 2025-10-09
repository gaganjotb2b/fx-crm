<?php

namespace App\Http\Controllers\systems;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\company_links;
use Illuminate\Http\Request;

class SystemCompnayLinks extends Controller
{

    public function view()
    {
        $company_links = company_links::first();
        return view('systems.configurations.company_links', [
            'company_links' => $company_links
        ]);
    }
    public function store(Request $request)
    {
        
        $validation_rules = [];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $checkUserColor =  new company_links;
            if ($checkUserColor->count() != 0) {
                $uAid = $checkUserColor->select('id')->first();
                company_links::where('id', "=", $uAid->id)->update([
                    'aml_policy' => $request->aml_policy,
                    'contact_us' => $request->contact_us,
                    'privacy_policy' => $request->privacy_policy,
                    'refund_policy' => $request->refund_policy,
                    'terms_condition' => $request->terms_condition,
                ]);
            } else {
                company_links::create([
                    'aml_policy' => $request->aml_policy,
                    'contact_us' => $request->contact_us,
                    'privacy_policy' => $request->privacy_policy,
                    'refund_policy' => $request->refund_policy,
                    'terms_condition' => $request->terms_condition,
                ]);
            }
        }

        return Response::json(['status' => true, 'message' => 'Successfully Updated']);
    }
}
