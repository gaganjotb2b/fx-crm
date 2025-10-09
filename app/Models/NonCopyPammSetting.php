<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonCopyPammSetting extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'profit_share_status',
        'profit_share_is',
        'min_profit_share',
        'max_profit_share',
        'profit_share',
        'approval_type',
        'requirement_status',
        'master_limit',
        'slave_limit',
        'min_account_deposit',
        'min_account_balance',
        'min_wallet_balance',
        'admin_log',
    ];
}
