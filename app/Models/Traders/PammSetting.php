<?php

namespace App\Models\Traders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PammSetting extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'profit_share_commission_value',
        'maximum_profit_share_value',
        'minimum_profit_share_value',
        'minimum_account_balance',
        'minimum_wallet_balance',
        'minimum_deposit',
        'pamm_account_limit',
        'slave_limit',
        'master_limit',
        'pamm_global_deposit',
        'profit_share_value',
        'pamm_requirement',
        'profit_share_commission_status',
        'pamm_requirement_status',
        'manual_approve_pamm_reg',
        'profit_share_commission',
        'flexible_profit_share_status',
        'profit_share_status',
        'global_pamm_status',
        'profit_share_margin_value',
        'profit_duration',
    ];
}
