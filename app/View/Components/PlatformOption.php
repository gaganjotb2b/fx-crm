<?php

namespace App\View\Components;

use App\Models\admin\SystemConfig;
use App\Models\ClientGroup;
use Illuminate\View\Component;

class PlatformOption extends Component
{
    public $platform;
    public $useFor;
    public $accountType;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($useFor = null, $accountType = null)
    {
        $this->platform = get_platform();
        $this->useFor = $useFor;
        $this->accountType = $accountType;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $system_config = SystemConfig::select('platform_type')->first();
        // start : get platform
        $server = '';
        if ($system_config) {
            if ($system_config->platform_type == 'both') {
                $all_platform = ClientGroup::select('server')
                    ->where('account_category', $accountType ?? 'live')
                    ->where('active_status', 1)
                    ->where('visibility', 'visible')
                    ->distinct()
                    ->get();
                foreach ($all_platform as $row) {
                    $server .= '<option value="' . $row->server . '">' . strtoupper($row->server) . '</option>';
                }
            } else {
                $server .=
                    '<option value="' .
                    $system_config->platform_type .
                    '">' .
                    strtoupper($system_config->platform_type) .
                    '</option>';
            }
        }
        return view('components.platform-option', [
            'server'        => $server,
            'system_config' => $system_config,
        ]);
    }
}
