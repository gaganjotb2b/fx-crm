<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Jenssegers\Mongodb\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;


class CopyTrade extends Model
{
    use HasFactory;
    protected $connection = 'alternate';
    protected $table = 'copy_trades';
    
    protected $fillable = [
        'Order',
        'Login',
        'Dealer',
        'Symbol',
        'Digits',
        'DigitsCurrency',
        'ContractSize',
        'State',
        'Reason',
        'TimeSetup',
        'TimeDone',
        'OpenTime',
        'CloseTime',
        'Type',
        'TypeFill',
        'TypeTime',
        'OpenPrice',
        'ClosePrice',
        'PriceCurrent',
        'PriceSL',
        'PriceTP',
        'Volume',
        'Profit',
        'PositionID',
        'PositionByID',
        'Deal',
        'Comment',
        'ActivationMode',
        'ActivationTime',
        'ActivationPrice',
        'ActivationFlags',
        'ApiData',
        'copy_of',
        'sync',
        'brocker_name',
        'created_at',
        'updated_at',
    ];
}
