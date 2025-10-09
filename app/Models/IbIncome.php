<?php

namespace App\Models;
ini_set('serialize_precision', 14);
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbIncome extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'ib_id',
        'order_num',
        'trading_account',
        'symbol',
        'cmd',
        'volume',
        'profit',
        'open_time',
        'close_time',
        'comment',
        'amount',
        'com_level',
        'level_com',
        'total_ibs',
        'account_group',
        'ip',
        'ib_group',
        'trader_id',
    ];
    public function ibInfo()
    {
        return $this->belongsTo(User::class, 'ib_id', 'id');
    }
    public function traderInfo()
    {
        return $this->belongsTo(User::class, 'trader_id', 'id');
    }
    
    public function trade() {
        return $this->belongsTo(Mt5Trade::class, 'order_num', 'TICKET');
    }

    public function ibUser() {
        return $this->belongsTo(User::class, 'ib_id');
    }
}
