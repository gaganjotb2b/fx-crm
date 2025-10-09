<?php 
namespace App\Services;
use App\Models\admin\SystemConfig;
use App\Models\CryptoAddress;
use App\Models\IbCommissionStructure;
use App\Models\IbSetting;
use App\Models\IbSetup;
use App\Models\Symbol;
use App\Models\TraderSetting;
use App\Models\TransactionSetting;

class NotificationService{
    public function __Call($name,$data)
    {
        if($name === 'system_notification'){
            return $this->get_system_notification();
        }
    }
    public static function __callStatic($name, $arguments)
    {
        if($name === 'system_notification'){
            return (new self)->get_system_notification();
        }
    }
    Private function get_system_notification($type = null)
    { 
        $data = [
            'software_settings' => SystemConfig::where('id',auth()->user()->id)->select('*')->first(), 
            'CryptoAddress' => CryptoAddress::where('admin_id',auth()->user()->id)->first(), 
            'Symbol' => Symbol::where('created_by',auth()->user()->id)->first(), 
            'IbSetting' => IbSetting::first(), 
            'IbSetup' => IbSetup::first(), 
            'IbCommissionStructure' => IbCommissionStructure::first(), 
            'TraderSetting' => TraderSetting::first(), 
            'TransactionSetting' => TransactionSetting::first(), 
        ];
        return $data;
    }
}