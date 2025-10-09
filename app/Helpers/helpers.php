<?php

use App\Models\admin\SystemConfig;
use App\Models\company_links;
use App\Models\CopySymbol;
use App\Models\CrmLogo;
use App\Models\IbSetting;
use App\Models\OtpSetting;
use App\Models\theme_setup;
use App\Models\ThemeColor;
use App\Models\ThemeSetup;
use App\Models\TraderSetting;
use App\Models\UserDescription;
use App\Models\UserOtpSetting;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// functions for display logo
function default_avater()
{
    return 'https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:crmassets/images/avater-men.png';
}
function get_admin_logo()
{
    $logo = "";
    $system_data = CrmLogo::select()->first();
    $themes = ThemeSetup::where('use_for', 'admin')->first();
    if ($themes && $system_data) {
        if ($themes->theme_version === 'light-layout') {
            if (isset($system_data->light_logo)) {
                $logo = asset('Uploads/logos/' . $system_data->light_logo);
            } else {
                $logo = get_light_logo();
            }
        } elseif ($themes->theme_version === 'dark-layout') {
            if (isset($system_data->dark_logo)) {
                $logo = asset('Uploads/logos/' . $system_data->dark_logo);
            } else {
                $logo = get_dark_logo();
            }
        } elseif ($themes->theme_version === 'semi-dark-layout') {
            if (isset($system_data->light_logo)) {
                $logo = asset('Uploads/logos/' . $system_data->light_logo);
            } else {
                $logo = get_light_logo();
            }
        }
    } else {
        $logo = asset('admin-assets/app-assets/images/logo/logo.png');
    }
    return ($logo);
}
function get_user_logo()
{
    try {
        $logo = "";
        $system_data = CrmLogo::select()->first();
        $themes = ThemeSetup::where('use_for', 'client')->first();
        if ($system_data && $themes) {
            if ($themes->theme_version === 'dark-layout') {
                if (isset($system_data->dark_logo)) {
                    $logo = asset('Uploads/logos/' . $system_data->dark_logo);
                } else {
                    $logo = get_dark_logo();
                }
            } elseif ($themes->theme_version === 'light-layout') {
                if (isset($system_data->light_logo)) {
                    $logo = asset('Uploads/logos/' . $system_data->light_logo);
                } else {
                    $logo = get_light_logo();
                }
            } elseif ($themes->theme_version === 'semi-dark-layout') {
                if (isset($system_data->light_logo)) {
                    $logo = asset('Uploads/logos/' . $system_data->light_logo);
                } else {
                    $logo = get_light_logo();
                }
            }
        } else {
            $logo = asset('admin-assets/app-assets/images/logo/logo.png');
        }
        return ($logo);
    } catch (\Throwable $th) {
        //throw $th;
        return asset('admin-assets/app-assets/images/logo/logo.png');
    }
}
// functions for get theme
function get_admin_theme()
{
    $theme = "light-layout";
    $system_data = ThemeSetup::where('use_for', 'admin')->select('theme_version')->first();
    if (isset($system_data->theme_version)) {
        $theme = $system_data->theme_version;
    }
    return ($theme);
}

// client theme color
function get_client_theme_color()
{
    try {
        $theme = 'light-version';
        $system_data = ThemeSetup::where('use_for', 'client')->select('theme_version')->first();
        if (isset($system_data->theme_version)) {
            $theme = $system_data->theme_version;
            if ($theme === 'light-layout') {
                $theme = 'light-version';
            } elseif ($theme === 'dark-layout') {
                $theme = 'dark-version';
            }
        }

        return ($theme);
    } catch (\Throwable $th) {
        //throw $th;
        return 'dark-version';
    }
}

