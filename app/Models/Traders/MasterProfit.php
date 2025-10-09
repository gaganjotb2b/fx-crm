<?php

namespace App\Models\Traders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProfit extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'master_order', 
        'master', 
        'slave_order', 
        'slave', 
        'profit_percent', 
        'slave_profit', 
        'broker_profit_rate', 
        'broker_profit', 
        'amount', 
        'slave_deal_id', 
        'master_deal_id', 
        'status'
    ];
}
