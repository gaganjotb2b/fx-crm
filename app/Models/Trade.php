<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'trading_account',
        'account_no',
        'ticket',
        'symbol',
        'vaolume',
        'cmd',
        'profit',
        'comment',
        'open_time',
        'close_time',
        'commission',
        'status',
        'state',
        'expert_position_id',
        'ip',
        'ib',
        'recount',
        'open_price',
        'close_price',
        'created_at',
        'updated_at',
    ];
}
