<?php

namespace App\Http\Controllers\systems;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\FooterLink;

class FooterLinkSetupController extends Controller
{
    public function footerLink()
    {
        $footer_links = FooterLink::select()->first();
        return view('systems.configurations.footer-link-setup', [
            'footer_links' => $footer_links,
        ]);
    }
    public function footerLinkAdd(Request $request)
    {
        $update = FooterLink::updateOrCreate(
            [
                'id' => $request->footer_link_id
            ],
            [
                'aml_policy'        => $request->aml_policy,
                'contact_us'        => $request->contact_us,
                'privacy_policy'    => $request->privacy_policy,
                'refund_policy'     => $request->refund_policy,
                'terms_and_cond'    => $request->terms_and_cond,
            ]
        );
        if ($update) {
            return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed To Update!']);
        }
    }
}