// get dark logo
function get_dark_logo()
{

    $logos = CrmLogo::select('dark_layout')->first();
    if (isset($logos->dark_layout)) {
        return (asset('Uploads/logos/' . $logos->dark_layout));
    }
    return (asset('admin-assets/app-assets/images/logo/logo.png'));
}
// get light logo-------------------------------------------------
function get_light_logo()
{

    $logos = CrmLogo::select('light_layout')->first();
    if (isset($logos->light_layout)) {
        return (asset('Uploads/logos/' . $logos->light_layout));
    }
    return (asset('admin-assets/app-assets/images/logo/logo-default-light.png'));
}
// function add check attribute for theme option--------------------------------
// this function for configuration page only
function checked_user_theme($layout)
{
    $theme = "";
    $system_data = ThemeSetup::where('use_for', 'client')->select('theme_version')->first();
    if (isset($system_data->theme_version)) {
        $theme = $system_data->theme_version;
    }
    if ($theme === 'light-layout' && $layout === 'light-layout') {
        return 'checked';
    } elseif ($theme === 'dark-layout' && $layout === 'dark-layout') {
        return 'checked';
    } elseif ($theme === 'semi-dark-dark-layout' && $layout === 'semi-dark-dark-layout') {
        return 'checked';
    } elseif ($theme === 'bordered-layout' && $layout === 'bordered-layout') {
        return 'checked';
    } else {
        if ($layout === 'light-layout') {
            return 'checked';
        }
    }
    return ("");
}
// function add check attribute for admin theme option-------------------------------------
// theme funtion for configuration page only
function checked_admin_theme($layout)
{
    $theme = '';
    $themes = ThemeSetup::where('use_for', 'admin')->select('theme_version')->first();
    if (isset($themes->theme_version)) {
        $theme = $themes->theme_version;
    }
    if ($theme === 'light-layout' && $layout === 'light-layout') {
        return 'checked';
    } elseif ($theme === 'dark-layout' && $layout === 'dark-layout') {
        return 'checked';
    } elseif ($theme === 'semi-dark-dark-layout' && $layout === 'semi-dark-dark-layout') {
        return 'checked';
    } elseif ($theme === 'bordered-layout' && $layout === 'bordered-layout') {
        return 'checked';
    } else {
        if ($layout === 'light-layout') {
            return 'checked';
        }
    }
    return ("");
}

// function for get copyright text in layout--------------------------------
// this function only for layout page
function get_copyright()
{
    $copyright = ucwords(config('app.name'));
    $system_data = SystemConfig::select('copyright')->first();
    if (isset($system_data->copyright)) {
        $copyright = $system_data->copyright;
    }
    return $copyright;
}

/***********************************
    Functions for style controll
    theme table color control
 */
function table_color($admin = null)
{
    if (get_admin_theme() === 'light-layout') {
        return 'dt-inner-table-light';
    } else {
        return 'dt-inner-table-dark';
    }
}

/**************************
 * Funcitons for get mail configuration
 ****************************************/
function mail_configs()
{
    $email = '';
    $system_data = SystemConfig::select()->first();
    if (isset($system_data->auto_email)) {
        $email = $system_data->auto_email;
    }
    return $email;
}


