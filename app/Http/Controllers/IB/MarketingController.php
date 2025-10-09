<?php

namespace App\Http\Controllers\IB;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Banner;
use App\Models\Country;
use App\Models\CurrencySetup;
use App\Models\SoftwareSetting;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\BankService;
use App\Services\DataTableService;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function ibBannerView(Request $request)
    {
        return view('ibs.marketing.ib-banner');
    }
    public function traderBannerView(Request $request)
    {
        return view('ibs.marketing.trader-banner');
    }
    public function bannerDescription(Request $request, $banner_user)
    {
        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = Banner::select('size')->where('active_status', 1)->where('use_for', $banner_user);
        $count = 0;
        $result = $result->distinct()->orderBy('size', 'ASC')->get();
        // $result = $result->distinct()->orderBy($dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            // $inner_data = ;
            $column1 = Banner::where('size', $value->size)->where('use_for', $banner_user)->where('active_status', 1)->where('column', 1)->first();
            $column2 = Banner::where('size', $value->size)->where('use_for', $banner_user)->where('active_status', 1)->where('column', 2)->first();
            $column3 = Banner::where('size', $value->size)->where('use_for', $banner_user)->where('active_status', 1)->where('column', 3)->first();
            $banner = asset("Uploads/banners") . "/";

            //Start: refferal link generate
            $referral_link = "";
            $ib_referral = AllFunctionService::ib_referel_link(null);
            $trader_referral = AllFunctionService::trader_referel_link(null);
            if (strtolower($banner_user) == "ib") {
                $parts = parse_url($ib_referral);
                parse_str($parts['query'], $query);
                $referral_link = $query['refer'];
            } else {
                $parts = parse_url($trader_referral);
                parse_str($parts['query'], $query);
                $referral_link = $query['refer'];
            }
            // banner link 3
            $banner_link1 = $banner_link2 = $banner_link3 = "";
            if ($column1) {
                $banner_link1 = asset("marketing/banner") . "/"  . $column1->banner_name . "/" . $referral_link . "/" . $column1->use_for;
            }
            if ($column2) {
                $banner_link2 = asset("marketing/banner") . "/"  . $column2->banner_name . "/" . $referral_link . "/" . $column2->use_for;
            }
            if ($column3) {
                $banner_link3 = asset("marketing/banner") . "/"  . $column3->banner_name . "/" . $referral_link . "/" . $column3->use_for;
            }
            $banner_link1 = str_replace('//marketing', '/marketing', $banner_link1);
            $banner_link2 = str_replace('//marketing', '/marketing', $banner_link2);
            $banner_link3 = str_replace('//marketing', '/marketing', $banner_link3);
            //End: refferal link generate
            
            // banner from contabo
            $banner_1st = FileApiService::contabo_file_path(isset($column1->banner_name)?$column1->banner_name:'');
            $banner_1st_url = $banner_1st['dataUrl'];
            // banner second from contabo
            $banner_2nd = FileApiService::contabo_file_path(isset($column2->banner_name)?$column2->banner_name:'');
            $banner_2nd_url = $banner_2nd['dataUrl'];
            // banner 3rd from contabo
            $banner_3rd = FileApiService::contabo_file_path(isset($column3->banner_name)?$column3->banner_name:'');
            $banner_3rd_url = $banner_3rd['dataUrl'];

            $details = '<div class="details-section-dark border-start-3 border-start-primary p-2">
                            <div class="row">
                                <div class="col-lg-12 p-0">
                                    <div class="rounded-0 w-100">
                                        <div class="card-body p-0">    
                                            <table class="table table-striped text-center">
                                                <tr>
                                                    <td class="td-font column-width">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#img-modal" class=" ' . (isset($column1->banner_name) ? "" : "d-none") . '">
                                                            <img class="img-ad-banner img img-fluid img-thumbnail" src="' . $banner_1st_url . '" alt="image not found" />
                                                        </a>
                                                        <div class="input-group ib-referal mt-3 ' . (isset($column1->banner_name) ? "" : "d-none") . '">
                                                            <input type="text" id="ib-referral-link" class="form-control ib-referral-link" placeholder="https://" value="' . $banner_link1 . '">
                                                            <button class="btn btn-sm btn-outline-primary mb-0 input-group-text bg-primary text-white referral-link" type="button">Copy</button>
                                                        </div>
                                                    </td>
                                                    <td class="td-font column-width">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#img-modal" class=" ' . (isset($column2->banner_name) ? "" : "d-none") . '">
                                                            <img class="img-ad-banner img img-fluid img-thumbnail" src="' . $banner_2nd_url . '" alt="image not found" />
                                                        </a>
                                                        <div class="input-group ib-referal mt-3 ' . (isset($column2->banner_name) ? "" : "d-none") . '">
                                                            <input type="text" id="ib-referral-link" class="form-control ib-referral-link" placeholder="https://" value="' . $banner_link2 . '">
                                                            <button class="btn btn-sm btn-outline-primary mb-0 input-group-text bg-primary text-white referral-link" type="button">Copy</button>
                                                        </div>
                                                    </td>
                                                    <td class="td-font column-width">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#img-modal" class=" ' . (isset($column3->banner_name) ? "" : "d-none") . '">
                                                            <img class="img-ad-banner img img-fluid img-thumbnail" src="' . $banner_3rd_url . '" alt="image not found" />
                                                        </a>
                                                        <div class="input-group ib-referal mt-3 ' . (isset($column3->banner_name) ? "" : "d-none") . '">
                                                            <input type="text" id="ib-referral-link" class="form-control ib-referral-link" placeholder="https://" value="' . $banner_link3 . '">
                                                            <button class="btn btn-sm btn-outline-primary mb-0 input-group-text bg-primary text-white referral-link" type="button">Copy</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
            $data[$i]["size"] = "Banner " . $value->size;
            $data[$i]["extra"]  = $details;
            $i++;
            $count++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return json_encode($res);
    }

    public function viewReferralImage(Request $request)
    {
        $referral_link = "";
        if (!empty($request->cookie('referral_link'))) {
            $referral_link = $request->cookie('referral_link');
        } else {
            $minutes = 1;
            $cookie = cookie('referral_link', $request->referral_link, $minutes);
            $referral_link = $request->referral_link;
            response("")->cookie($cookie);
        }
        // banner from contabo
        $banner_1st = FileApiService::contabo_file_path(isset($request->image_name)?$request->image_name:'');
        $banner_1st_url = $banner_1st['dataUrl'];
        
        return view('ibs.marketing.referral-image-view', [
            'image_name' => $banner_1st_url,
            'referral_link' => $referral_link,
            'use_for' => $request->use_for
        ]);
    }
}
