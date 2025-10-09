<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComTrade extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'ticket',
        'trading_account',
        'account_no',
        'symbol',
        'volume',
        'open_price',
        'close_price',
        'cmd',
        'profit',
        'comment',
        'open_time',
        'close_time',
        'commission',
        'state',
        'expert_position_id',
        'ib',
        'type',
        'flag',
        'status',
        'recount',
        'ib_mode',
        'ip',
        'created_at',
        'updated_at',
    ];
}
