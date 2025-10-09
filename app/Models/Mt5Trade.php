<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mt5Trade extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'SYMBOL',
        'DIGITS',
        'CMD',
        'VOLUME',
        'OPEN_TIME',
        'OPEN_PRICE',
        'SL',
        'TP',
        'CLOSE_TIME',
        'EXPIRATION',
        'REASON',
        'DEAL',
        'CONV_RATE1',
        'CONV_RATE2',
        'COMMISSION',
        'COMMISSION_AGENT',
        'SWAPS',
        'CLOSE_PRICE',
        'PROFIT',
        'TAXES',
        'COMMENT',
        'INTERNAL_ID',
        'MARGIN_RATE',
        'TIMESTAMP',
        'MAGIC',
        'GW_VOLUME',
        'GW_OPEN_PRICE',
        'GW_CLOSE_PRICE',
        'MODIFY_TIME',
        'TICKET',
        'LOGIN'
    ];
    public function account()
    {
        return $this->belongsTo(TradingAccount::class, 'LOGIN', 'account_number');
    }

    public function tradeAccount()
    {
        return $this->belongsTo(MetaAccount::class, 'LOGIN', 'account_number');
    }
    
}