// START: Session for furm submit
// --------------------------------------------------------------------------------------------------------
function multi_submit($form_name, $second = null)
{
    $waiting_time = 300;
    if ($second != null) {
        $waiting_time = $second;
    }
    // start session of form submit
    if (!Session::has($form_name)) {
        session([$form_name => time()]);
    } elseif ((time() - Session::get($form_name)) > $waiting_time) {
        // Session::regenerate($form_name);
        session([$form_name => time()]);
    }
    // if form already submitted
    if (Session::has($form_name)) {
        if (submit_wait($form_name, $second) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// START: Session has multi submit form
// ----------------------------------------------------------------------------------------------------

function has_multi_submit($form_name, $second = null)
{
    if (Session::has($form_name)) {
        if (submit_wait($form_name, ($second == null) ? 300 : $second) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// START: Session submit wait
// ---------------------------------------------------------------------------------------------------------
function submit_wait($form_name, $second = null)
{
    $submit_waitng = time() - session($form_name);
    $time_left = $second - $submit_waitng;
    return ($time_left);
}
function wait_second()
{
    return (15);
}
// end: session submit wait---------------------------------

// start: get top header avatar image-------------------------------
function avatar()
{
    $user_description = UserDescription::where('user_id', auth()->user()->id)->first('gender');
    if (isset($user_description->gender)) {
        $avatar = 'admin-assets/app-assets/images/avatars/' . ((strtolower($user_description->gender) === 'male') ? 'avater-men.png' : 'avater-lady.png');
    } else {
        $avatar = 'admin-assets/app-assets/images/avatars/avater-men.png';
    }

    return $avatar;
}
// end: get top header avatar image

// start generate password------------------------------------------------------------------
function generatePassword($length = 6)
{
    $password = "";
    $possible = "012346789";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
        $length = $maxlength;
    }
    // set up a counter for how many characters are in the password so far
    $i = 0;
    // add random characters to $password until $length is reached
    while ($i < $length) {

        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);

        // have we already used this character in $password?
        if (!strstr($password, $char)) {
            // no, so it's OK to add it onto the end of whatever we've already got...
            $password .= $char;
            // ... and increase the counter by one
            $i++;
        }
    }
    return $password;
}

// get symbol

function copy_symbols()
{
    $symbols = CopySymbol::where('visible', 'visible')->get();
    $data = '';
    foreach ($symbols as $symbol) {
        $data .= '
            <option value="' . $symbol->symbol . '">' . $symbol->symbol . '</option>
        ';
    }
    return $data;
}


// get light logo-------------------------------------------------
function get_favicon_icon()
{

    try {
        $logos = CrmLogo::select('fevicon')->first();
        if (isset($logos->fevicon)) {
            if (isset($logos->fevicon)) {
                return (asset('Uploads/logos/' . $logos->fevicon));
            } else {
                return (asset('trader-assets/assets/img/favicon.png'));
            }
        }
        return (asset('trader-assets/assets/img/favicon.png'));
    } catch (\Throwable $th) {
        //throw $th;
        return (asset('trader-assets/assets/img/favicon.png'));
    }
}
function get_theme_colors_forAll($useFor)
{

    try {
        $themeSetupTab = ThemeColor::where('use_for', $useFor);
        if ($themeSetupTab->count() != 0) {
            return $themeSetupTab->first();
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        //throw $th;
        return false;
    }
}

function get_email_logo()
{
    $logos = CrmLogo::select('email_logo')->first();
    if (isset($logos->email_logo)) {
        return (asset('Uploads/logos/' . $logos->email_logo));
    }
    return (asset('admin-assets/app-assets/images/logo/logo-default-light.png'));
}
function get_footer_links()
{
    $companyLinks = new company_links;
    if ($companyLinks->count() != 0) {
        return company_links::first();
    } else {
        return false;
    }
}
function get_company_name()
{
    try {
        $com_name = SystemConfig::select('com_name')->first();
        if ($com_name) {
            return $com_name->com_name;
        } else {
            return (config('app.name'));
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
}

// meta quotes downloads
function meta_download_link($device = 'windows')
{
    $link = '';
    $platform = SystemConfig::select('platform_type')->first();
    // dd($platform);
    if (strtolower($platform->platform_type) === 'mt5') {
        switch ($device) {
            case 'ios':
                $link = "https://download.mql5.com/cdn/mobile/mt5/ios?utm_source=www.metatrader4.com&utm_campaign=install.metaquotes";
                break;
            case 'android':
                $link = "https://download.mql5.com/cdn/mobile/mt5/android?utm_source=www.metatrader4.com&utm_campaign=install.metaquotes";
                break;

            default:
                $link = "https://download.mql5.com/cdn/web/core.prime.ltd/mt5/coreprimeltd5setup.exe";
                break;
        }
    } elseif (strtolower($platform->platform_type) === 'mt4') {
        switch ($device) {
            case 'ios':
                $link = "https://download.mql5.com/cdn/web/15743/mt4/xflowmarkets4setup.exe";
                break;
            case 'android':
                $link = "https://download.mql5.com/cdn/web/15743/mt4/xflowmarkets4setup.exe";
                break;
            default:
                $link = "https://download.mql5.com/cdn/web/core.prime.ltd/mt5/coreprimeltd5setup.exe";
                break;
        }
    }
    return $link;
}

// check system disable
function system_disable($type)
{
    switch ($type) {
        case 'trader':
            $count = TraderSetting::where('system_disable', 1)->count();
            if ($count) {
                return false;
            } else {
                return true;
            }
            break;

        default:
            $count = IbSetting::where('system_disable', 1)->count();
            if ($count) {
                return false;
            } else {
                return true;
            }
            break;
    }
}
// random color code
function rand_color()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
// check otp is on or off
function check_otp($use_for, $user_id = null)
{
    if ($user_id == null) {
        $user_id = auth()->user()->id;
    }
    $otp_admin = OtpSetting::select()->first();
    if ($otp_admin) {
        $otp_user = UserOtpSetting::where('user_id', $user_id)->first();
        if ($otp_user) {
            switch ($use_for) {
                    // for withdraw operation
                case 'withdraw':
                    if ($otp_admin->withdraw == 1 && $otp_user->withdraw == 1) {
                        return true;
                    }
                    return false;
                    break;
                    // for deposit operation
                case 'deposit':
                    if ($otp_admin->deposit == 1 && $otp_user->deposit == 1) {
                        return true;
                    }
                    return false;
                    break;
                    // for transfer operation
                case 'transfer':
                    if ($otp_admin->transfer == 1 && $otp_user->transfer == 1) {
                        return true;
                    }
                    return false;
                    break;
                case 'account_create':
                    if ($otp_admin->account_create == 1 && $otp_user->account_create == 1) {
                        return true;
                    }
                    return false;
                    break;

                default:
                    if (check_otp($user_id, 'withdraw') == true || check_otp($user_id, 'deposit') == true || check_otp($user_id, 'transfer') == true || check_otp($user_id, 'create_account') == true) {
                        return true;
                    }
                    return false;
                    break;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
// get company social link
function get_company_social_link($social)
{
    $system_config = SystemConfig::select('com_social_info')->first();
    if ($system_config) {
        $social_links = json_decode($system_config->com_social_info);
    } else {
        return false;
    }
    switch ($social) {
        case 'skype':
            return ($social_links->skype);
            break;
        case 'twitter':
            return ($social_links->twitter);
            break;
        case 'youtube':
            return ($social_links->youtube);
            break;
        case 'facebook':
            return ($social_links->facebook);
            break;
        case 'linkedin':
            return ($social_links->linkedin);
            break;
        case 'livechat':
            return ($social_links->livechat);
            break;
        case 'telegram':
            return ($social_links->telegram);
            break;

        default:
            return false;
            break;
    }
}
// get platform name
function get_platform($type = 'single')
{
    switch ($type) {
        case 'multple':
            //code for future plan
            break;

        default:
            $system_config = SystemConfig::select('platform_type')->first();
            if ($system_config) {
                return $system_config->platform_type;
            }
            return "mt5";
            break;
    }
}
// get support mail
function get_support_email()
{
    $company_info = SystemConfig::select()->first();
    $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
    return $support_email;
}
function get_auth_address()
{
    $description = UserDescription::where('user_id', auth()->user()->id)->first();
    if ($description) {
        if ($description->address != "") {
            return ($description->address);
        }
        return ('N/a');
    }
    return ('N/a');
}

function default_support_email()
{
    $app_name = strtolower(config('app.name'));
    $app_name = str_replace(" ", "", $app_name);
    return "support@" . $app_name . ".com";
}

function join_app_name()
{
    $name = config('app.name');
    $name = trim($name, "");
    return strtolower(str_replace(" ", "", $name));
}
